<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Kode Ruangan (misal: AE101)
            $table->string('display_name')->nullable(); // Nama Tampilan
            $table->string('location')->default('Gedung JTIK');
            
            // ✅ FIELD WAJIB SESUAI VIEW & CONTROLLER ANDA
            $table->string('lantai')->default('1'); 
            $table->decimal('luas', 8, 2)->default(48.00); 
            
            $table->enum('type', ['kelas', 'lab', 'other'])->default('kelas');
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->text('description')->nullable();
            $table->integer('capacity')->nullable();
            $table->json('facilities')->nullable();
            
            // QR Code Fields
            $table->string('qr_code')->nullable();
            $table->string('permanent_qr_url')->nullable();
            $table->string('qr_content')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};