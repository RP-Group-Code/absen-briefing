<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HadiahUndi extends Model
{
    protected $table = 'hadiah_undi';

    protected $fillable = [
        'nama_hadiah',
        'kategori',
        'deskripsi',
        'stock_total',
        'stock_sisa',
        'is_active',
    ];

    protected $casts = [
        'stock_total' => 'integer',
        'stock_sisa' => 'integer',
        'is_active' => 'boolean',
    ];

    public function pemenang(): HasMany
    {
        return $this->hasMany(Pemenang::class, 'hadiah_undi_id');
    }
}
