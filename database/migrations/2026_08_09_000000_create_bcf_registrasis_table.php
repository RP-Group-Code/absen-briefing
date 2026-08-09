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
            $table->string('unit_kerja')->nullable();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('Pending');
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
