<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutJtik extends Model
{
    use HasFactory;

    protected $table = 'about_jtik';
    
    protected $fillable = [
        'info',
        'detail',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
    'info' => 'array',
    'detail' => 'array',
    'hero_stats' => 'array', // Tambahkan ini
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
];
}