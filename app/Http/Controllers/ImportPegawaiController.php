<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\PegawaiImport;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ImportPegawaiController extends Controller
{
    /* ── Tampilkan halaman import ── */
    public function index()
    {
        return view('pegawai.import');
    }

    /* ── Proses upload & import ── */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:11120',
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            $import = new PegawaiImport();
            Excel::import($import, $request->file('file'));

            $imported = $import->getRowCount();
            $skipped  = $import->getSkippedCount();
            $errors   = $import->getErrors();

            if ($imported === 0 && $skipped > 0) {
                // Semua baris gagal
                $errorDetail = implode('\n', array_slice($errors, 0, 5)); // max 5 error ditampilkan
                Alert::error(
                    'Import Gagal!',
                    "Tidak ada data yang tersimpan.\n{$errorDetail}"
                )->persistent('Tutup');
                return redirect()->route('pegawai.import');
            }

            if ($skipped > 0) {
                // Sebagian berhasil, sebagian skip
                Alert::warning(
                    "Import Sebagian — {$imported} tersimpan",
                    "{$skipped} baris dilewati:\n" . implode('\n ', array_slice($errors, 0, 3))
                )->persistent('Mengerti');
                return redirect()->route('pegawai.dashboard');
            }

            Alert::success(
                'Import Berhasil!',
                "✅ {$imported} data pegawai berhasil diimport."
            )->autoClose(4000);
            return redirect()->route('pegawai.dashboard');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors   = collect($failures)->map(
                fn($f) =>
                "Baris {$f->row()}: " . implode(', ', $f->errors())
            )->implode('<br>');

            return back()->withErrors(['file' => $errors]);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal import: ' . $e->getMessage()]);
        }
    }

    /* ── Download template Excel ── */
    public function template()
    {
        $headers = ['uker_id', 'nama', 'pn', 'jabatan'];
        $rows    = [
            [1, 'Contoh Nama Pegawai', '123456', 'CUSTOMER SERVICE'],
            [1, 'Contoh Nama Dua',     '789012', 'SUPERVISOR'],
        ];

        // Buat file CSV sederhana sebagai template
        $callback = function () use ($headers, $rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_pegawai.csv"',
        ]);
    }
}
