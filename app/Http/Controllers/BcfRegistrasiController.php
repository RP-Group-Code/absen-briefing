<?php

namespace App\Http\Controllers;

use App\Models\BcfRegistrasi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BcfRegistrasiController extends Controller
{
    public function index()
    {
        $registrasi = BcfRegistrasi::orderBy('created_at', 'desc')->get();

        return view('bcf.registrasi', compact('registrasi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'pn' => 'required|string|max:100',
            'unit_kerja' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'status' => 'required|string|max:50',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'pn.required' => 'PN wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        BcfRegistrasi::create($validated);

        Alert::success('Berhasil Tambah Data', 'Data registrasi BCF berhasil disimpan.');

        return redirect()->route('bcf.registrasi.index');
    }

    public function update(Request $request, $id)
    {
        $bcf = BcfRegistrasi::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'pn' => 'required|string|max:100',
            'unit_kerja' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'status' => 'required|string|max:50',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'pn.required' => 'PN wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $bcf->update($validated);

        Alert::success('Berhasil Diperbarui', 'Data registrasi BCF berhasil diperbarui.');

        return redirect()->route('bcf.registrasi.index');
    }

    public function destroy($id)
    {
        $bcf = BcfRegistrasi::findOrFail($id);
        $bcf->delete();

        Alert::success('Berhasil Dihapus', 'Data registrasi BCF berhasil dihapus.');

        return redirect()->route('bcf.registrasi.index');
    }
}
