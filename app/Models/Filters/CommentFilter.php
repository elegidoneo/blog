<?php


namespace App\Models\Filters;


class CommentFilter extends Filters
{
    protected $columnsFilter = [
        "post_id", "user_id"
    ];
}
