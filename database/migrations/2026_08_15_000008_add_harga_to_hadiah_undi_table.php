<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hadiah_undi', function (Blueprint $table) {
            $table->unsignedBigInteger('harga')->nullable()->after('stock_sisa');
        });
    }

    public function down(): void
    {
        Schema::table('hadiah_undi', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }
};
