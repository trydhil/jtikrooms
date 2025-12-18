<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Indeks gabungan untuk mempercepat validasi bentrok jadwal
            $table->index(['room_name', 'status', 'waktu_berakhir'], 'idx_booking_availability');
            
            // Indeks untuk mempercepat pencarian histori user
            $table->index(['username', 'status'], 'idx_user_bookings');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_booking_availability');
            $table->dropIndex('idx_user_bookings');
        });
    }
};