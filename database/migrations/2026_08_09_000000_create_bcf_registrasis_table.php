<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bcf_registrasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('pn');
            $table->string('unit_kerja');
            $table->enum('warna', [
                'Ungu',
                'Hitam',
                'Biru Tua',
                'Biru Muda',
                'Putih',
                'Kuning',
                'Merah',
                'Hijau',
                'Orange'
            ])->default('Biru Muda');
            $table->integer('nourut')->default(0);
            $table->string('team')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bcf_registrasis');
    }
};
