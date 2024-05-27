<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';

    protected $fillable = [
        'nama',
        'kategori_id',
        'tipe_id',
        'brand_id',
        'harga',
        'deskripsi',
    ];

    protected $appends = [
        'status'
    ];

    public function scopeDisruption($query)
    {
        return $query->whereDoesntHave('supplier_produk', function ($query) {
            $query->where('status', 1);
        });        
    }

    public function scopeCategory($query, $category)
    {
        if ($category == 'all' || $category == null) {
            return $query;
        }

        return $query->whereHas('kategori', function ($query) use ($category) {
            $query->where('nama', $category)->orWhere('id', $category);
        });
    }

    public function scopeBrand($query, $brand)
    {
        if ($brand == 'all' || $brand == null) {
            return $query;
        }
        return $query->whereHas('brand', function ($query) use ($brand) {
            $query->where('nama', $brand)->orWhere('id', $brand);
        });
    }

    public function scopeType($query, $type)
    {
        if ($type == 'all' || $type == null) {
            return $query;
        }

        return $query->whereHas('tipe', function ($query) use ($type) {
            $query->where('nama', $type)->orWhere('id', $type);
        });
    }

    public function scopeSearch($query, $request)
    {
        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                ->orWhereHas('kategori', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('brand', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('tipe', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%');
                });
        }

        if ($request->brand && $request->brand != 'Semua') {
            $query->where('brand_id', $request->brand);
        }

        if ($request->kategori && $request->kategori != 'Semua') {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->tipe && $request->tipe != 'Semua') {
            $query->where('tipe_id', $request->tipe);
        }

        if ($request->supplier && $request->supplier != 'Semua') {
            $query->whereHas('supplier_produk', function ($query) use ($request) {
                $query->where('supplier_id', $request->supplier);
            });
        }

        if ($request->status && $request->status != 'Semua' || $request->status == '0') {
            if ($request->status == '0') {
                $query->whereDoesntHave('supplier_produk', function ($query) {
                    $query->where('status', 1);
                });
            }
        }
        return $query;
    }

    public function scopeBestSeller($query, $request)
    {
        if ($request->start && $request->end) {
            $query->
           
            whereHas('topup', function ($query) use ($request) {
                
                $query->whereBetween('tgl_transaksi', [$request->start . ' 00:00:00', $request->end . ' 23:59:59'])
                ->where('status', 'sukses');
            });
        }

        $query->withCount('topup')
        ->orderByDesc('topup_count')
        ->take($request->jumlah);

        return $query;
    }


    // relationship
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function tipe()
    {
        return $this->belongsTo(Tipe::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }


    public function supplier_produk()
    {
        return $this->hasMany(SupplierProduk::class, 'produk_id');
    }

    public function topup()
    {
        return $this->hasMany(Topup::class, 'produk_id');
    }

    public function getStatusAttribute()
    {
        $status = true;
        if ($this->supplier_produk->where('status', 1)->count() == 0) {
            $status = false;
        }
        unset($this->supplier_produk);
        return $status;
    }

    public function getHargaTermahalAttribute()
    {
        return $this->supplier_produk->max('harga');
    }
   
    
    public function getSupplier()
    {
        $suppliers = $this->supplier_produk->filter(function ($supplier) {
            return $supplier->status == 1;
        })->sortBy('harga');
        
        $supplierArray = $suppliers->map(function ($supplier) {
            $totalTopup = $supplier->topup_api->count();
            $totalSuccessTopup = $supplier->topup_api->where('status', 'sukses')->count();
            return [
                'id' => $supplier->id,
                'name' => strtoupper($supplier->supplier->nama),
                'price' => number_format($supplier->harga, 0, ',', '.'),
                'total_topup' => $totalTopup,
                'total_berhasil' => $totalSuccessTopup,
                'persentase_berhasil' => round($totalTopup == 0 ? 0 : ($totalSuccessTopup / $totalTopup) * 100),
            ];
        });
        
        return $supplierArray->values()->toArray();
    }
}
