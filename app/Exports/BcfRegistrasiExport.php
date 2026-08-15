<?php

namespace App\Exports;

use App\Models\BcfRegistrasi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BcfRegistrasiExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        private readonly string $search = ''
    ) {}

    public function collection(): Collection
    {
        return BcfRegistrasi::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($query) {
                    $like = '%' . $this->search . '%';
                    $query->where('nama', 'like', $like)
                        ->orWhere('pn', 'like', $like)
                        ->orWhere('team', 'like', $like)
                        ->orWhere('warna', 'like', $like)
                        ->orWhere('unit_kerja', 'like', $like)
                        ->orWhere('nourut', 'like', $like);
                });
            })
            ->orderBy('nourut')
            ->orderByDesc('created_at')
            ->get()
            ->map(function (BcfRegistrasi $row) {
                return [
                    'Nama' => $row->nama,
                    'PN' => $row->pn,
                    'Warna' => $row->warna,
                    'No Urut' => $row->nourut,
                    'Team' => $row->team,
                    'Unit Kerja' => $row->unit_kerja,
                    'Dibuat Pada' => optional($row->created_at)?->format('Y-m-d H:i:s'),
                ];
            });
    }

    public function headings(): array
    {
        return ['Nama', 'PN', 'Warna', 'No Urut', 'Team', 'Unit Kerja', 'Dibuat Pada'];
    }
}
