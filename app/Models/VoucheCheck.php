<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CekVoucher extends Model
{
    use HasFactory;

    protected $table = 'cek_voucher';

    protected $fillable = [
        'ref_id',
        'nomor',
        'keterangan',
        'status',
        'brand_id'
    ];
}
