<?php

namespace App\Http\Controllers;

use App\Models\BcfRegistrasi;
use App\Models\BcfTeamQuota;
use App\Models\Pegawai;
use App\Models\Uker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Contracts\Encryption\DecryptException;
use RealRashid\SweetAlert\Facades\Alert;

class BcfRegistrasiController extends Controller
{
    private const PER_PAGE_OPTIONS = [10, 20, 50];

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

    public function index(Request $request)
    {
        $perPage = $this->resolvePerPage($request);

        $registrasi = BcfRegistrasi::orderBy('nourut', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'peserta_page')
            ->withQueryString();

        $totalRegistrasi = BcfRegistrasi::count();
        $totalUnitKerja = BcfRegistrasi::distinct('unit_kerja')->count('unit_kerja');
        $latestNoUrut = BcfRegistrasi::max('nourut') ?? 0;

        $bcfWorkers = $this->bcfWorkersWithSystemData();

        $ukers = Uker::orderBy('nama', 'asc')->get(['id', 'nama', 'kode_uker']);
        $warnaOptions = self::WARNA_OPTIONS;
        $teamOptions = self::TEAM_OPTIONS;
        $nextTeam = $this->nextTeam();
        $nextNoUrut = $latestNoUrut + 1;
        $registeredNames = BcfRegistrasi::pluck('nama')->all();
        $assignmentToken = $nextTeam
            ? Crypt::encryptString(json_encode(['team' => $nextTeam['team'], 'warna' => $nextTeam['warna']]))
            : null;

        $perPageOptions = self::PER_PAGE_OPTIONS;

        return view('bcf.registrasi', compact('registrasi', 'ukers', 'warnaOptions', 'bcfWorkers', 'teamOptions', 'nextTeam', 'nextNoUrut', 'registeredNames', 'assignmentToken', 'totalRegistrasi', 'totalUnitKerja', 'latestNoUrut', 'perPage', 'perPageOptions'));
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

        $assignment = DB::transaction(function () use ($validated, $worker) {
            // Lock all quota rows so concurrent registrations cannot claim the same last slot.
            $this->lockQuotaRows();

            if (BcfRegistrasi::where('nama', $worker['nama'])->exists()) {
                throw ValidationException::withMessages(['nama' => 'Peserta ini sudah melakukan registrasi.']);
            }

            $capacities = BcfTeamQuota::query()->pluck('capacity', 'team');
            $team = $this->teamFromToken($validated['assignment_token'] ?? null);
            $teamCapacity = $capacities->get($team['team'] ?? '') ?? ($team['capacity'] ?? 0);

            if (!$team || BcfRegistrasi::where('team', $team['team'])->count() >= $teamCapacity) {
                $team = $this->nextTeam($capacities);
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

            return compact('nourut', 'team') + ['nama' => $worker['nama']];
        });

        return redirect()->route('bcf.registrasi.index')->with('bcf_assignment', [
            'nama' => $assignment['nama'],
            'nourut' => $assignment['nourut'],
            'warna' => $assignment['team']['warna'],
            'team' => $assignment['team']['team'],
            'penanggung_jawab' => $assignment['team']['penanggung_jawab'],
        ]);
    }

    private function lockQuotaRows(): void
    {
        BcfTeamQuota::query()->orderBy('id')->lockForUpdate()->get();
    }

    private function nextTeam($capacities = null): ?array
    {
        $capacities ??= BcfTeamQuota::query()->pluck('capacity', 'team');
        $availableTeams = [];

        foreach (self::TEAM_OPTIONS as $team) {
            $capacity = $capacities->get($team['team']) ?? $team['capacity'];
            if (BcfRegistrasi::where('team', $team['team'])->count() < $capacity) {
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

    public function admin(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $perPage = $this->resolvePerPage($request);
        $allRegistrasi = BcfRegistrasi::get(['team']);

        $registrasi = BcfRegistrasi::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $like = '%' . $search . '%';
                    $query->where('nama', 'like', $like)
                        ->orWhere('pn', 'like', $like)
                        ->orWhere('team', 'like', $like)
                        ->orWhere('warna', 'like', $like)
                        ->orWhere('unit_kerja', 'like', $like)
                        ->orWhere('nourut', 'like', $like);
                });
            })
            ->orderBy('nourut', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $capacities = BcfTeamQuota::query()->pluck('capacity', 'team');
        $teamSummary = collect(self::TEAM_OPTIONS)->map(function (array $team) use ($allRegistrasi, $capacities) {
            $used = $allRegistrasi->where('team', $team['team'])->count();
            $capacity = $capacities->get($team['team']) ?? $team['capacity'];

            return array_merge($team, [
                'used' => $used,
                'capacity' => $capacity,
                'remaining' => max(0, $capacity - $used),
            ]);
        });

        $perPageOptions = self::PER_PAGE_OPTIONS;

        return view('bcf.admin', compact('registrasi', 'teamSummary', 'search', 'perPage', 'perPageOptions'));
    }

    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->input('per_page', self::PER_PAGE_OPTIONS[0]);

        return in_array($perPage, self::PER_PAGE_OPTIONS, true)
            ? $perPage
            : self::PER_PAGE_OPTIONS[0];
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

        DB::transaction(function () use ($bcf, $validated, $team) {
            // Keep admin edits in the same quota lock as public registrations.
            $this->lockQuotaRows();

            $capacity = BcfTeamQuota::query()
                ->where('team', $team['team'])
                ->value('capacity') ?? $team['capacity'];
            $teamUsed = BcfRegistrasi::where('team', $team['team'])
                ->where('id', '!=', $bcf->id)
                ->count();
            if ($teamUsed >= $capacity) {
                throw ValidationException::withMessages(['team' => "Kuota team {$team['team']} sudah penuh."]);
            }

            $bcf->update($validated);
        });

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
