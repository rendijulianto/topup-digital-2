<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenggunaPin extends Model
{
    use HasFactory;

    protected $table = 'pengguna_pin';

    protected $fillable = [
        'pengguna_id',
        'pin',
    ];
}
