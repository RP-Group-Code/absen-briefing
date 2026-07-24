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
        Schema::create('kanca', function (Blueprint $table) {
            $table->id();
            $table->string('division')->index();
            $table->string('name')->index();
            $table->string('jabatan')->nullable();
            $table->timestamps();

            $table->unique(['division', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kanca');
    }
};
