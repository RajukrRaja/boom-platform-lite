<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'wallet',
        'wallet_balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}