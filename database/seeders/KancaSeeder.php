<?php

namespace Database\Seeders;

use App\Models\Kanca;
use Illuminate\Database\Seeder;

class KancaSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('seeders/data/employees.csv');

        if (!is_file($csvPath) || !is_readable($csvPath)) {
            $this->command?->warn("File tidak ditemukan: {$csvPath}");
            return;
        }

        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            $this->command?->warn("Gagal membuka file: {$csvPath}");
            return;
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            $this->command?->warn('Header CSV kosong.');
            return;
        }

        $headerMap = $this->buildHeaderMap($header);
        $required = ['division', 'name', 'jabatan'];

        foreach ($required as $key) {
            if (!array_key_exists($key, $headerMap)) {
                fclose($handle);
                $this->command?->warn("Kolom '{$key}' tidak ditemukan di CSV.");
                return;
            }
        }

        $now = now();
        $rows = [];

        while (($row = fgetcsv($handle)) !== false) {
            $division = trim((string) ($row[$headerMap['division']] ?? ''));
            $name = trim((string) ($row[$headerMap['name']] ?? ''));
            $jabatan = trim((string) ($row[$headerMap['jabatan']] ?? ''));

            if ($division === '' || $name === '') {
                continue;
            }

            $rows[] = [
                'division' => $division,
                'name' => $name,
                'jabatan' => $jabatan !== '' ? $jabatan : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        fclose($handle);

        if ($rows === []) {
            $this->command?->warn('Tidak ada baris valid yang bisa diimport.');
            return;
        }

        Kanca::query()->upsert(
            $rows,
            ['division', 'name'],
            ['jabatan', 'updated_at']
        );

        $this->command?->info('Data kanca berhasil disinkronkan dari employees.csv');
    }

    /**
     * @param  array<int, string>  $header
     * @return array<string, int>
     */
    private function buildHeaderMap(array $header): array
    {
        $map = [];

        foreach ($header as $index => $name) {
            $normalized = strtolower(trim((string) $name));
            $normalized = preg_replace('/^\xEF\xBB\xBF/', '', $normalized);
            if ($normalized !== '') {
                $map[$normalized] = $index;
            }
        }

        return $map;
    }
}
