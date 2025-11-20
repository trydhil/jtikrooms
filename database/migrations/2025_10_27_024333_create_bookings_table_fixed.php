<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hanya buat table jika belum ada
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->string('room_name');
                $table->string('username');
                $table->string('mata_kuliah')->nullable();
                $table->string('dosen')->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamp('waktu_mulai')->useCurrent();
$table->timestamp('waktu_berakhir')->useCurrent();
                $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
                $table->timestamps();

                // Index untuk performa
                $table->index(['room_name', 'status']);
                $table->index(['waktu_mulai', 'waktu_berakhir']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};