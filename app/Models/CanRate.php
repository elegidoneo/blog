<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

trait CanRate
{
    /**
     * @param Model $model
     * @param float $score
     * @return bool
     */
    public function rate(Model $model, float $score)
    {
        if ($this->hasRated($model)) {
            return false;
        }
        $this->ratings($model)->attach($model->getKey(), ["score" => $score, "rateable_type" => get_class($model)]);
        return true;
    }

    /**
     * @param null $model
     * @return mixed
     */
    public function ratings($model = null)
    {
        $modelClass =  $this->modelClass($model);
        $morphToMany = $this->morphToMany(
            $modelClass,
            "qualifier",
            "ratings",
            "qualifier_id",
            "rateable_id"
        );
        return $morphToMany->as("ranting")->withTimestamps()
            ->withPivot("score", "rateable_type")
            ->wherePivot("rateable_type",  $modelClass)
            ->wherePivot("qualifier_type", $this->getMorphClass());
    }

    /**
     * @param Model $model
     * @return bool
     */
    private function hasRated(Model $model)
    {
        return ! is_null($this->ratings($model->getMorphClass())->find($model->getKey()));
    }

    /**
     * @param null $model
     * @return mixed
     */
    private function modelClass($model = null)
    {
        return $model ? $model : $this->getMorphClass();
    }
}
