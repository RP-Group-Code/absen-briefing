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
        Schema::create('kanca_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kanca_id')
                ->constrained('kanca')
                ->cascadeOnDelete();
            $table->date('attendance_date')->index();
            $table->enum('status', ['Masuk', 'Telat', 'Sakit', 'Absen', 'Izin', 'Cuti'])
                ->default('Masuk');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['kanca_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kanca_attendances');
    }
};
