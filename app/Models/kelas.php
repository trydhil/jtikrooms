<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

// app/Models/Kelas.php
protected $fillable = [
    'username', 
    'password',  // Sekarang plain text atau bcrypt
    'nama_kelas',
    'angkatan', 
    'prodi'
];
    public $timestamps = true;

    // Accessor untuk menampilkan nama lengkap
    public function getNamaLengkapAttribute()
    {
        $prodiNames = [
            'TEKOM' => 'Teknik Komputer',
            'PTIK' => 'Pendidikan Teknik Informatika dan Komputer'
        ];

        $prodi = $prodiNames[$this->prodi] ?? $this->prodi;
        return $prodi . ' - Kelas ' . $this->kelas;
    }

    // Method untuk mendapatkan informasi booking aktif
    public function getActiveBooking()
    {
        return \App\Models\Booking::where('username', $this->username)
            ->where('status', 'active')
            ->where('waktu_berakhir', '>', now())
            ->first();
    }

    // Accessor untuk cek apakah sedang booking
    public function getIsBookingAttribute()
    {
        return \App\Models\Booking::where('username', $this->username)
            ->where('status', 'active')
            ->where('waktu_berakhir', '>', now())
            ->exists();
    }
}