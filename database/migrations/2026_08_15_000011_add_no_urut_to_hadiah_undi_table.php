<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hadiah_undi', function (Blueprint $table) {
            if (! Schema::hasColumn('hadiah_undi', 'no_urut')) {
                $table->unsignedInteger('no_urut')->nullable()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hadiah_undi', function (Blueprint $table) {
            if (Schema::hasColumn('hadiah_undi', 'no_urut')) {
                $table->dropColumn('no_urut');
            }
        });
    }
};
