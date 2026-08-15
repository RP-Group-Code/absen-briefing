<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_undi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('pn')->nullable()->index();
            $table->string('unit_kerja')->nullable();
            $table->string('jabatan', 144)->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_undi');
    }
};
