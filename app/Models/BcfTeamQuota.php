<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BcfTeamQuota extends Model
{
    protected $table = 'bcf_team_quotas';

    protected $fillable = [
        'nourut',
        'team',
        'warna',
        'penanggung_jawab',
        'capacity',
    ];

    protected $casts = [
        'nourut' => 'integer',
        'capacity' => 'integer',
    ];
}
