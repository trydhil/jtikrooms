<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id', // SEKARANG SUDAH BENAR
        'room_id', 
        'body', 
        'is_anonymous', 
        'type', 
        'status'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function room() 
    { 
        return $this->belongsTo(Room::class); 
    }
}