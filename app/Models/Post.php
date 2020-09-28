<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes, CanBeRated;

    /**
     * @var string[]
     */
    protected $fillable = [
        "title",
        "body",
        "image_url",
        "user_id",
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        "created_at" => "datetime:Y-m-d",
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeFromCreationDate(Builder $query, $value)
    {
        return empty($value) ? $query : $query->whereDate("create_at", ">=", $value);
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeToCreationDate(Builder $query, $value)
    {
        return empty($value) ? $query : $query->whereDate("create_at", "<=", $value);
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeTitle(Builder $query, $value)
    {
        return empty($value) ? $query : $query->where("title", "LIKE", "%" . $value . "%");
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeUserId(Builder $query, $value)
    {
        return empty($value) ? $query : $query->where("user_id", $value);
    }
}
