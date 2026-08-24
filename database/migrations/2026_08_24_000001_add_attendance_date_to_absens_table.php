<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absens', function (Blueprint $table) {
            $table->date('attendance_date')->nullable()->after('alasan');
        });

        $claimedDates = [];

        DB::table('absens')
            ->select(['id', 'pegawai_id', 'created_at'])
            ->whereNotNull('created_at')
            ->orderBy('id')
            ->chunkById(500, function ($rows) use (&$claimedDates) {
                foreach ($rows as $row) {
                    $date = Carbon::parse($row->created_at, config('app.timezone'))->toDateString();
                    $key = $row->pegawai_id . '|' . $date;

                    if (isset($claimedDates[$key])) {
                        continue;
                    }

                    DB::table('absens')
                        ->where('id', $row->id)
                        ->update(['attendance_date' => $date]);

                    $claimedDates[$key] = true;
                }
            });

        Schema::table('absens', function (Blueprint $table) {
            $table->unique(['pegawai_id', 'attendance_date'], 'absens_pegawai_date_unique');
        });
    }

    public function down(): void
    {
        Schema::table('absens', function (Blueprint $table) {
            $table->dropUnique('absens_pegawai_date_unique');
            $table->dropColumn('attendance_date');
        });
    }
};
