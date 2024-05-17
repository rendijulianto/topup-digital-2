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
        'nama_pelanggan',
        'nomor_meter',
        'id_pelanggan',
        'segment_power'
    ];

    public $timestamps = false;
}
