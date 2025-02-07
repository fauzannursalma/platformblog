<?php

namespace App\Enums\Comment;

enum MessageEnum: string
{
    case SUCCESS_GET = 'Comments retrieved successfully.';
    case FAIL_GET = 'Failed to retrieve comments.';
    case SUCCESS_FIND = 'Comment retrieved successfully.';
    case FAIL_FIND = 'Comment not found.';
    case SUCCESS_STORE = 'Comment created successfully.';
    case FAIL_STORE = 'Failed to create comment.';
    case SUCCESS_UPDATE = 'Comment updated successfully.';
    case FAIL_UPDATE = 'Failed to update comment.';
    case SUCCESS_DESTROY = 'Comment deleted successfully.';
    case FAIL_DESTROY = 'Failed to delete comment.';
}
