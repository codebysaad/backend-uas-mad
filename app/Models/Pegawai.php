<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'nama_lengkap',
        'alamat',
        'tmpt_lahir',
        'tgl_lahir',
    ];
}
