<?php

namespace App\Http\Controllers;

use App\Exports\BcfUndianRekapExport;
use App\Models\HadiahUndi;
use App\Models\PesertaUndi;
use App\Models\Pemenang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class BcfUndianController extends Controller
{
    public function index(Request $request)
    {
        [$dashboard, $hadiahTersedia, $pesertaTersedia, $recentWinner] = $this->buildUndianSummary();

        $peserta = PesertaUndi::query()
            ->latest('id')
            ->paginate(10, ['*'], 'peserta_page')
            ->withQueryString();

        $hadiah = HadiahUndi::query()
            ->latest('id')
            ->paginate(10, ['*'], 'hadiah_page')
            ->withQueryString();

        $pemenang = Pemenang::query()
            ->with(['peserta', 'hadiah'])
            ->latest('won_at')
            ->latest('id')
            ->paginate(10, ['*'], 'pemenang_page')
            ->withQueryString();

        return view('bcf.undian', compact(
            'peserta',
            'hadiah',
            'pemenang',
            'dashboard',
            'hadiahTersedia',
            'pesertaTersedia',
            'recentWinner'
        ));
    }

    public function live()
    {
        [$dashboard, $hadiahTersedia, $pesertaTersedia, $recentWinner] = $this->buildUndianSummary();

        $pemenangTerbaru = Pemenang::query()
            ->with(['peserta', 'hadiah'])
            ->latest('won_at')
            ->latest('id')
            ->take(8)
            ->get();

        $pesertaPool = PesertaUndi::query()
            ->where('status', 'Belum Menang')
            ->whereDoesntHave('pemenang')
            ->inRandomOrder()
            ->get(['nama', 'pn', 'unit_kerja', 'jabatan']);

        return view('bcf.undian-live', compact(
            'dashboard',
            'hadiahTersedia',
            'pesertaTersedia',
            'recentWinner',
            'pemenangTerbaru',
            'pesertaPool'
        ));
    }

    public function storePeserta(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'pn' => 'nullable|string|max:100',
            'unit_kerja' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:144',
            'status' => 'nullable|string|max:255',
        ]);

        PesertaUndi::create([
            'nama' => $validated['nama'],
            'pn' => $validated['pn'] ?? null,
            'unit_kerja' => $validated['unit_kerja'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'status' => $this->normalizePesertaStatus($validated['status'] ?? null),
        ]);

        Alert::success('Peserta Ditambahkan', 'Peserta undian berhasil disimpan.');

        return redirect()->route('bcf.undian.index', ['tab' => 'peserta']);
    }

    public function importPeserta(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $rows = $this->readImportRows($request->file('file'));
        [$headers, $records] = $this->extractSheetRecords($rows);

        $created = 0;
        foreach ($records as $row) {
            $nama = trim((string) $this->cellValue($row, $headers, ['nama', 'name']));
            if ($nama === '') {
                continue;
            }

            PesertaUndi::create([
                'nama' => $nama,
                'pn' => $this->nullableString($this->cellValue($row, $headers, ['pn', 'nip'])),
                'unit_kerja' => $this->nullableString($this->cellValue($row, $headers, ['unit kerja', 'unit_kerja', 'uker'])),
                'jabatan' => $this->nullableString($this->cellValue($row, $headers, ['jabatan', 'job title', 'posisi'])),
                'status' => $this->normalizePesertaStatus($this->nullableString($this->cellValue($row, $headers, ['status']))),
            ]);
            $created++;
        }

        if ($created === 0) {
            throw ValidationException::withMessages([
                'file' => 'Tidak ada data peserta yang berhasil dibaca dari file import.',
            ]);
        }

        Alert::success('Import Peserta Selesai', $created . ' peserta berhasil diimport.');

        return redirect()->route('bcf.undian.index', ['tab' => 'peserta']);
    }

    public function storeHadiah(Request $request)
    {
        $validated = $request->validate([
            'nama_hadiah' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'stock_total' => 'required|integer|min:1|max:100000',
            'harga' => 'required|integer|min:0|max:999999999999',
        ]);

        HadiahUndi::create([
            'nama_hadiah' => $validated['nama_hadiah'],
            'kategori' => $validated['kategori'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'stock_total' => $validated['stock_total'],
            'stock_sisa' => $validated['stock_total'],
            'harga' => $validated['harga'],
            'status' => true,
        ]);

        Alert::success('Hadiah Ditambahkan', 'Hadiah undian berhasil disimpan.');

        return redirect()->route('bcf.undian.index', ['tab' => 'hadiah']);
    }

    public function importHadiah(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $rows = $this->readImportRows($request->file('file'));
        [$headers, $records, $hasHeader] = $this->extractSheetRecordsWithDetection($rows, [
            'nama hadiah',
            'nama_hadiah',
            'hadiah',
            'nama barang',
            'nama bararang',
            'stok total',
            'stock_total',
            'status',
            'harga',
        ]);

        $created = 0;
        foreach ($records as $row) {
            $payload = $hasHeader
                ? $this->mapHadiahRowWithHeaders($row, $headers)
                : $this->mapHadiahRowByPosition($row);

            if ($payload === null) {
                continue;
            }

            HadiahUndi::create($payload);
            $created++;
        }

        if ($created === 0) {
            throw ValidationException::withMessages([
                'file' => 'Format file hadiah tidak dikenali atau seluruh baris kosong.',
            ]);
        }

        Alert::success('Import Hadiah Selesai', $created . ' hadiah berhasil diimport.');

        return redirect()->route('bcf.undian.index', ['tab' => 'hadiah']);
    }

    public function draw(Request $request)
    {
        $validated = $request->validate([
            'hadiah_undi_id' => 'nullable|exists:hadiah_undi,id',
            'redirect_to' => 'nullable|string|in:index,live',
        ]);

        $winner = DB::transaction(function () use ($validated) {
            $pesertaPool = PesertaUndi::query()
                ->where('status', 'Belum Menang')
                ->whereDoesntHave('pemenang')
                ->lockForUpdate()
                ->get();

            if ($pesertaPool->isEmpty()) {
                throw ValidationException::withMessages(['undian' => 'Tidak ada peserta aktif yang bisa diundi.']);
            }

            $hadiahQuery = HadiahUndi::query()
                ->where('status', true)
                ->where('stock_sisa', '>', 0)
                ->lockForUpdate();

            if (! empty($validated['hadiah_undi_id'])) {
                $hadiahQuery->whereKey($validated['hadiah_undi_id']);
            }

            $hadiahPool = $hadiahQuery->get();
            if ($hadiahPool->isEmpty()) {
                throw ValidationException::withMessages(['hadiah_undi_id' => 'Tidak ada hadiah tersedia untuk diundi.']);
            }

            $peserta = $pesertaPool->random();
            $hadiah = $hadiahPool->random();

            $pemenang = Pemenang::create([
                'peserta_undi_id' => $peserta->id,
                'hadiah_undi_id' => $hadiah->id,
                'undian_ke' => (Pemenang::max('undian_ke') ?? 0) + 1,
                'won_at' => now(),
            ]);

            $peserta->forceFill(['status' => 'Menang'])->save();
            $hadiah->decrement('stock_sisa');

            return $pemenang->load(['peserta', 'hadiah']);
        });

        $redirectRoute = ($validated['redirect_to'] ?? 'index') === 'live'
            ? 'bcf.undian.live'
            : 'bcf.undian.index';

        return redirect()
            ->route($redirectRoute, ['tab' => 'undian'])
            ->with('undian_winner', [
                'peserta' => $winner->peserta?->nama,
                'pn' => $winner->peserta?->pn,
                'hadiah' => $winner->hadiah?->nama_hadiah,
                'kategori' => $winner->hadiah?->kategori,
                'undian_ke' => $winner->undian_ke,
            ]);
    }

    public function exportRekap()
    {
        return Excel::download(
            new BcfUndianRekapExport(),
            'bcf-undian-rekap-' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    private function buildUndianSummary(): array
    {
        $dashboard = [
            'total_peserta' => PesertaUndi::count(),
            'peserta_aktif' => PesertaUndi::where('status', 'Belum Menang')->count(),
            'total_hadiah' => HadiahUndi::sum('stock_total'),
            'sisa_hadiah' => HadiahUndi::sum('stock_sisa'),
            'total_pemenang' => Pemenang::count(),
        ];

        $hadiahTersedia = HadiahUndi::query()
            ->where('status', true)
            ->where('stock_sisa', '>', 0)
            ->orderBy('kategori')
            ->orderBy('nama_hadiah')
            ->get();

        $pesertaTersedia = PesertaUndi::query()
            ->where('status', 'Belum Menang')
            ->whereDoesntHave('pemenang')
            ->count();

        $recentWinner = Pemenang::query()
            ->with(['peserta', 'hadiah'])
            ->latest('won_at')
            ->latest('id')
            ->first();

        return [$dashboard, $hadiahTersedia, $pesertaTersedia, $recentWinner];
    }

    private function extractSheetRecords(array $rows): array
    {
        $headers = array_map(fn ($value) => $this->normalizeHeading((string) $value), $rows[0] ?? []);
        $records = array_slice($rows, 1);

        return [$headers, $records];
    }

    private function extractSheetRecordsWithDetection(array $rows, array $expectedAliases): array
    {
        $headers = array_map(fn ($value) => $this->normalizeHeading((string) $value), $rows[0] ?? []);
        $hasHeader = false;

        foreach ($expectedAliases as $alias) {
            if (in_array($this->normalizeHeading($alias), $headers, true)) {
                $hasHeader = true;
                break;
            }
        }

        return [$headers, $hasHeader ? array_slice($rows, 1) : $rows, $hasHeader];
    }

    private function headerKey(array $headers, array $aliases): ?int
    {
        foreach ($aliases as $alias) {
            $normalizedAlias = $this->normalizeHeading($alias);
            $index = array_search($normalizedAlias, $headers, true);
            if ($index !== false) {
                return $index;
            }
        }

        return null;
    }

    private function normalizeHeading(string $value): string
    {
        return Str::lower(Str::of($value)->replace(['_', '-'], ' ')->squish()->value());
    }

    private function nullableString(mixed $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }

    private function readImportRows(UploadedFile $file): array
    {
        $extension = Str::lower($file->getClientOriginalExtension());

        if ($extension === 'csv') {
            return $this->readCsvRows($file->getRealPath());
        }

        return Excel::toArray([], $file)[0] ?? [];
    }

    private function readCsvRows(string $path): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return [];
        }

        $firstLine = fgets($handle);
        rewind($handle);

        $delimiter = $this->detectCsvDelimiter((string) $firstLine);
        $rows = [];

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if ($row === [null] || $row === false) {
                continue;
            }

            if (isset($row[0])) {
                $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $row[0]);
            }

            if (count(array_filter($row, fn ($value) => trim((string) $value) !== '')) === 0) {
                continue;
            }

            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }

    private function detectCsvDelimiter(string $line): string
    {
        $commaCount = substr_count($line, ',');
        $semicolonCount = substr_count($line, ';');

        return $semicolonCount > $commaCount ? ';' : ',';
    }

    private function sanitizeInteger(mixed $value, ?int $default = 0): ?int
    {
        $text = trim((string) $value);
        if ($text === '') {
            return $default;
        }

        $digits = preg_replace('/[^\d-]/', '', $text);
        if ($digits === '' || $digits === '-') {
            return $default;
        }

        return (int) $digits;
    }

    private function isHadiahStatusActive(?string $status, int $stockSisa): bool
    {
        if ($stockSisa <= 0) {
            return false;
        }

        if ($status === null) {
            return true;
        }

        $normalized = Str::lower(Str::of($status)->squish()->value());

        return ! in_array($normalized, ['habis', 'nonaktif', 'tidak tersedia', 'false', '0'], true);
    }

    private function mapHadiahRowWithHeaders(array $row, array $headers): ?array
    {
        $namaHadiah = trim((string) $this->cellValue($row, $headers, [
            'nama hadiah',
            'nama_hadiah',
            'hadiah',
            'nama barang',
            'nama bararang',
        ]));

        if ($namaHadiah === '') {
            return null;
        }

        if ($this->shouldSkipHadiahRow($namaHadiah)) {
            return null;
        }

        $stockTotal = $this->sanitizeInteger($this->cellValue($row, $headers, [
            'qty',
            'jumlah',
            'stock_total',
            'stok total',
        ]), 1);
        $stockTotal = max(1, $stockTotal);

        $stockSisa = $this->sanitizeInteger($this->cellValue($row, $headers, [
            'stock_sisa',
            'stok sisa',
            'sisa stok',
        ]), $stockTotal);
        $stockSisa = max(0, min($stockTotal, $stockSisa));

        $status = $this->nullableString($this->cellValue($row, $headers, ['status']));
        $harga = $this->sanitizeInteger($this->cellValue($row, $headers, ['harga', 'price']), 0);

        return [
            'nama_hadiah' => $namaHadiah,
            'kategori' => $this->nullableString($this->cellValue($row, $headers, ['kategori'])),
            'deskripsi' => $this->nullableString($this->cellValue($row, $headers, ['deskripsi', 'keterangan'])),
            'stock_total' => $stockTotal,
            'stock_sisa' => $stockSisa,
            'harga' => $harga,
            'status' => $this->isHadiahStatusActive($status, $stockSisa),
        ];
    }

    private function mapHadiahRowByPosition(array $row): ?array
    {
        $row = array_values($row);

        if (isset($row[0]) && is_numeric(trim((string) $row[0])) && count($row) >= 7) {
            array_shift($row);
        }

        $namaHadiah = trim((string) ($row[0] ?? ''));
        if ($namaHadiah === '') {
            return null;
        }

        if ($this->shouldSkipHadiahRow($namaHadiah)) {
            return null;
        }

        $kategori = $this->nullableString($row[1] ?? null);
        $deskripsi = $this->nullableString($row[2] ?? null);
        $stockTotal = max(1, $this->sanitizeInteger($row[3] ?? null, 1));
        $stockSisa = max(0, min($stockTotal, $this->sanitizeInteger($row[4] ?? null, $stockTotal)));
        $harga = $this->sanitizeInteger($row[5] ?? null, 0);
        $status = $this->nullableString($row[6] ?? null);

        return [
            'nama_hadiah' => $namaHadiah,
            'kategori' => $kategori,
            'deskripsi' => $deskripsi === '_' ? null : $deskripsi,
            'stock_total' => $stockTotal,
            'stock_sisa' => $stockSisa,
            'harga' => $harga,
            'status' => $this->isHadiahStatusActive($status, $stockSisa),
        ];
    }

    private function shouldSkipHadiahRow(string $namaHadiah): bool
    {
        $normalized = Str::upper(Str::of($namaHadiah)->squish()->value());

        return in_array($normalized, ['TOTAL', 'SUBTOTAL', 'JUMLAH'], true);
    }

    private function normalizePesertaStatus(?string $status): string
    {
        $normalized = Str::lower(Str::of((string) ($status ?? ''))->squish()->value());

        if ($normalized === '' || in_array($normalized, ['belum menang', 'belummenang', 'aktif', 'active', '1', 'true'], true)) {
            return 'Belum Menang';
        }

        if (in_array($normalized, ['menang', 'winner', 'won'], true)) {
            return 'Menang';
        }

        if (in_array($normalized, ['nonaktif', 'inactive', '0', 'false'], true)) {
            return 'Belum Menang';
        }

        return trim((string) $status) ?: 'Belum Menang';
    }

    private function cellValue(array $row, array $headers, array $aliases): mixed
    {
        $index = $this->headerKey($headers, $aliases);

        return $index === null ? null : ($row[$index] ?? null);
    }
}
