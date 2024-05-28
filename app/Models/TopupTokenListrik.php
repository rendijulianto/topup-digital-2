<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupTokenListrik extends Model
{
    use HasFactory;

    protected $table = 'topup_token_listrik';

    protected $fillable = [
        'topup_id',
        'customer_name',
        'meter_no',
        'subscriber_id',
        'segment_power'
    ];

    public $timestamps = false;
}
