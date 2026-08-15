<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hadiah_undi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_hadiah');
            $table->string('kategori')->nullable()->index();
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('stock_total')->default(1);
            $table->unsignedInteger('stock_sisa')->default(1);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hadiah_undi');
    }
};
