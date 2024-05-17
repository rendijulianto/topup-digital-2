<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierProduk extends Model
{
    use HasFactory;

    // table name
    protected $table = 'supplier_produk';

    // fillable attrib
    protected $fillable = [
        'supplier_id',
        'produk_id',
        'produk_sku_code',
        'harga',
        'stok',
        'status',
        'multi',
        'jam_buka',
        'jam_tutup',
    ];

    // relation to supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // relation to produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // relation to topup
    public function topup_api()
    {
        return $this->hasMany(TopupApi::class, 'supplier_produk_id', 'id');
    }

}
