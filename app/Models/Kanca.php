<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kanca extends Model
{
    use HasFactory;

    protected $table = 'kanca';

    protected $fillable = [
        'division',
        'name',
        'jabatan',
    ];

    public function attendances()
    {
        return $this->hasMany(KancaAttendance::class, 'kanca_id');
    }
}
