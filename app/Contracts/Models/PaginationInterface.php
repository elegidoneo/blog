<?php


namespace App\Contracts\Models;

use App\Models\Pagination\Pagination;
use Illuminate\Database\Eloquent\Builder;

interface PaginationInterface
{
    /**
     * @param Builder $builder
     * @return mixed
     */
    public function paged(Builder $builder);

    /**
     * @param int $pages
     * @return Pagination
     */
    public function setPages(int $pages): Pagination;

    /**
     * @param string $orderBy
     * @return Pagination
     */
    public function setOrderBy(string $orderBy): Pagination;

    /**
     * @param string $orderDir
     * @return Pagination
     */
    public function setOrderDir(string $orderDir): Pagination;
}
