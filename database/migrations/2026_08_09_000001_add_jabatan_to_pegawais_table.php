<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pegawais', 'jabatan')) {
            Schema::table('pegawais', function (Blueprint $table) {
                $table->string('jabatan')->nullable()->after('nama');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pegawais', 'jabatan')) {
            Schema::table('pegawais', function (Blueprint $table) {
                $table->dropColumn('jabatan');
            });
        }
    }
};
