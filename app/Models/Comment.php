<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        "user_id",
        "post_id",
        "comment"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
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

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopePostId(Builder $query, $value)
    {
        return empty($value) ? $query : $query->where("post_id", $value);
    }
}
