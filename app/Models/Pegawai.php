<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawais';

    protected $fillable = [
        'id_user',
        'id_jabatan',
        'nama_lengkap',
        'alamat',
        'tmpt_lahir',
        'tgl_lahir',
    ];
}
