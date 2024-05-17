<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CekNama extends Model
{
    use HasFactory;

    protected $table = 'cek_nama';

    protected $fillable = [
        'ref_id',
        'brand_id',
        'nomor',
        'nama',
        'status',
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

    // hasOne
    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    // scopeSearch
    public function scopeSearch($query, $request)
    {
        if ($request->search) {
            $query->where('nomor', 'like', '%' . $request->search . '%')
                ->orWhere('nama', 'like', '%' . $request->search . '%')
                ->orWhereHas('brand', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%');
                })
                ->orWhere('status', 'like', '%' . $request->search . '%')
                ->orWhere('ref_id', 'like', '%' . $request->search . '%');
        }

        if ($request->brand) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->start && $request->end) {
            $query->whereBetween('created_at', [$request->start. ' 00:00:00', $request->end. ' 23:59:59']);
        }

        return $query;
    }
}
