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
        'status',
    ];

    public function getIsActiveAttribute(): bool
    {
        $status = trim((string) $this->status);

        if ($status === '') {
            return false;
        }

        return ! in_array(mb_strtolower($status), ['nonaktif', 'inactive', '0'], true);
    }

    public function pemenang(): HasMany
    {
        return $this->hasMany(Pemenang::class, 'peserta_undi_id');
    }
}
