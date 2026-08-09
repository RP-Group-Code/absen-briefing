<?php

namespace App\Http\Controllers;

use App\Models\BcfRegistrasi;
use App\Models\Pegawai;
use App\Models\Uker;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class BcfRegistrasiController extends Controller
{
    public const WARNA_OPTIONS = [
        'Ungu',
        'Hitam',
        'Biru Tua',
        'Biru Muda',
        'Putih',
        'Kuning',
        'Merah',
        'Hijau',
        'Orange',
    ];

    public function index()
    {
        $registrasi = BcfRegistrasi::orderBy('nourut', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $pegawais = Pegawai::with('uker:id,nama,kode_uker')
            ->orderBy('nama', 'asc')
            ->get(['id', 'uker_id', 'nama', 'pn']);

        $ukers = Uker::orderBy('nama', 'asc')->get(['id', 'nama', 'kode_uker']);
        $warnaOptions = self::WARNA_OPTIONS;

        return view('bcf.registrasi', compact('registrasi', 'pegawais', 'ukers', 'warnaOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'pn' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:255',
            'warna' => ['required', Rule::in(self::WARNA_OPTIONS)],
            'nourut' => 'nullable|integer',
            'team' => 'nullable|string|max:100',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'pn.required' => 'PN wajib diisi.',
            'unit_kerja.required' => 'Unit kerja wajib dipilih.',
            'warna.required' => 'Warna wajib dipilih.',
            'warna.in' => 'Pilihan warna tidak valid.',
        ]);

        if (is_null($validated['nourut'])) {
            $validated['nourut'] = (BcfRegistrasi::max('nourut') ?? 0) + 1;
        }

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
            'unit_kerja' => 'required|string|max:255',
            'warna' => ['required', Rule::in(self::WARNA_OPTIONS)],
            'nourut' => 'nullable|integer',
            'team' => 'nullable|string|max:100',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'pn.required' => 'PN wajib diisi.',
            'unit_kerja.required' => 'Unit kerja wajib dipilih.',
            'warna.required' => 'Warna wajib dipilih.',
            'warna.in' => 'Pilihan warna tidak valid.',
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
