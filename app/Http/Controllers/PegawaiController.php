<?php

namespace App\Http\Controllers;

use App\Models\Kanca;
use App\Models\Uker;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class PegawaiController extends Controller
{
    public function index()
    {
        DB::statement("SET SESSION sql_mode=''");

        $data['pegawai'] = Pegawai::select('pegawais.*')
            ->with('uker:id,nama,kode_uker')
            ->leftJoin('absens', function ($join) {
                $join->on('absens.pegawai_id', '=', 'pegawais.id')
                    ->whereMonth('absens.created_at', date('m'));
            })
            ->leftJoin('ukers', 'ukers.id', '=', 'pegawais.uker_id')
            ->addSelect(DB::raw('COUNT(absens.id) as jumlah_absen'))
            ->groupBy('pegawais.id')
            ->orderByRaw('COUNT(absens.id) DESC, ukers.nama ASC')
            ->get();

        $data['ukers'] = Uker::orderBy('nama')->get(['id', 'nama', 'kode_uker']);

        return view('pegawai.index', $data);
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate(
            [
                'uker_id' => ['required', 'exists:ukers,id'],
                'pn' => ['required', 'string', 'max:100', 'unique:pegawais,pn,' . $pegawai->id],
                'nama' => ['required', 'string', 'max:255'],
                'jabatan' => ['nullable', 'string', 'max:255'],
            ],
            [
                'uker_id.required' => 'Unit kerja wajib dipilih.',
                'uker_id.exists' => 'Unit kerja tidak valid.',
                'pn.required' => 'PN wajib diisi.',
                'pn.unique' => 'PN sudah digunakan pegawai lain.',
                'nama.required' => 'Nama wajib diisi.',
            ]
        );

        $pegawai->update($validated);

        Alert::success('Berhasil Diedit', "Data pegawai {$pegawai->nama} berhasil diperbarui.");

        return redirect()->route('pegawai.dashboard');
    }

    public function destroy(Pegawai $pegawai)
    {
        $namaPegawai = $pegawai->nama;
        $pegawai->delete();

        Alert::success('Berhasil Dihapus', "Data pegawai {$namaPegawai} berhasil dihapus.");

        return redirect()->route('pegawai.dashboard');
    }

    public function indexKanca()
    {
        $divisions = $this->kancaDivisionOptions();
        $jabatans = $this->kancaJabatanOptions();

        $kanca = Kanca::query()
            ->orderBy('division')
            ->orderBy('name')
            ->get(['id', 'division', 'jabatan', 'name', 'created_at']);

        return view('pegawai.kanca', [
            'kanca' => $kanca,
            'divisionOptions' => $divisions,
            'jabatanOptions' => $jabatans,
        ]);
    }

    public function storeKanca(Request $request)
    {
        if ($request->has('jabatan')) {
            $request->merge(['jabatan' => strtoupper($request->input('jabatan'))]);
        }

        $divisionRules = ['required', 'string', 'max:120'];
        $jabatanRules = ['required', 'string', 'max:190'];

        $divisionOptions = $this->kancaDivisionOptions();
        if ($divisionOptions !== []) {
            $divisionRules[] = Rule::in($divisionOptions);
        }

        $validated = $request->validate(
            [
                'form_mode' => ['nullable', 'string'],
                'division' => $divisionRules,
                'jabatan' => $jabatanRules,
                'name' => [
                    'required',
                    'string',
                    'max:190',
                    Rule::unique('kanca', 'name')->where(function ($query) use ($request) {
                        return $query->where('division', (string) $request->input('division'));
                    }),
                ],
            ],
            [
                'division.required' => 'Divisi wajib dipilih.',
                'division.in' => 'Divisi tidak valid.',
                'jabatan.required' => 'Jabatan wajib diisi.',
                'name.required' => 'Nama pegawai wajib diisi.',
                'name.unique' => 'Nama pegawai untuk divisi tersebut sudah ada.',
            ]
        );

        Kanca::query()->create([
            'division' => $validated['division'],
            'jabatan' => $validated['jabatan'],
            'name' => $validated['name'],
        ]);

        Alert::success('Berhasil Ditambah', "Data pegawai {$validated['name']} berhasil ditambahkan.");

        return redirect()->route('pegawai.kanca.index');
    }

    public function updateKanca(Request $request, Kanca $kanca)
    {
        if ($request->has('jabatan')) {
            $request->merge(['jabatan' => strtoupper($request->input('jabatan'))]);
        }

        $divisionRules = ['required', 'string', 'max:120'];
        $jabatanRules = ['required', 'string', 'max:190'];

        $divisionOptions = $this->kancaDivisionOptions();
        if ($divisionOptions !== []) {
            $divisionRules[] = Rule::in($divisionOptions);
        }

        $validated = $request->validate(
            [
                'form_mode' => ['nullable', 'string'],
                'edit_kanca_id' => ['nullable', 'integer'],
                'division' => $divisionRules,
                'jabatan' => $jabatanRules,
                'name' => [
                    'required',
                    'string',
                    'max:190',
                    Rule::unique('kanca', 'name')
                        ->ignore($kanca->id)
                        ->where(function ($query) use ($request) {
                            return $query->where('division', (string) $request->input('division'));
                        }),
                ],
            ],
            [
                'division.required' => 'Divisi wajib dipilih.',
                'division.in' => 'Divisi tidak valid.',
                'jabatan.required' => 'Jabatan wajib diisi.',
                'name.required' => 'Nama pegawai wajib diisi.',
                'name.unique' => 'Nama pegawai untuk divisi tersebut sudah ada.',
            ]
        );

        $kanca->update([
            'division' => $validated['division'],
            'jabatan' => $validated['jabatan'],
            'name' => $validated['name'],
        ]);

        Alert::success('Berhasil Diedit', "Data pegawai {$validated['name']} berhasil diperbarui.");

        return redirect()->route('pegawai.kanca.index');
    }

    public function destroyKanca(Kanca $kanca)
    {
        $name = $kanca->name;
        $kanca->delete();

        Alert::success('Berhasil Dihapus', "Data pegawai {$name} berhasil dihapus.");

        return redirect()->route('pegawai.kanca.index');
    }

    /**
     * @return array<int, string>
     */
    private function kancaDivisionOptions(): array
    {
        return Kanca::query()
            ->select('division')
            ->whereNotNull('division')
            ->where('division', '!=', '')
            ->distinct()
            ->orderBy('division')
            ->pluck('division')
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function kancaJabatanOptions(): array
    {
        return Kanca::query()
            ->select('jabatan')
            ->whereNotNull('jabatan')
            ->where('jabatan', '!=', '')
            ->distinct()
            ->orderBy('jabatan')
            ->pluck('jabatan')
            ->values()
            ->all();
    }
}
