<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('peserta_undi') || Schema::hasColumn('peserta_undi', 'jabatan')) {
            return;
        }

        Schema::table('peserta_undi', function (Blueprint $table) {
            $table->string('jabatan', 144)->nullable()->after('unit_kerja');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('peserta_undi') || ! Schema::hasColumn('peserta_undi', 'jabatan')) {
            return;
        }

        Schema::table('peserta_undi', function (Blueprint $table) {
            $table->dropColumn('jabatan');
        });
    }
};
