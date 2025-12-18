<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    // Scope untuk booking aktif
  public function scopeActive($query)
{
    return $query->where('status', 'active')
                ->where('waktu_berakhir', '>', now()->timezone('Asia/Makassar')); // ✅ FIX
}

    // Scope untuk overlap checking
    public function scopeOverlapping($query, $roomName, $startTime, $endTime)
    {
        return $query->where('room_name', $roomName)
                    ->where('status', 'active')
                    ->where(function($q) use ($startTime, $endTime) {
                        $q->where(function($innerQ) use ($startTime, $endTime) {
                            $innerQ->where('waktu_mulai', '<', $endTime)
                                  ->where('waktu_berakhir', '>', $startTime);
                        });
                    });
    }

    // Cek apakah booking masih aktif
   public function isActive()
{
    return $this->status === 'active' && 
           $this->waktu_berakhir->timezone('Asia/Makassar')->gt(now()->timezone('Asia/Makassar')); // ✅ FIX
}

    // Get sisa waktu dalam menit
   public function getRemainingMinutes()
{
    return $this->waktu_berakhir->timezone('Asia/Makassar')
                ->diffInMinutes(now()->timezone('Asia/Makassar'), false); // ✅ FIX
}}