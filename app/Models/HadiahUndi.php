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
        'harga',
        'status',
    ];

    protected $casts = [
        'stock_total' => 'integer',
        'stock_sisa' => 'integer',
        'harga' => 'integer',
        'status' => 'boolean',
    ];

    public function getIsActiveAttribute(): bool
    {
        return (bool) $this->status;
    }

    public function pemenang(): HasMany
    {
        return $this->hasMany(Pemenang::class, 'hadiah_undi_id');
    }
}
