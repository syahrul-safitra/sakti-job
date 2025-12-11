<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'link_website',
        'description',
        'status',
        'email',
        'password',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
