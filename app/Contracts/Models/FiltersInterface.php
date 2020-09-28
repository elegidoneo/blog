<?php


namespace App\Contracts\Models;

use Illuminate\Database\Eloquent\Builder;

interface FiltersInterface
{
    /**
     * @param Builder $builder
     * @return mixed
     */
    public function apply(Builder $builder);
}
