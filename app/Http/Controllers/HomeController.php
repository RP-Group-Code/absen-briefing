<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Dashboard General — Portal utama setelah login
     */
    public function index()
    {
        return view('home.index');
    }

    /**
     * Masuk ke mode BCF: set session lalu redirect ke halaman BCF Undian
     */
    public function bcf(Request $request)
    {
        $request->session()->put('sidebar_mode', 'bcf');
        return redirect()->route('bcf.undian.index');
    }

    /**
     * Masuk ke mode Briefing: set session lalu redirect ke dashboard
     */
    public function briefing(Request $request)
    {
        $request->session()->put('sidebar_mode', 'briefing');
        return redirect()->route('dashboard');
    }
}
