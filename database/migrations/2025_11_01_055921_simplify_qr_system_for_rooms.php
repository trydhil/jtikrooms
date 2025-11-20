<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Hapus field yang tidak perlu (jika ada)
            $table->dropColumn(['qr_path']);
            
            // Kita hanya butuh satu field untuk QR
            $table->string('qr_code')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['qr_code']);
        });
    }
};