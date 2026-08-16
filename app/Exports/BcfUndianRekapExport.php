<?php

namespace App\Exports;

use App\Models\Pemenang;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BcfUndianRekapExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection(): Collection
    {
        return Pemenang::query()
            ->with(['peserta', 'hadiah'])
            ->orderByDesc('won_at')
            ->orderByDesc('id')
            ->get()
            ->map(function (Pemenang $pemenang) {
                return [
                    'Undian Ke' => $pemenang->undian_ke,
                    'No Hadiah' => $pemenang->hadiah?->no_urut,
                    'Nama Peserta' => $pemenang->peserta?->nama,
                    'PN' => $pemenang->peserta?->pn,
                    'Unit Kerja' => $pemenang->peserta?->unit_kerja,
                    'Jabatan' => $pemenang->peserta?->jabatan,
                    'Hadiah' => $pemenang->hadiah?->nama_hadiah,
                    'Kategori Hadiah' => $pemenang->hadiah?->kategori,
                    'Waktu Menang' => optional($pemenang->won_at)->format('Y-m-d H:i:s'),
                    'Catatan' => $pemenang->catatan,
                ];
            });
    }

    public function headings(): array
    {
        return ['Undian Ke', 'No Hadiah', 'Nama Peserta', 'PN', 'Unit Kerja', 'Jabatan', 'Hadiah', 'Kategori Hadiah', 'Waktu Menang', 'Catatan'];
    }
}
