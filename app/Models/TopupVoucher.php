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
        'tgl_kadaluarsa',
        'status',
    ];

    // date
    protected $casts = [
        'tgl_kadaluarsa' => 'datetime',
    ];

    // relationship
    public function topup()
    {
        return $this->belongsTo(Topup::class);
    }

    public $timestamps = false;

}
