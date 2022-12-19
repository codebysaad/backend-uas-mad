<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCuti extends Model
{
    use HasFactory;
    protected $table = 'jeniscuti';

    protected $fillable = [
        'jenis_cuti',
        'deskripsi',
    ];
}
