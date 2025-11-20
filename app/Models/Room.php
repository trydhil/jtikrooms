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
    'qr_code', // HANYA INI YANG DIBUTUHKAN
    'location',
    'type'
];

    protected $casts = [
        'facilities' => 'array'
    ];

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
}