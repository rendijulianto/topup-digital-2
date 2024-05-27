<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupApi extends Model
{
    use HasFactory;

    // table name
    protected $table = 'topup_api';

    // fillable attrib
    protected $fillable = [
        'topup_id',
        'trx_id',
        'supplier_produk_id',
        'supplier_id',
        'pengguna_id',
        'ref_id',
        'keterangan',
        'status'
    ];

    // relationship
    public function topup()
    {
        return $this->belongsTo(Topup::class);
    }

    public function supplier_produk()
    {
        return $this->belongsTo(SupplierProduk::class, 'supplier_produk_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    
    

}
