<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name', 
        'description',
        'capacity',
        'facilities',
        'status',
        'qr_code',
        'location',
        'type',
        'lantai' // ✅ TAMBAH INI
    ];

    protected $casts = [
        'facilities' => 'array'
    ];

    // ✅ TAMBAH RELATIONSHIP INI
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    // Scope untuk ruangan yang available
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Accessor untuk facilities
    public function getFacilitiesListAttribute()
    {
        return $this->facilities ? implode(', ', $this->facilities) : 'Tidak ada fasilitas';
    }

    // Check if room is available
    public function isAvailable()
    {
        return $this->status === 'available';
    }
    
    // ✅ TAMBAH: Method untuk cek booking aktif
    public function getActiveBooking()
    {
        return \App\Models\Booking::where('room_name', $this->name)
            ->where('status', 'active')
            ->where('waktu_berakhir', '>', now()->timezone('Asia/Makassar'))
            ->first();
    }
    // app/Models/Room.php

public function activeBooking()
{
    return $this->hasOne(Booking::class, 'room_name', 'name')
                ->where('status', 'active')
                ->where('waktu_berakhir', '>', now())
                ->latest('waktu_berakhir');
}

// Add this scope for cleaner code
public function scopeWithAvailability($query)
{
    return $query->with(['activeBooking' => function($q) {
        $q->select('id', 'room_name', 'username', 'mata_kuliah', 
                   'dosen', 'waktu_mulai', 'waktu_berakhir');
    }]);
}
}