<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // Pastikan kedua kolom ID ini ada!
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            
            $table->text('body');
            $table->boolean('is_anonymous')->default(false);
            $table->enum('type', ['general', 'report'])->default('general');
            $table->enum('status', ['open', 'resolved'])->default('open');
            $table->timestamps();
            
            // Indexing agar query cepat
            $table->index(['room_id', 'type']);
            $table->index(['status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};