<?php

namespace App\Http\Controllers;

use App\Models\BcfRegistrasi;
use App\Models\Pegawai;
use App\Models\Uker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Contracts\Encryption\DecryptException;
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

    public const TEAM_OPTIONS = [
        ['nourut' => 1, 'team' => 'BRILIAN', 'warna' => 'Ungu', 'penanggung_jawab' => 'Oktareza', 'capacity' => 40],
        ['nourut' => 2, 'team' => 'BRISPOT', 'warna' => 'Hitam', 'penanggung_jawab' => 'Fadil', 'capacity' => 40],
        ['nourut' => 3, 'team' => 'BRISMART', 'warna' => 'Biru Tua', 'penanggung_jawab' => 'Andreas', 'capacity' => 40],
        ['nourut' => 4, 'team' => 'BRIMO', 'warna' => 'Biru Muda', 'penanggung_jawab' => 'David', 'capacity' => 40],
        ['nourut' => 5, 'team' => 'BRIMEN', 'warna' => 'Putih', 'penanggung_jawab' => 'Pak Budi', 'capacity' => 40],
        ['nourut' => 6, 'team' => 'BRICARE', 'warna' => 'Kuning', 'penanggung_jawab' => 'Wiliam', 'capacity' => 40],
        ['nourut' => 7, 'team' => 'BRIAPI', 'warna' => 'Merah', 'penanggung_jawab' => 'Pak Iwan', 'capacity' => 40],
        ['nourut' => 8, 'team' => 'BRIPEDIA', 'warna' => 'Hijau', 'penanggung_jawab' => 'Baim', 'capacity' => 40],
        ['nourut' => 9, 'team' => 'BRILINK', 'warna' => 'Orange', 'penanggung_jawab' => 'Febin', 'capacity' => 40],
    ];

    public function index()
    {
        $registrasi = BcfRegistrasi::orderBy('nourut', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $bcfWorkers = $this->bcfWorkersWithSystemData();

        $ukers = Uker::orderBy('nama', 'asc')->get(['id', 'nama', 'kode_uker']);
        $warnaOptions = self::WARNA_OPTIONS;
        $teamOptions = self::TEAM_OPTIONS;
        $nextTeam = $this->nextTeam();
        $nextNoUrut = (BcfRegistrasi::max('nourut') ?? 0) + 1;
        $registeredNames = $registrasi->pluck('nama')->all();
        $assignmentToken = $nextTeam
            ? Crypt::encryptString(json_encode(['team' => $nextTeam['team'], 'warna' => $nextTeam['warna']]))
            : null;

        return view('bcf.registrasi', compact('registrasi', 'ukers', 'warnaOptions', 'bcfWorkers', 'teamOptions', 'nextTeam', 'nextNoUrut', 'registeredNames', 'assignmentToken'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'assignment_token' => 'nullable|string',
        ], [
            'nama.required' => 'Nama wajib diisi.',
        ]);

        $worker = collect($this->bcfWorkersWithSystemData())
            ->first(fn (array $item) => $this->normalize($item['nama']) === $this->normalize($validated['nama']));

        if (!$worker) {
            $worker = [
                'nama' => trim($validated['nama']),
                'pn' => null,
                'uker' => 'Input Manual',
            ];
        }

        if (BcfRegistrasi::where('nama', $worker['nama'])->exists()) {
            throw ValidationException::withMessages(['nama' => 'Peserta ini sudah melakukan registrasi.']);
        }

        $team = $this->teamFromToken($validated['assignment_token'] ?? null);
        if (!$team || BcfRegistrasi::where('team', $team['team'])->count() >= $team['capacity']) {
            $team = $this->nextTeam();
        }
        if (!$team) {
            throw ValidationException::withMessages(['nama' => 'Kuota seluruh team BCF sudah penuh.']);
        }

        $nourut = (BcfRegistrasi::max('nourut') ?? 0) + 1;

        BcfRegistrasi::create([
            'nama' => $worker['nama'],
            'pn' => $worker['pn'] ?: 'Non PN',
            'unit_kerja' => $worker['uker'],
            'warna' => $team['warna'],
            'nourut' => $nourut,
            'team' => $team['team'],
        ]);

        return redirect()->route('bcf.registrasi.index')->with('bcf_assignment', [
            'nama' => $worker['nama'],
            'nourut' => $nourut,
            'warna' => $team['warna'],
            'team' => $team['team'],
            'penanggung_jawab' => $team['penanggung_jawab'],
        ]);
    }

    private function nextTeam(): ?array
    {
        $availableTeams = [];

        foreach (self::TEAM_OPTIONS as $team) {
            if (BcfRegistrasi::where('team', $team['team'])->count() < $team['capacity']) {
                $availableTeams[] = $team;
            }
        }

        return $availableTeams === [] ? null : $availableTeams[array_rand($availableTeams)];
    }

    private function teamFromToken(?string $token): ?array
    {
        if (blank($token)) {
            return null;
        }

        try {
            $payload = json_decode(Crypt::decryptString($token), true, 512, JSON_THROW_ON_ERROR);
        } catch (DecryptException|\JsonException) {
            return null;
        }

        return collect(self::TEAM_OPTIONS)->first(function (array $team) use ($payload) {
            return $team['team'] === ($payload['team'] ?? null)
                && $team['warna'] === ($payload['warna'] ?? null);
        });
    }

    private function bcfWorkers(): array
    {
        $path = database_path('seeders/data/bcf_workers_2026.csv');
        if (!is_readable($path)) {
            return [];
        }

        $handle = fopen($path, 'rb');
        $headers = fgetcsv($handle);
        $workers = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < count($headers) || trim($row[0]) === '') {
                continue;
            }

            $workers[] = [
                'nama' => trim($row[0]),
                'jabatan' => trim($row[1] ?? ''),
                'uker' => trim($row[2] ?? ''),
                'ukuran' => trim($row[3] ?? ''),
                'gender' => trim($row[4] ?? ''),
            ];
        }

        fclose($handle);

        return $workers;
    }

    private function bcfWorkersWithSystemData(): array
    {
        $employees = Pegawai::query()
            ->get(['nama', 'pn', 'jabatan'])
            ->groupBy(fn (Pegawai $pegawai) => $this->normalize($pegawai->nama));

        return collect($this->bcfWorkers())->map(function (array $worker) use ($employees) {
            $candidates = $employees->get($this->normalize($worker['nama']), collect());
            $worker['pn'] = null;
            $worker['pegawai_jabatan'] = null;

            $employee = $candidates->first(function (Pegawai $pegawai) use ($worker) {
                return $this->normalize($pegawai->jabatan) === $this->normalize($worker['jabatan']);
            }) ?? $candidates->first(fn (Pegawai $pegawai) => blank($pegawai->jabatan));

            if ($employee) {
                $worker['pn'] = $employee->pn;
                $worker['pegawai_jabatan'] = $employee->jabatan;
            }

            return $worker;
        })->all();
    }

    private function normalize(?string $value): string
    {
        return Str::lower(Str::squish((string) $value));
    }

    public function admin()
    {
        $registrasi = BcfRegistrasi::orderBy('nourut', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $teamSummary = collect(self::TEAM_OPTIONS)->map(function (array $team) use ($registrasi) {
            $used = $registrasi->where('team', $team['team'])->count();

            return array_merge($team, [
                'used' => $used,
                'remaining' => max(0, $team['capacity'] - $used),
            ]);
        });

        return view('bcf.admin', compact('registrasi', 'teamSummary'));
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
            'team' => ['required', Rule::in(collect(self::TEAM_OPTIONS)->pluck('team')->all())],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'pn.required' => 'PN wajib diisi.',
            'unit_kerja.required' => 'Unit kerja wajib dipilih.',
            'warna.required' => 'Warna wajib dipilih.',
            'warna.in' => 'Pilihan warna tidak valid.',
            'team.required' => 'Team wajib dipilih.',
            'team.in' => 'Team tidak valid.',
        ]);

        $team = collect(self::TEAM_OPTIONS)->firstWhere('team', $validated['team']);
        if ($team['warna'] !== $validated['warna']) {
            throw ValidationException::withMessages(['warna' => "Warna untuk {$team['team']} harus {$team['warna']}."]);
        }

        $teamUsed = BcfRegistrasi::where('team', $team['team'])
            ->where('id', '!=', $bcf->id)
            ->count();
        if ($teamUsed >= $team['capacity']) {
            throw ValidationException::withMessages(['team' => "Kuota team {$team['team']} sudah penuh."]);
        }

        $bcf->update($validated);

        Alert::success('Berhasil Diperbarui', 'Data registrasi BCF berhasil diperbarui.');

        return redirect()->route('bcf.registrasi.admin');
    }

    public function destroy($id)
    {
        $bcf = BcfRegistrasi::findOrFail($id);
        $bcf->delete();

        Alert::success('Berhasil Dihapus', 'Data registrasi BCF berhasil dihapus.');

        return redirect()->route('bcf.registrasi.admin');
    }
}
