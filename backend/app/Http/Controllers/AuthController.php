<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use App\Enums\Auth\MessageEnum as AuthMessageEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = AuthService::register(
                $request->name,
                $request->email,
                $request->password
            );
            DB::commit();

            return response()->json([
                'message' => AuthMessageEnum::SUCCESS_REGISTER->value,
                'data' => ['user' => $user],
            ], JsonResponse::HTTP_CREATED);
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error('Register error: ' . $e->getMessage());
            return response()->json([
                'message' => AuthMessageEnum::FAIL_REGISTER->value,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $token = AuthService::login($validated['email'], $validated['password']);

            return response()->json([
                'message' => AuthMessageEnum::SUCCESS_LOGIN->value,
                'data' => ['token' => $token],
            ], JsonResponse::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => AuthMessageEnum::FAIL_LOGIN,
                'errors' => $e->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $_) {
            return response()->json([
                'message' => AuthMessageEnum::FAIL_LOGIN,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {

            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => AuthMessageEnum::SUCCESS_LOGOUT->value,
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json([
                'message' => AuthMessageEnum::FAIL_LOGOUT->value,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function user(Request $request): JsonResponse
    {
        try {
            $user = AuthService::getUser($request->user());
            return response()->json([
                'message' => AuthMessageEnum::SUCCESS_USER->value,
                'data' => ['user' => $user],
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Get user error: ' . $e->getMessage());
            return response()->json([
                'message' => AuthMessageEnum::FAIL_USER->value,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}


