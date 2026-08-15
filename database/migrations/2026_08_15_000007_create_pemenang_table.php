<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemenang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_undi_id')->constrained('peserta_undi')->cascadeOnDelete();
            $table->foreignId('hadiah_undi_id')->constrained('hadiah_undi')->cascadeOnDelete();
            $table->unsignedInteger('undian_ke');
            $table->text('catatan')->nullable();
            $table->timestamp('won_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemenang');
    }
};
