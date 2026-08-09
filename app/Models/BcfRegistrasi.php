<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BcfRegistrasi extends Model
{
    use HasFactory;

    protected $table = 'bcf_registrasis';

    protected $fillable = [
        'nama',
        'pn',
        'unit_kerja',
        'tanggal',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
