<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualUndian extends Model
{
    protected $table = 'manual_undian';

    protected $fillable = [
        'peserta_undi_id',
        'hadiah_undi_id',
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
