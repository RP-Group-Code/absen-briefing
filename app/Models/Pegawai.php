<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table    = 'pegawais';
    protected $fillable = ['uker_id', 'nama', 'pn', 'jabatan'];

    /* ── Relasi ke Absen ── */
    public function absens()
    {
        return $this->hasMany(Absen::class, 'pegawai_id');
    }
    // Relasi balik (opsional)
    public function Uker()
    {
        return $this->belongsTo(Uker::class, 'uker_id', 'id', "created_at");
    }
}
