<?php

namespace App\Models\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class Filters
 * @package App\Models\Filters
 */
abstract class Filters
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    protected $columnsFilter = [];

    /**
     * Filters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        if (!empty($this->columnsFilter)) {
            foreach ($this->getFilters() as $filter => $value) {
                $scopeName = Str::camel($filter);
                if ($builder->hasNamedScope($scopeName)) {
                    $builder = $builder->{$scopeName}($value);
                }
            }
        }

        return $builder;
    }

    /**
     * @return array
     */
    private function getFilters(): array
    {
        return array_filter($this->request->only($this->columnsFilter));
    }
}
