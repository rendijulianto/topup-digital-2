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

    public function getStatusHtmlAttribute()
    {
        switch($this->status){
        case 'pending':
            return '<span class="badge bg-warning-subtle text-warning rounded-pill">Pending</span>';
            break;
        case 'sukses':
            return '<span class="badge bg-success-subtle text-success rounded-pill">Sukses</span>';
            break;
        case 'gagal':	
            return '<span class="badge bg-danger-subtle text-danger rounded-pill">Gagal</span>';
            break;
        default :
            return '<span class="badge bg-warning-subtle text-warning rounded-pill">Pending</span>';
        }
    }

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
