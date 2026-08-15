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
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function pemenang(): HasMany
    {
        return $this->hasMany(Pemenang::class, 'peserta_undi_id');
    }
}
