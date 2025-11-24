<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{

    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle user login.
     *
     * Validates the incoming request, attempts to authenticate the user and returns a JSON response
     * that includes the authenticated user and an authentication token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @OA\Post(
     *     path="/login",
     *     summary="Login a user",
     *     description="Authenticates a user and returns user data with an authentication token.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful authentication",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIs..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Authentication failed due to invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 description="Validation error details"
     *             )
     *         )
     *     )
     * )
     *
     * Handle user authentication request.
     *
     * @param \Illuminate\Http\Request $request Incoming request containing 'email' and 'password'.
     * @return \Illuminate\Http\JsonResponse JSON response with token on success or error message on failure.
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->authService->login($request->validated());
            if (!$user) {
                return response([
                    'message' => 'These credentials do not match our records.',
                ], 401);
            }
        } catch (\Throwable $th) {
            return response([
                'message' => 'Login Failed!',
                'error' => $th->getMessage()
            ], 500);
        }

        // create access token
        $token = $user->createToken('auth')->plainTextToken;

        // return
        return response([
            'message' => 'Login Successful!',
            'results' => [
                'user' => new UserResource($user),
                'token' => $token
            ]
        ], 201);
    }



    /**
     * Handle user registration.
     *
     * Validates the incoming request, creates a new user and returns a JSON response
     * that includes the created user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @OA\Post(
     *     path="/register",
     *     summary="Register a new user",
     *     description="Creates a new user and returns the user data.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name","last_name","email","password","gender"},
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="gender", type="string", example="male")
     *         )
     *     )
     * )
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {

        try {
            $user = $this->authService->register($request->validated());
        } catch (\Throwable $th) {
            return response([
                'message' => 'Registration Failed!',
                'error' => $th->getMessage()
            ], 500);
        }

        return response([
            'message' => 'Registration Successful!',
            'results' => [
                'user' => new UserResource($user),
            ]
        ], 201);
    }

    /**
     * Handle user logout.
     *
     * Revokes the current user's authentication token and returns a JSON response
     * indicating successful logout.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     path="/logout",
     *     summary="Logout the authenticated user",
     *     description="Revokes the current user's authentication token.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     *
     * @param \Illuminate\Http\Request $request Incoming request from the authenticated user.
     * @return \Illuminate\Http\Response JSON response indicating logout status.
     */
    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Logout success',
        ]);
    }
}
