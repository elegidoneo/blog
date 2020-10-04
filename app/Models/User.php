<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens, SoftDeletes, CanRate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'admin', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        "created_at" => "datetime:Y-m-d",
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        "token",
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\MorphMany|object|null
     */
    public function getTokenAttribute()
    {
        return $this->tokens()->first();
    }

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return boolval($this->getAttribute('admin'));
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeName(Builder $query, $value)
    {
        return empty($value) ? $query : $query->where("name", "LIKE", "%".$value."%");
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeEmail(Builder $query, $value)
    {
        return empty($value) ? $query : $query->where("email", $value);
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeActive(Builder $query, $value)
    {
        return empty($value) ? $query : $query->where("active", $value);
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
    public function scopeFromCreationDate(Builder $query, $value)
    {
        return empty($value) ? $query : $query->whereDate("create_at", ">=", $value);
    }
}
