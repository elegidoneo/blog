<?php


namespace App\Models\Filters;

class PostFilter extends Filters
{
    /**
     * @var string[]
     */
    public $columnsFilter = [
        "title", "user_id", "from_creation_date", "to_creation_date"
    ];
}
