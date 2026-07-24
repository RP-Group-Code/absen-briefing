<?php

namespace App\Http\Controllers;

use App\Exports\KancaAttendanceExport;
use App\Models\Absen;
use App\Models\InputAbsen;
use App\Models\Kanca;
use App\Models\KancaAttendance;
use App\Models\Pegawai;
use App\Models\Uker;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class InputAbsenController extends Controller
{
    private const KANCA_STATUSES = ['Masuk', 'Telat', 'Sakit', 'Absen', 'Izin', 'Cuti'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['uker'] = Uker::all();
        $data['pegawai'] = Pegawai::all();

        return view('absen.index', $data);
    }

    public function indexKanca(Request $request)
    {
        $isAjax = $request->boolean('ajax') || $request->expectsJson();

        $activeDate = $request->query('date', now()->toDateString());
        try {
            $activeDate = Carbon::parse($activeDate)->toDateString();
        } catch (\Throwable $th) {
            $activeDate = now()->toDateString();
        }

        $division = trim((string) $request->query('division', ''));
        $search = trim((string) $request->query('search', ''));

        $exportStartDate = $request->query('start_date', $activeDate);
        try {
            $exportStartDate = Carbon::parse($exportStartDate)->toDateString();
        } catch (\Throwable $th) {
            $exportStartDate = $activeDate;
        }

        $exportEndDate = $request->query('end_date', $activeDate);
        try {
            $exportEndDate = Carbon::parse($exportEndDate)->toDateString();
        } catch (\Throwable $th) {
            $exportEndDate = $activeDate;
        }

        if ($exportStartDate > $exportEndDate) {
            [$exportStartDate, $exportEndDate] = [$exportEndDate, $exportStartDate];
        }

        if (!Schema::hasTable('kanca')) {
            $emptyPayload = [
                'rows' => collect(),
                'divisions' => collect(),
                'activeDate' => $activeDate,
                'prevDate' => Carbon::parse($activeDate)->subDay()->toDateString(),
                'nextDate' => Carbon::parse($activeDate)->addDay()->toDateString(),
                'activeDivision' => $division,
                'search' => $search,
                'exportStartDate' => $exportStartDate,
                'exportEndDate' => $exportEndDate,
                'statuses' => self::KANCA_STATUSES,
                'summary' => array_fill_keys(self::KANCA_STATUSES, 0),
                'totalEmployees' => 0,
                'selectedRow' => null,
            ];

            if ($isAjax) {
                return response()->json([
                    'rows' => [],
                    'summary' => $emptyPayload['summary'],
                    'totalEmployees' => 0,
                    'activeDivision' => $division,
                    'activeDate' => $activeDate,
                    'selectedRow' => null,
                ]);
            }

            return view('absen.kanca', $emptyPayload);
        }

        $divisions = Kanca::query()
            ->select('division')
            ->distinct()
            ->orderBy('division')
            ->pluck('division');

        $employeesQuery = Kanca::query()
            ->orderBy('division')
            ->orderBy('name');

        if ($division !== '') {
            $employeesQuery->where('division', $division);
        }

        if ($search !== '') {
            $employeesQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('division', 'like', "%{$search}%");
            });
        }

        $employees = $employeesQuery->get(['id', 'division', 'name', 'jabatan']);
        $employeeIds = $employees->pluck('id');

        $attendanceMap = collect();
        if (Schema::hasTable('kanca_attendances')) {
            $attendanceMap = KancaAttendance::query()
                ->whereDate('attendance_date', $activeDate)
                ->whereIn('kanca_id', $employeeIds)
                ->get()
                ->keyBy('kanca_id');
        }

        $rows = $employees->map(function (Kanca $employee) use ($attendanceMap) {
            $attendance = $attendanceMap->get($employee->id);
            $status = $attendance?->status ?? 'Masuk';

            return [
                'id' => $employee->id,
                'division' => $employee->division,
                'jabatan' => $employee->jabatan ?? '-',
                'name' => $employee->name,
                'status' => $status,
                'indicator' => strtoupper(substr($status, 0, 1)),
                'notes' => $attendance?->notes ?? '',
            ];
        });

        $summary = [];
        foreach (self::KANCA_STATUSES as $status) {
            $summary[$status] = $rows->where('status', $status)->count();
        }

        $selectedId = (int) $request->query('selected', 0);
        $selectedRow = $rows->firstWhere('id', $selectedId);
        if (!$selectedRow && $rows->isNotEmpty()) {
            $selectedRow = $rows->first();
        }

        $prevDate = Carbon::parse($activeDate)->subDay()->toDateString();
        $nextDate = Carbon::parse($activeDate)->addDay()->toDateString();

        $payload = [
            'rows' => $rows,
            'divisions' => $divisions,
            'activeDate' => $activeDate,
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
            'activeDivision' => $division,
            'search' => $search,
            'exportStartDate' => $exportStartDate,
            'exportEndDate' => $exportEndDate,
            'statuses' => self::KANCA_STATUSES,
            'summary' => $summary,
            'totalEmployees' => $rows->count(),
            'selectedRow' => $selectedRow,
        ];

        if ($isAjax) {
            return response()->json([
                'rows' => $rows->values()->all(),
                'summary' => $summary,
                'totalEmployees' => $rows->count(),
                'activeDivision' => $division,
                'activeDate' => $activeDate,
                'selectedRow' => $selectedRow,
            ]);
        }

        return view('absen.kanca', $payload);
    }

    public function exportKanca(Request $request)
    {
        $activeDate = $request->query('date', now()->toDateString());
        try {
            $activeDate = Carbon::parse($activeDate)->toDateString();
        } catch (\Throwable $th) {
            $activeDate = now()->toDateString();
        }

        $division = trim((string) $request->query('division', ''));
        $search = trim((string) $request->query('search', ''));

        $startDate = $request->query('start_date', $activeDate);
        try {
            $startDate = Carbon::parse($startDate)->toDateString();
        } catch (\Throwable $th) {
            $startDate = $activeDate;
        }

        $endDate = $request->query('end_date', $activeDate);
        try {
            $endDate = Carbon::parse($endDate)->toDateString();
        } catch (\Throwable $th) {
            $endDate = $activeDate;
        }

        if ($startDate > $endDate) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $filename = $startDate === $endDate
            ? 'absen-kanca-' . $startDate . '.xlsx'
            : 'absen-kanca-' . $startDate . '_sampai_' . $endDate . '.xlsx';

        return Excel::download(
            new KancaAttendanceExport($startDate, $endDate, $division, $search),
            $filename
        );
    }

    public function getPegawaiByUnit($uker_id)
    {
        $pegawai = Pegawai::where('uker_id', $uker_id)->get();
        return response()->json($pegawai);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'uker'         => ['required'],
            'pegawai_id'   => ['required', 'array', 'min:1'],
            'pegawai_id.*' => ['nullable'],   // ← nullable karena ada skip row kosong
            'alasan'       => ['required', 'array', 'min:1'],
            'alasan.*'     => ['nullable', 'string', 'max:50'],
        ]);

        $rows = [];
        $now = now();
        // dd($request->all());

        foreach ($validated['pegawai_id'] as $i => $pegawai_id) {
            $alasan = $validated['alasan'][$i] ?? null;

            // skip row kosong
            if (!$pegawai_id || !$alasan) continue;

            $rows[] = [
                'pegawai_id'  => $pegawai_id,
                'alasan'      => $alasan,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        DB::transaction(function () use ($rows) {
            if (count($rows)) {
                Absen::insert($rows);
            }
        });

        if (count($rows) === 0) {
            return redirect()->route("Input-Index")
                ->with('error', 'Tidak ada data yang disimpan. Pilih pegawai & alasan dulu ya.');
        }

        Alert::success('Tersimpan ' . count($rows) . ' Data Absensi Pegawai');
        return redirect()->route("Input-Index");
    }

    public function saveKancaStatus(Request $request)
    {
        $base = $request->validate([
            'date' => ['required', 'date'],
            'division' => ['nullable', 'string', 'max:255'],
            'search' => ['nullable', 'string', 'max:255'],
            'changes_payload' => ['nullable', 'string'],
        ]);

        $activeDate = Carbon::parse($base['date'])->toDateString();

        $changes = [];
        if (!empty($base['changes_payload'])) {
            $decoded = json_decode($base['changes_payload'], true);
            if (is_array($decoded)) {
                $changes = $decoded;
            }
        }

        if ($changes === []) {
            $single = $request->validate([
                'kanca_id' => ['required', 'integer', Rule::exists('kanca', 'id')],
                'status' => ['required', Rule::in(self::KANCA_STATUSES)],
                'notes' => ['nullable', 'string', 'max:1000'],
            ]);

            $changes[] = [
                'kanca_id' => (int) $single['kanca_id'],
                'status' => $single['status'],
                'notes' => $single['notes'] ?? '',
            ];
        }

        $validatedChanges = validator(
            ['changes' => $changes],
            [
                'changes' => ['required', 'array', 'min:1'],
                'changes.*.kanca_id' => ['required', 'integer', Rule::exists('kanca', 'id')],
                'changes.*.status' => ['required', Rule::in(self::KANCA_STATUSES)],
                'changes.*.notes' => ['nullable', 'string', 'max:1000'],
            ]
        )->validate()['changes'];

        DB::transaction(function () use ($validatedChanges, $activeDate) {
            foreach ($validatedChanges as $change) {
                $notes = trim((string) ($change['notes'] ?? ''));

                if ($change['status'] === 'Masuk' && $notes === '') {
                    KancaAttendance::query()
                        ->where('kanca_id', $change['kanca_id'])
                        ->whereDate('attendance_date', $activeDate)
                        ->delete();
                    continue;
                }

                KancaAttendance::updateOrCreate(
                    [
                        'kanca_id' => $change['kanca_id'],
                        'attendance_date' => $activeDate,
                    ],
                    [
                        'status' => $change['status'],
                        'notes' => $notes !== '' ? $notes : null,
                    ]
                );
            }
        });

        $lastChangedId = (int) ($validatedChanges[count($validatedChanges) - 1]['kanca_id'] ?? 0);

        return redirect()->route('Input-Index-Kanca', [
            'date' => $activeDate,
            'division' => $base['division'] ?? null,
            'search' => $base['search'] ?? null,
            'selected' => $lastChangedId > 0 ? $lastChangedId : null,
        ])->with('success', count($validatedChanges) . ' status pegawai berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InputAbsen $inputAbsen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InputAbsen $inputAbsen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InputAbsen $inputAbsen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InputAbsen $inputAbsen, $id)
    {

        Absen::destroy($id);
        Alert::success("Delete Berhasil");

        return redirect()->route('absen.dashboard')
            ->with('error', 'Delete Berhasil ');;
    }
}
