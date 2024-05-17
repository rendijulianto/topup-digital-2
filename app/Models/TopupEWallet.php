<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupEWallet extends Model
{
    use HasFactory;

    protected $table = 'topup_e_wallet';

    protected $fillable = [
        'topup_id',
        'nama_pelanggan',
    ];
    public $timestamps = false;

}
