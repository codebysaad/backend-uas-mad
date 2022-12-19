<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanCuti extends Model
{
    use HasFactory;
    protected $table = 'permohonan_cutis';

    protected $fillable = [
        'id_user',
        'jns_cuti',
        'alasan',
        'tgl_awal',
        'tgl_akhir',
        'status',
        'tgl_status',
    ];
}
