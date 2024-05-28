<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupVoucher extends Model
{
    use HasFactory;

    // table name
    protected $table = 'topup_voucher';

    // fillable attrib
    protected $fillable = [
        'topup_id',
        'expired_at',
        'status',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public $timestamps = false;

}
