<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
    use HasFactory;
    protected $table = 'product_suppliers';
    protected $fillable = [
        'supplier_id',
        'product_id',
        'product_sku_code',
        'price',
        'stock',
        'status',
        'multi',
        'start_cut_off',
        'end_cut_off',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function topup_api()
    {
        return $this->hasMany(TopupApi::class, 'product_supplier_id');
    }
}
