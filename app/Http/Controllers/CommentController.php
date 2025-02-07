<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Services\Comment\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Enums\Comment\MessageEnum as CommentMessageEnum;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $comments = Comment::with('post:id,title')
                ->select('id', 'body', 'post_id', 'user_id', 'created_at')
                ->get();

            return response()->json([
                'message' => 'Comments retrieved successfully.',
                'data' => $comments,
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $_) {
            return response()->json([
                'message' => 'Failed to retrieve comments.',
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCommentsForPost(int $postId): JsonResponse
    {
        try {
            $comments = CommentService::getCommentsForPost($postId);
            return response()->json([
                'message' => CommentMessageEnum::SUCCESS_GET,
                'data' => compact('comments'),
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $_) {
            return response()->json([
                'message' => CommentMessageEnum::FAIL_GET,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreCommentRequest $request, int $post): JsonResponse
    {
        try {
            DB::beginTransaction();

            $comment = CommentService::store([
                'body' => $request->body,
                'post_id' => $post,
                'user_id' => $request->user()->id,
            ]);
            DB::commit();
            return response()->json([
                'message' => CommentMessageEnum::SUCCESS_STORE,
                'data' => compact('comment'),
            ], JsonResponse::HTTP_CREATED);
        } catch (\Throwable $_) {
            DB::rollback();
            return response()->json([
                'message' => CommentMessageEnum::FAIL_STORE,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $postId, int $commentId): JsonResponse
    {
        try {
            $comment = CommentService::findCommentById($postId, $commentId);
            return response()->json([
                'message' => $comment ? CommentMessageEnum::SUCCESS_FIND : CommentMessageEnum::FAIL_FIND,
                'data' => compact('comment'),
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $_) {
            return response()->json([
                'message' => 'Failed to retrieve comment.',
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateCommentRequest $request, int $postId, Comment $comment): JsonResponse
    {
        try {
            DB::beginTransaction();

            if ($comment->post_id !== $postId) {
                return response()->json([
                    "message" => "Data not found.",
                ], JsonResponse::HTTP_NOT_FOUND);
            }

            if ($comment->user_id !== Auth::id()) {
                return response()->json([
                    "message" => "Unauthorized action.",
                ], JsonResponse::HTTP_FORBIDDEN);
            }

            $comment = CommentService::update($request, $comment);
            DB::commit();

            return response()->json([
                "message" => CommentMessageEnum::SUCCESS_UPDATE,
                "data" => compact("comment"),
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $_) {
            DB::rollback();
            return response()->json([
                "message" => CommentMessageEnum::FAIL_UPDATE,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(string $post, string $comment): JsonResponse
    {
        try {
            DB::beginTransaction();

            $comment = CommentService::findCommentById($post, $comment);
            if (empty($comment)) {
                return response()->json([
                    "message" => "Comment not found.",
                ], JsonResponse::HTTP_NOT_FOUND);
            }

            if ($comment->user_id !== Auth::id()) {
                return response()->json([
                    "message" => "Unauthorized action.",
                ], JsonResponse::HTTP_FORBIDDEN);
            }

            CommentService::destroy($comment);
            DB::commit();
            return response()->json([
                "message" => "Comment deleted successfully.",
            ], JsonResponse::HTTP_OK);
        } catch (\Throwable $_) {
            DB::rollback();
            return response()->json([
                "message" => "Failed to delete comment.",
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
