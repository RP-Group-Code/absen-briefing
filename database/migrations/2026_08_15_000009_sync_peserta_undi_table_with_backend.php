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

        if (! Schema::hasColumn('peserta_undi', 'jabatan')) {
            Schema::table('peserta_undi', function (Blueprint $table) {
                $table->string('jabatan', 144)->nullable()->after('unit_kerja');
            });
        }

        if (Schema::hasColumn('peserta_undi', 'is_active')) {
            DB::statement("UPDATE peserta_undi SET status = CASE WHEN is_active = 1 THEN 'Belum Menang' ELSE 'Menang' END WHERE status IS NULL OR status = ''");
        } else {
            DB::statement("UPDATE peserta_undi SET status = CASE WHEN LOWER(COALESCE(status, '')) IN ('aktif', 'active', 'belum menang', 'belummenang', '1', 'true', '') THEN 'Belum Menang' WHEN LOWER(COALESCE(status, '')) IN ('menang', 'winner', 'won') THEN 'Menang' ELSE status END");
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

        if (Schema::hasColumn('peserta_undi', 'jabatan')) {
            Schema::table('peserta_undi', function (Blueprint $table) {
                $table->dropColumn('jabatan');
            });
        }

        if (! Schema::hasColumn('peserta_undi', 'is_active')) {
            Schema::table('peserta_undi', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('keterangan');
            });
        }

        DB::statement("UPDATE peserta_undi SET is_active = CASE WHEN LOWER(status) = 'menang' THEN 0 ELSE 1 END");

        if (Schema::hasColumn('peserta_undi', 'status')) {
            Schema::table('peserta_undi', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
