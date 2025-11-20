<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name',
        'username', 
        'mata_kuliah',
        'dosen',
        'waktu_mulai',
        'waktu_berakhir',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_berakhir' => 'datetime',
    ];

    // Relasi ke Room (jika ada model Room)
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_name', 'name');
    }
}