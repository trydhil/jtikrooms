<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('about_jtik', function (Blueprint $table) {
            $table->id();
            $table->json('hero_stats')->nullable(); 
            $table->json('info')->nullable();
            $table->json('detail')->nullable();
            $table->timestamps();
        });

        // Insert Data Default
        DB::table('about_jtik')->insert([
            'hero_stats' => json_encode([
                'title' => 'Jurusan Teknik Informatika dan Komputer',
                'subtitle' => 'Menciptakan generasi unggul di bidang teknologi informasi',
                'students' => '500+',
                'lecturers' => '25+',
                'accreditation_badge' => 'A'
            ]),
            'info' => json_encode([
                'address' => 'Jl. A.H. Nasution No.105, Cibiru, Bandung',
                'phone' => '+62 22 1234 5678',
                'email' => 'jtik@universitas.ac.id',
                'maps_url' => '#',
                'operational_hours' => [],
                'study_programs' => [],
                'accreditation' => 'A (Unggul)'
            ]),
            'detail' => json_encode([
                'history' => '-',
                'vision' => '-',
                'missions' => [],
                'achievements' => [],
                'lecturers' => [],
                'staff' => []
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('about_jtik');
    }
};