<?php


namespace App\Models;


trait CanBeRated
{
    /**
     * @param string|null $model
     * @return float
     */
    public function averageRating(string $model = null)
    {
        return $this->qualifiers($model)->avg('score') ?: 0.0;
    }

    /**
     * @param string|null $model
     * @return mixed
     */
    public function qualifiers(string $model = null)
    {
        $modelClass = $model ? (new $model)->getMorphClass() : $this->getMorphClass();

        return $this->morphToMany(
            $modelClass,
            'rateable',
            'ratings',
            'rateable_id',
            "qualifier_id"
        )->withPivot("score", "qualifier_type")
            ->wherePivot("qualifier_type", $modelClass)
            ->wherePivot("rateable_type", $this->getMorphClass());
    }
}
