<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KancaAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'kanca_id',
        'attendance_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function kanca()
    {
        return $this->belongsTo(Kanca::class, 'kanca_id');
    }
}
