<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemenang extends Model
{
    protected $table = 'pemenang';

    protected $fillable = [
        'peserta_undi_id',
        'hadiah_undi_id',
        'undian_ke',
        'catatan',
        'won_at',
    ];

    protected $casts = [
        'undian_ke' => 'integer',
        'won_at' => 'datetime',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(PesertaUndi::class, 'peserta_undi_id');
    }

    public function hadiah(): BelongsTo
    {
        return $this->belongsTo(HadiahUndi::class, 'hadiah_undi_id');
    }
}
