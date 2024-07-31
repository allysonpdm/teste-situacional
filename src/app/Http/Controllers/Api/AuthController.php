<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\Auth\Credentials;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Api\AuthService;
use Illuminate\Http\JsonResponse;

/**
 *
 *     @OA\Info(
 *         title="Your API",
 *         version="1.0.0",
 *     )
 *     @OA\SecurityScheme(
 *         type="http",
 *         securityScheme="bearerAuth",
 *         scheme="bearer",
 *         bearerFormat="JWT"
 *     )
 */
class AuthController extends Controller
{

    public function __construct(protected AuthService $service)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Sign in",
     *     description="Login by email, password",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass user credentials",
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="expires_in", type="integer", example="3600"),
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXBpLnRlc3Rlc2l0dWFjaW9uYWwuY29tLmJyL2FwaS9sb2dpbiIsImlhdCI6MTcyMjM3NjMzNCwiZXhwIjoxNzIyMzc5OTM0LCJuYmYiOjE3MjIzNzYzMzQsImp0aSI6Ik9hdHpNQUYyaUtBdnJrblEiLCJzdWIiOiIxMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.DwciSjXf4kYBTMQ2yPTzTD8Uo-6DnGnEEEqPcGF4Ntw"),
     *             @OA\Property(property="token_type", type="string", example="bearer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Content",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The email field is required. (and 1 more error)"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="array",
     *                     @OA\Items(type="string", example="The password field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = new Credentials(...$request->validated());
        $response = $this->service->login($credentials);

        return response()
            ->json($response);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Log out",
     *     description="Log out the authenticated user",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out.")
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
     */
    public function logout(): JsonResponse
    {
        $this->service->logout();

        return response()
            ->json(['message' => 'Logged out.']);
    }

    /**
     * @OA\Post(
     *     path="/api/refresh",
     *     summary="Refresh token",
     *     description="Refresh the authentication token",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="expires_in", type="integer", example=3600),
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXBpLnRlc3Rlc2l0dWFjaW9uYWwuY29tLmJyL2FwaS9yZWZyZXNoIiwiaWF0IjoxNzIyMzc2MzM0LCJleHAiOjE3MjIzNzk5MzQsIm5iZiI6MTcyMjM3NjMzNCwianRpIjoiT2F0ek1BRjJpS0F2cmtyblEiLCJzdWIiOiIxMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.DwciSjXf4kYBTMQ2yPTzTD8Uo-6DnGnEEEqPcGF4Ntw"),
     *             @OA\Property(property="token_type", type="string", example="bearer")
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
     */
    public function refresh(): JsonResponse
    {
        $response = $this->service->refresh();

        return response()
            ->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Get authenticated user",
     *     description="Get details of the authenticated user",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved user details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=11),
     *             @OA\Property(property="name", type="string", example="Test User"),
     *             @OA\Property(property="email", type="string", example="test@example.com"),
     *             @OA\Property(property="email_verified_at", type="string", format="date-time", example="2024-07-30T20:35:35.000000Z"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-30T20:35:35.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-30T20:35:35.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function me(): JsonResponse
    {
        $response = $this->service->me();

        return response()
            ->json($response);
    }
}
