<?php

namespace App\Enums\Post;

enum DatabaseEnum: string
{
    case COLUMN_SELECTIONS = 'id,title,body,author_id,created_at,updated_at';
}
