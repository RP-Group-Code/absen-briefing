<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_undian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_undi_id')->unique()->constrained('peserta_undi')->cascadeOnDelete();
            $table->foreignId('hadiah_undi_id')->constrained('hadiah_undi')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_undian');
    }
};
