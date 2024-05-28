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
        'product_supplier_id',
        'supplier_id',
        'user_id',
        'ref_id',
        'note',
        'status'
    ];

    // relationship
    public function topup()
    {
        return $this->belongsTo(Topup::class);
    }

    public function product_suppliers()
    {
        return $this->belongsTo(ProductSupplier::class, 'product_supplier_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    

}
