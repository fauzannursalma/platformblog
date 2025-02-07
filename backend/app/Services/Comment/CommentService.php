<?php

namespace App\Services\Comment;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use App\Enums\Comment\DatabaseEnum;
use App\Http\Requests\UpdateCommentRequest;

class CommentService
{
    public static function getAllComments(): Collection
    {
        return Comment::with('post:id,title')
            ->select(explode(',', DatabaseEnum::COLUMN_SELECTIONS->value))
            ->get();
    }

    public static function getCommentsForPost(int $postId): Collection
    {
        return Comment::where('post_id', $postId)
            ->with(['user'=> function($query) {
                $query->select('id','name', 'email');
            }])
            ->get();
    }

    public static function getAllCommetsOverPosts(): Collection
    {
        return Comment::with(['user'=> function($query) {
            $query->select('id','name', 'email');
        }])
        ->get();
    }

    public static function findCommentById(int $postId, int $commentId): ?Comment
    {
        return Comment::where('post_id', $postId)
            ->with(['user' => function($query) {
                $query->select('id','name', 'email');
            }])
            ->find($commentId);
    }

    public static function store(array $data): Comment
    {
        return Comment::create($data);
    }

    public static function update(UpdateCommentRequest $request, Comment $comment): Comment
    {
        $comment->update($request->only(['body']));
        return $comment;
    }

    public static function destroy(Comment $comment): void
    {
        $comment->delete();
    }
}
