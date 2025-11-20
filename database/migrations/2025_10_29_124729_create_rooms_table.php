<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('rooms', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->string('display_name')->nullable();
        $table->string('location')->default('Gedung JTIK');
        $table->integer('floor')->default(2);
        $table->enum('type', ['kelas', 'lab', 'khusus'])->default('kelas');
        $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
        $table->text('description')->nullable();
        $table->integer('capacity')->nullable();
        $table->json('facilities')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
