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
        'customer_name',
    ];
    public $timestamps = false;
}
