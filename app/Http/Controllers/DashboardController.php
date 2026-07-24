<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $absenNow  = Absen::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->where('alasan', '!=', 'Cuti')
            ->count();
// dd($absenNow);
        $absenLast = Absen::whereMonth('created_at', date('m', strtotime('first day of last month')))
            ->whereYear('created_at',  date('Y'))
            ->where('alasan', '!=', 'Cuti')
            ->count();

        $absenNowKronis = Absen::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->where('alasan', '!=', 'Cuti')
            ->groupBy('pegawai_id')
            ->having(DB::raw('COUNT(pegawai_id)'), '>=', 3)
            ->select('pegawai_id', DB::raw('COUNT(pegawai_id) as total'))
            ->get();
        $data['totalKronis'] = $absenNowKronis->count();

        $absenLastKronis = Absen::whereMonth('created_at', date('m', strtotime('first day of last month')))
            ->whereYear('created_at', date('Y'))
            ->where('alasan', '!=', 'Cuti')
            ->groupBy('pegawai_id')
            ->having(DB::raw('COUNT(pegawai_id)'), '>=', 3)
            ->select('pegawai_id', DB::raw('COUNT(pegawai_id) as total'))
            ->get();
        $data['totalLastKronis'] = $absenLastKronis->count();

        $data['Absen_total']    = $absenNow;
        $data['Absen_totalmtd'] = $absenLast;

        $data['Persentase_absenmtd'] = $absenLast > 0
            ? round((($absenNow - $absenLast) / $absenLast) * 100, 1)
            : ($absenNow > 0 ? 100 : 0);
        $data['Persentase_absenmtdkronis'] = $absenLastKronis->count() > 0
            ? round((($absenNowKronis->count() - $absenLastKronis->count()) / $absenLastKronis->count()) * 100, 1)
            : ($absenNowKronis->count() > 0 ? 100 : 0);

        $bulanIni  = date('m');

        $tahunIni  = date('Y');
        $bulanLalu = date('m', strtotime('first day of last month'));
        $tahunLalu = date('Y', strtotime('first day of last month'));

        $data['DashboardAbsenNow'] = Absen::join('pegawais', 'pegawais.id', '=', 'absens.pegawai_id')
            ->join('ukers', 'ukers.id', '=', 'pegawais.uker_id')
            ->whereYear('absens.created_at', $tahunIni)
            ->whereMonth('absens.created_at', $bulanIni)
            ->where('alasan', '!=', 'Cuti')
            ->groupBy('ukers.nama')
            ->select(DB::raw('ukers.nama as nama_uker, COUNT(ukers.nama) AS Jumlah'))
            ->get()
            ->keyBy('nama_uker'); // index by nama_uker

        $data['DashboardAbsenLast'] = Absen::join('pegawais', 'pegawais.id', '=', 'absens.pegawai_id')
            ->join('ukers', 'ukers.id', '=', 'pegawais.uker_id')
            // ->whereYear('absens.created_at', $tahunLalu)
            ->whereMonth('absens.created_at', $bulanLalu)
            ->where('alasan', '!=', 'Cuti')
            ->groupBy('ukers.nama')
            ->select(DB::raw('ukers.nama as nama_uker, COUNT(ukers.nama) AS Jumlah'))
            ->get()
            ->keyBy('nama_uker'); // index by nama_uker

        // Gabungkan semua nama uker dari kedua bulan
        $data['ukerList'] = $data['DashboardAbsenNow']
            ->keys()
            ->merge($data['DashboardAbsenLast']->keys())
            ->unique()
            ->sort()
            ->values();


        $data['absenKronisNow'] = Absen::whereMonth('absens.created_at', $bulanIni)
            ->whereYear('absens.created_at', $tahunIni)
            ->where('alasan', '!=', 'Cuti')
            ->join('pegawais', 'pegawais.id', '=', 'absens.pegawai_id')
            ->join('ukers', 'ukers.id', '=', 'pegawais.uker_id')
            ->groupBy('absens.pegawai_id', 'pegawais.nama', 'ukers.nama')
            ->having(DB::raw('COUNT(absens.pegawai_id)'), '>=', 3)  // ← ganti > jadi >=
            ->select(
                'absens.pegawai_id',
                'pegawais.nama as nama_pegawai',
                'ukers.nama as nama_uker',
                DB::raw('COUNT(absens.pegawai_id) as total_now')
            )
            ->get()
            ->keyBy('pegawai_id');

        $data['absenKronisLast'] = Absen::whereMonth('absens.created_at', $bulanLalu)
            ->whereYear('absens.created_at', $tahunIni)  
            ->where('alasan', '!=', 'Cuti')
            ->join('pegawais', 'pegawais.id', '=', 'absens.pegawai_id')
            ->join('ukers', 'ukers.id', '=', 'pegawais.uker_id')
            ->groupBy('absens.pegawai_id', 'pegawais.nama', 'ukers.nama')
            ->having(DB::raw('COUNT(absens.pegawai_id)'), '>=', 3)
            ->select(
                'absens.pegawai_id',
                'pegawais.nama as nama_pegawai',
                'ukers.nama as nama_uker',        
                DB::raw('COUNT(absens.pegawai_id) as total_last')
            )
            ->get()
            ->keyBy('pegawai_id');

        $data['kronisIds'] = $data['absenKronisNow']
            ->keys()
            ->merge($data['absenKronisLast']->keys())
            ->unique()
            ->values();

        return view("dashboard.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
