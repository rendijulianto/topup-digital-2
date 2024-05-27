<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CekNama extends Model
{
    use HasFactory;

    protected $table = 'cek_nama';

    protected $fillable = [
        'ref_id',
        'brand_id',
        'nomor',
        'nama',
        'status',
    ];
}
