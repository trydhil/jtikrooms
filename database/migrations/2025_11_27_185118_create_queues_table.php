<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->integer('queue_number');
            $table->enum('status', ['waiting', 'processing', 'completed', 'cancelled'])->default('waiting');
            $table->integer('priority')->default(0);
            $table->timestamps();
            
            $table->index(['room_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('queues');
    }
};