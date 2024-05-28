<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'name',
        'category_id',
        'type_id',
        'brand_id',
        'price',
        'description',
    ];

    protected $appends = [
        'status'
    ];

    public function scopeCategory($query, $category)
    {
        if ($category == 'all' || $category == null) {
            return $query;
        }

        return $query->whereHas('category', function ($query) use ($category) {
            $query->where('name', $category)->orWhere('id', $category);
        });
    }

    public function scopeCategories($query, $category)
    {
        if ($category == 'all' || $category == null) {
            return $query;
        }

        return $query->whereHas('category', function ($query) use ($category) {
            $query->where('name', $category)->orWhere('id', $category);
        });
    }

    public function scopeBrand($query, $brand)
    {
        if ($brand == 'all' || $brand == null) {
            return $query;
        }
        return $query->whereHas('brand', function ($query) use ($brand) {
            $query->where('name', $brand)->orWhere('id', $brand);
        });
    }

    public function scopeType($query, $type)
    {
        if ($type == 'all' || $type == null) {
            return $query;
        }

        return $query->whereHas('type', function ($query) use ($type) {
            $query->where('name', $type)->orWhere('id', $type);
        });
    }

    public function scopeSearch($query, $request)
    {
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%')
                ->orWhereHas('category', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('brand', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('type', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
        }

        if ($request->brand && $request->brand != 'Semua') {
            $query->where('brand_id', $request->brand);
        }

        if ($request->category && $request->category != 'Semua') {
            $query->where('category_id', $request->category);
        }

        if ($request->type && $request->type != 'Semua') {
            $query->where('type_id', $request->type);
        }

        if ($request->supplier && $request->supplier != 'Semua') {
            $query->whereHas('product_suppliers', function ($query) use ($request) {
                $query->where('supplier_id', $request->supplier);
            });
        }

        if ($request->status && $request->status != 'Semua' || $request->status == '0') {
            if ($request->status == '0') {
                $query->whereDoesntHave('product_suppliers', function ($query) {
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
                
                $query->whereBetween('transacted_at', [$request->start . ' 00:00:00', $request->end . ' 23:59:59'])
                ->where('status', 'sukses');
            });
        }

        $query->withCount('topup')
        ->orderByDesc('topup_count')
        ->take($request->jumlah);

        return $query;
    }


    // relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }


    public function product_suppliers()
    {
        return $this->hasMany(ProductSupplier::class, 'product_id');
    }

    public function topup()
    {
        return $this->hasMany(Topup::class, 'product_id');
    }

    public function getStatusAttribute()
    {
        $status = true;
        if ($this->product_suppliers->where('status', 1)->count() == 0) {
            $status = false;
        }
        unset($this->product_suppliers);
        return $status;
    }

    public function getMaxPriceAttribute()
    {
        return $this->product_suppliers->max('price');
    }
   
    
    public function getSupplier()
    {
        $suppliers = $this->product_suppliers->filter(function ($supplier) {
            return $supplier->status == 1;
        })->sortBy('price');
        
        $supplierArray = $suppliers->map(function ($supplier) {
            $totalTopup = $supplier->topup_api->count();
            $totalSuccessTopup = $supplier->topup_api->where('status', 'sukses')->count();
            return [
                'id' => $supplier->id,
                'name' => strtoupper($supplier->supplier->name),
                'price' => number_format($supplier->price, 0, ',', '.'),
                'total_topup' => $totalTopup,
                'total_berhasil' => $totalSuccessTopup,
                'persentase_berhasil' => round($totalTopup == 0 ? 0 : ($totalSuccessTopup / $totalTopup) * 100),
            ];
        });
        
        return $supplierArray->values()->toArray();
    }
}
