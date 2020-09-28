<?php

namespace App\Models\Filters;

use App\Contracts\Models\FiltersInterface;

/**
 * Class UserFilters
 * @package App\Models\Filters
 */
class UserFilter extends Filters implements FiltersInterface
{
    /**
     * @var string[]
     */
    public $columnsFilter = [
        "status", "email", "name", "from_creation_date", "to_creation_date"
    ];
}
