<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'location', 
        'employment_type',
        'salary_min',
        'salary_max',
        'description',
        'gambar',
        'status',
        'company_id'
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }
    
}
