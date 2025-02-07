<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\Post\PostService;
use Illuminate\Http\JsonResponse;
use App\Enums\Post\MessageEnum as PostMessageEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse {
        try {
            $posts = PostService::get();
            return response()->json([
                "message" => PostMessageEnum::SUCCESS_GET,
                "data" => compact("posts"),
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $_) {
            return response()->json([
                "message" => PostMessageEnum::FAIL_GET,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request) : JsonResponse
    {
        try {
            DB::beginTransaction();
            $post = PostService::store($request);
            DB::commit();
            return response()->json([
                "message" => PostMessageEnum::SUCCESS_STORE,
                "data" => $post,
            ], JsonResponse::HTTP_CREATED);
        } catch (\Throwable $_) {
            return response()->json([
                "message" => PostMessageEnum::FAIL_STORE,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $post = PostService::find((int) $id);
            return response()->json([
                "message" => empty($post) ? PostMessageEnum::FAIL_FIND : PostMessageEnum::SUCCESS_FIND,
                "data" => compact("post"),
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $_) {
            return response()->json([
                "message" => PostMessageEnum::FAIL_FIND,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $post = PostService::find((int) $id);
            if (empty($post)) {
                return response()->json([
                    "message" => PostMessageEnum::FAIL_UPDATE,
                ], JsonResponse::HTTP_NOT_FOUND);
            }

            if ($post->author_id !== Auth::id()) {
                return response()->json([
                    "message" => "Unauthorized action.",
                ], JsonResponse::HTTP_FORBIDDEN);
            }

            $post = PostService::update($request, $post);
            DB::commit();
            return response()->json([
                "message" => PostMessageEnum::SUCCESS_UPDATE,
                "data" => compact("post"),
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $_) {
            return response()->json([
                "message" => PostMessageEnum::FAIL_UPDATE,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $post = PostService::find((int) $id);
            if (empty($post)) {
                return response()->json([
                    "message" => PostMessageEnum::FAIL_FIND,
                ], JsonResponse::HTTP_NOT_FOUND);
            }
            if ($post->author_id !== Auth::id()) {
                return response()->json([
                    "message" => "Unauthorized action.",
                ], JsonResponse::HTTP_FORBIDDEN);
            }
            PostService::destroy($post);
            DB::commit();
            return response()->json([
                "message" => PostMessageEnum::SUCCESS_DESTROY,
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $_) {
            DB::rollback();
            return response()->json([
                "message" => PostMessageEnum::FAIL_DESTROY,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
