<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            
            // ✅ FIELD TAMBAHAN AGAR TIDAK ERROR DI VIEW INDEX
            $table->string('nama_kelas')->nullable(); // TEKOM A 2023
            $table->string('prodi', 50)->nullable();  // TEKOM
            $table->string('kelas', 10)->nullable();  // A
            $table->string('angkatan', 10)->nullable(); // 2023
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas');
    }
};