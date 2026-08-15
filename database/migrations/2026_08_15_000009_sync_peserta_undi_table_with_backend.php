<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('peserta_undi')) {
            return;
        }

        if (! Schema::hasColumn('peserta_undi', 'status')) {
            Schema::table('peserta_undi', function (Blueprint $table) {
                $table->string('status')->nullable()->after('unit_kerja');
            });
        }

        if (Schema::hasColumn('peserta_undi', 'is_active')) {
            DB::statement("UPDATE peserta_undi SET status = CASE WHEN is_active = 1 THEN 'Aktif' ELSE 'Nonaktif' END WHERE status IS NULL OR status = ''");
        } else {
            DB::statement("UPDATE peserta_undi SET status = 'Aktif' WHERE status IS NULL OR status = ''");
        }

        DB::statement("ALTER TABLE peserta_undi MODIFY status VARCHAR(255) NOT NULL");

        if (Schema::hasColumn('peserta_undi', 'keterangan')) {
            Schema::table('peserta_undi', function (Blueprint $table) {
                $table->dropColumn('keterangan');
            });
        }

        if (Schema::hasColumn('peserta_undi', 'is_active')) {
            Schema::table('peserta_undi', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('peserta_undi')) {
            return;
        }

        if (! Schema::hasColumn('peserta_undi', 'keterangan')) {
            Schema::table('peserta_undi', function (Blueprint $table) {
                $table->text('keterangan')->nullable()->after('unit_kerja');
            });
        }

        if (! Schema::hasColumn('peserta_undi', 'is_active')) {
            Schema::table('peserta_undi', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('keterangan');
            });
        }

        DB::statement("UPDATE peserta_undi SET is_active = CASE WHEN LOWER(status) = 'nonaktif' THEN 0 ELSE 1 END");

        if (Schema::hasColumn('peserta_undi', 'status')) {
            Schema::table('peserta_undi', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
