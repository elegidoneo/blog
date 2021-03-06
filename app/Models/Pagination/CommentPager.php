<?php


namespace App\Models\Pagination;

use App\Contracts\Models\PaginationInterface;

class CommentPager extends Pagination implements PaginationInterface
{
    /**
     * @var string
     */
    protected $orderBy = "created_at";

    /**
     * @var string
     */
    protected $orderDir = "DESC";
}
