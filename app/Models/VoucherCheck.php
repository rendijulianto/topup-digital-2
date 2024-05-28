<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherCheck extends Model
{
    use HasFactory;

    protected $table = 'voucher_checks';

    protected $fillable = [
        'ref_id',
        'target',
        'note',
        'status',
        'brand_id'
    ];
}
