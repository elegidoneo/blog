<?php

namespace App\Models\Pagination;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Pagination
 * @package App\Models\Pagination
 */
abstract class Pagination
{
    /**
     * @var string
     */
    protected $orderBy;

    /**
     * @var string
     */
    protected $orderDir;

    /**
     * @var int
     */
    protected $pages = 10;

    /**
     * {@inheritDoc}
     */
    public function setPages(int $pages): self
    {
        $this->pages = $pages;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setOrderBy(string $orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setOrderDir(string $orderDir): self
    {
        $this->orderDir = $orderDir;
        return $this;
    }



    /**
     * {@inheritDoc}
     */
    public function paged(Builder $builder)
    {
        $builder = $builder->orderBy($this->orderBy, $this->orderDir);
        return $builder->paginate($this->pages);
    }
}
