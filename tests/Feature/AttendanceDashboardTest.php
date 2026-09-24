<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AttendanceDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');

        Schema::create('ukers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
        });
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uker_id');
            $table->string('nama');
        });
        Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->string('alasan');
            $table->timestamps();
        });

        $this->actingAs(new User(['name' => 'Admin']));
    }

    public function test_dashboard_renders_without_attendance(): void
    {
        $this->withSession(['sidebar_mode' => 'briefing'])
            ->get('/dashboard/absen')
            ->assertOk()
            ->assertViewIs('dashboard.index')
            ->assertViewHas('Persentase_absenmtd', 0)
            ->assertViewHas('Persentase_absenmtdkronis', 0)
            ->assertSee('Master Pegawai');
    }

    public function test_dashboard_receives_calculated_statistics(): void
    {
        DB::table('ukers')->insert(['id' => 1, 'nama' => 'Unit Test']);
        DB::table('pegawais')->insert(['id' => 1, 'uker_id' => 1, 'nama' => 'Pegawai Test']);
        for ($i = 0; $i < 3; $i++) {
            DB::table('absens')->insert([
                'pegawai_id' => 1,
                'alasan' => 'Sakit',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $this->get('/dashboard/absen')
            ->assertOk()
            ->assertViewHas('Absen_total', 3)
            ->assertViewHas('totalKronis', 1)
            ->assertViewHas('Persentase_absenmtd', 100)
            ->assertViewHas('Persentase_absenmtdkronis', 100)
            ->assertSee('Pegawai Test')
            ->assertSee('Unit Test');
    }
}
