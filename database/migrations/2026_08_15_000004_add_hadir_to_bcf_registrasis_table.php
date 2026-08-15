<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bcf_registrasis', function (Blueprint $table) {
            $table->boolean('hadir')->default(false)->after('team');
        });
    }

    public function down(): void
    {
        Schema::table('bcf_registrasis', function (Blueprint $table) {
            $table->dropColumn('hadir');
        });
    }
};
