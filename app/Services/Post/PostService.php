<?php

namespace App\Services\Post;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use App\Enums\Post\DatabaseEnum as PostDatabaseEnum;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{
    public static function get(): Collection
    {
        return Post::select(explode(',', PostDatabaseEnum::COLUMN_SELECTIONS->value))
            ->with(['author' => function($query) {
                $query->select('id','name', 'email');
            }])
            ->get();
    }

    public static function find(int $id): ?Post
    {
        return Post::select(explode(',', PostDatabaseEnum::COLUMN_SELECTIONS->value))
            ->with(['author' => function($query) {
                $query->select('id','name', 'email');
            }])
            ->with('comments')
            ->find($id);
    }

    public static function store(StorePostRequest $request): Post
    {
        return Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'author_id' => $request->user()->id,
        ]);
    }

    public static function update(UpdatePostRequest $request, Post $post): Post
    {
        $post->update($request->only(['title', 'body']));
        return $post;
    }

    public static function destroy(Post $post): void
    {
        $post->delete();
    }

    public static function getWithPaginate(): LengthAwarePaginator
    {
        return Post::paginate(10);
    }
}
