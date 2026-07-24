<?php

namespace App\Imports;

use App\Models\Pegawai;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PegawaiImport implements
    ToCollection,
    WithHeadingRow,   // baca baris pertama sebagai header
    SkipsOnError      // skip baris error, lanjutkan import
{
    use SkipsErrors;

    private int $rowCount    = 0;
    private int $skippedCount = 0;

    /**
     * Kolom Excel yang diharapkan:
     * | uker_id | nama | pn | jabatan |
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $baris = $index + 2; // +2 karena baris 1 = header

            // ── Validasi WAJIB ──
            $missingFields = [];

            if (empty($row['uker_id'])) {
                $missingFields[] = 'uker_id';
            }
            if (empty($row['nama'])) {
                $missingFields[] = 'nama';
            }
            if (empty($row['pn'])) {
                $missingFields[] = 'pn';
            }

            if (!empty($missingFields)) {
                $this->errors[] = "Baris {$baris}: kolom wajib diisi kedapat blank : " .
                    implode(', ', $missingFields);
                $this->skippedCount++;
                continue;
            }

            // ── Cek duplikat PN ──
            $exists = Pegawai::where('pn', trim($row['pn']))->exists();
            if ($exists) {
                $this->errors[] = "Baris {$baris}: PN '{$row['pn']}' sudah terdaftar (duplikat).";
                $this->skippedCount++;
                continue;
            }

            Pegawai::create([
                'uker_id' => $row['uker_id'],
                'nama'    => trim($row['nama']),
                'pn'      => trim($row['pn']),
                'jabatan' => trim($row['jabatan'] ?? ''),
            ]);

            $this->rowCount++;
        }
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
    public function getErrors(): array
    {
        return $this->errors;
    }
}
