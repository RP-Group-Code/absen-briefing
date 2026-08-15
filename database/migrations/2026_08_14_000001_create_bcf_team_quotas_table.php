<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bcf_team_quotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('nourut')->unique();
            $table->string('team')->unique();
            $table->string('warna')->unique();
            $table->string('penanggung_jawab');
            $table->unsignedInteger('capacity')->default(40);
            $table->timestamps();
        });

        $now = now();
        DB::table('bcf_team_quotas')->insert([
            ['nourut' => 1, 'team' => 'BRILIAN', 'warna' => 'Ungu', 'penanggung_jawab' => 'Oktareza', 'capacity' => 40, 'created_at' => $now, 'updated_at' => $now],
            ['nourut' => 2, 'team' => 'BRISPOT', 'warna' => 'Hitam', 'penanggung_jawab' => 'Fadil', 'capacity' => 40, 'created_at' => $now, 'updated_at' => $now],
            ['nourut' => 3, 'team' => 'BRISMART', 'warna' => 'Biru Tua', 'penanggung_jawab' => 'Andreas', 'capacity' => 40, 'created_at' => $now, 'updated_at' => $now],
            ['nourut' => 4, 'team' => 'BRIMO', 'warna' => 'Biru Muda', 'penanggung_jawab' => 'David', 'capacity' => 40, 'created_at' => $now, 'updated_at' => $now],
            ['nourut' => 5, 'team' => 'BRIMEN', 'warna' => 'Putih', 'penanggung_jawab' => 'Pak Budi', 'capacity' => 40, 'created_at' => $now, 'updated_at' => $now],
            ['nourut' => 6, 'team' => 'BRICARE', 'warna' => 'Kuning', 'penanggung_jawab' => 'Wiliam', 'capacity' => 40, 'created_at' => $now, 'updated_at' => $now],
            ['nourut' => 7, 'team' => 'BRIAPI', 'warna' => 'Merah', 'penanggung_jawab' => 'Pak Iwan', 'capacity' => 40, 'created_at' => $now, 'updated_at' => $now],
            ['nourut' => 8, 'team' => 'BRIPEDIA', 'warna' => 'Hijau', 'penanggung_jawab' => 'Baim', 'capacity' => 40, 'created_at' => $now, 'updated_at' => $now],
            ['nourut' => 9, 'team' => 'BRILINK', 'warna' => 'Orange', 'penanggung_jawab' => 'Febin', 'capacity' => 40, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('bcf_team_quotas');
    }
};
