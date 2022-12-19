<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendances';
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'id_user',
        'type',
        'attend',
        'photo',
        'long',
        'lat',
        'date',
    ];
}
