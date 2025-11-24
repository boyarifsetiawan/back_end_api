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
     * @OA\Post(
     *     path="/login",
     *     summary="Logs in an existing user",
     *     description="Logs in an existing user and returns a token.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login Successful!"),
     *             @OA\Property(property="results", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *                 @OA\Property(property="token", type="string", example="your_auth_token")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="These credentials do not match our records.")
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
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login Failed!"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
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
        ], 200);
    }



    /**
     * Handle user registration.
     *
     * @OA\Post(
     *     path="/register",
     *     summary="Registers a new user",
     *     description="Registers a new user and returns the user object.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email","password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Registration Successful!"),
     *             @OA\Property(property="results", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *             )
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
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Registration Failed!"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     * @param RegisterRequest $request
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
