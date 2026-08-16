<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PesertaUndi extends Model
{
    protected $table = 'peserta_undi';

    protected $fillable = [
        'nama',
        'pn',
        'unit_kerja',
        'jabatan',
        'Jabatan',
        'status',
    ];

    public function getJabatanAttribute(mixed $value): ?string
    {
        return $value ?? ($this->attributes['Jabatan'] ?? null);
    }

    public function setJabatanAttribute(mixed $value): void
    {
        $jabatan = trim((string) ($value ?? ''));

        if (array_key_exists('Jabatan', $this->attributes) && ! array_key_exists('jabatan', $this->attributes)) {
            $this->attributes['Jabatan'] = $jabatan;

            return;
        }

        $this->attributes['jabatan'] = $jabatan;
    }

    public function getIsActiveAttribute(): bool
    {
        $status = trim((string) $this->status);

        if ($status === '') {
            return false;
        }

        return mb_strtolower($status) === 'belum menang';
    }

    public function pemenang(): HasMany
    {
        return $this->hasMany(Pemenang::class, 'peserta_undi_id');
    }
}
