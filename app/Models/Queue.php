<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'room_id', 
        'queue_number',
        'status', 
        'priority'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function kelas() 
    { 
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    
    public function room() 
    { 
        return $this->belongsTo(Room::class); 
    }

    // Scope untuk antrian aktif
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Method untuk mendapatkan nomor antrian berikutnya
    public static function getNextQueueNumber($roomId)
    {
        $todayQueues = self::where('room_id', $roomId)
                          ->whereDate('created_at', today())
                          ->count();

        return $todayQueues + 1;
    }
}