<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::get('{post}', [PostController::class, 'show']);
    Route::get('{post}/comments', [CommentController::class, 'getCommentsForPost']);
    Route::get('{post}/{comment}', [CommentController::class, 'show']);
});

Route::get('/comments', [CommentController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('/posts/{post}/comments/{comment}', [CommentController::class, 'update'])
    ->missing(function () {
        return response()->json([
            'message' => 'Data not found.',
        ], JsonResponse::HTTP_NOT_FOUND);
    });
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy']);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Route not found.',
    ], JsonResponse::HTTP_NOT_FOUND);
});
