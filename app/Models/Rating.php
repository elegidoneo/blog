<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Rating extends Pivot
{
    public $incrementing = true;

    protected $table = "ratings";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function rateable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function qualifier()
    {
        return $this->morphTo();
    }
}
