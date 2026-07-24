<?php

namespace App\Exports;

use App\Models\Kanca;
use App\Models\KancaAttendance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KancaAttendanceExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        private readonly string $startDate,
        private readonly string $endDate,
        private readonly string $division = '',
        private readonly string $search = ''
    ) {}

    public function collection(): Collection
    {
        if (!Schema::hasTable('kanca')) {
            return collect();
        }

        $employeesQuery = Kanca::query()
            ->orderBy('division')
            ->orderBy('name');

        if ($this->division !== '') {
            $employeesQuery->where('division', $this->division);
        }

        if ($this->search !== '') {
            $employeesQuery->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('jabatan', 'like', "%{$this->search}%")
                    ->orWhere('division', 'like', "%{$this->search}%");
            });
        }

        $employees = $employeesQuery->get(['id', 'division', 'jabatan', 'name']);

        if ($employees->isEmpty()) {
            return collect();
        }

        $startDate = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);
        if ($startDate->gt($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $dates = collect();
        $cursor = $startDate->copy();
        while ($cursor->lte($endDate)) {
            $dates->push($cursor->toDateString());
            $cursor->addDay();
        }

        $attendanceMap = collect();
        if (Schema::hasTable('kanca_attendances')) {
            $attendanceMap = KancaAttendance::query()
                ->whereDate('attendance_date', '>=', $startDate->toDateString())
                ->whereDate('attendance_date', '<=', $endDate->toDateString())
                ->whereIn('kanca_id', $employees->pluck('id'))
                ->get()
                ->keyBy(function (KancaAttendance $attendance) {
                    $date = $attendance->attendance_date instanceof Carbon
                        ? $attendance->attendance_date->toDateString()
                        : Carbon::parse($attendance->attendance_date)->toDateString();
                    return $attendance->kanca_id . '|' . $date;
                });
        }

        return $employees->flatMap(function (Kanca $employee) use ($dates, $attendanceMap) {
            return $dates->map(function (string $date) use ($employee, $attendanceMap) {
                $attendance = $attendanceMap->get($employee->id . '|' . $date);
                $status = $attendance?->status ?? 'Masuk';

                return [
                    'Tanggal' => $date,
                    'Divisi' => $employee->division,
                    'Jabatan' => $employee->jabatan ?? '-',
                    'Nama' => $employee->name,
                    'Status' => $status,
                    'Indikator' => strtoupper(substr($status, 0, 1)),
                ];
            });
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Divisi', 'Jabatan', 'Nama', 'Status', 'Indikator'];
    }
}
