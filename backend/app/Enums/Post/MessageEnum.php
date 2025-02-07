<?php

namespace App\Enums\Post;

enum MessageEnum: string
{
    case SUCCESS_GET = 'Posts retrieved successfully.';
    case FAIL_GET = 'Failed to retrieve posts.';
    case SUCCESS_FIND = 'Post retrieved successfully.';
    case FAIL_FIND = 'Post not found.';
    case SUCCESS_STORE = 'Post created successfully.';
    case FAIL_STORE = 'Failed to create post.';
    case SUCCESS_UPDATE = 'Post updated successfully.';
    case FAIL_UPDATE = 'Failed to update post.';
    case SUCCESS_DESTROY = 'Post deleted successfully.';
    case FAIL_DESTROY = 'Failed to delete post.';
}
