<?php

namespace App\Enums\Comment;

enum DatabaseEnum: string
{
    case COLUMN_SELECTIONS = 'id,body,post_id,user_id,created_at,updated_at';
}
