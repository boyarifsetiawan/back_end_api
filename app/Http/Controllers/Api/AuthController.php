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
     * @OA\Post(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="Authenticate a user and issue an access token",
     *     description="Validates user credentials and returns a JSON response containing an access token, token type and expiration time. Intended for API clients to authenticate and receive a bearer token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com", description="User email address"),
     *             @OA\Property(property="password", type="string", format="password", example="secret", description="User password")
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


        // login user
        $user = $this->authService->login($request);

        if (!$user) {
            return response([
                'message' => 'These credentials do not match our records.',
            ], 401);
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
     * that includes the newly created user and an authentication token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @OA\Post(
     *     path="/register",
     *     summary="Register a new user",
     *     description="Creates a new user account and returns user data with an authentication token.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="P@ssw0rd!"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="P@ssw0rd!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *                 ),
     *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJI...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", description="Validation errors keyed by field")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred.")
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {

        $user = $this->authService->register($request);

        // $token = $user->createToken('auth')->plainTextToken;

        return response([
            'message' => 'Registration Successful!',
            'results' => [
                'user' => new UserResource($user),
                // 'token' => $token
            ]
        ], 201);
    }


    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Logout success',
        ]);
    }
}
