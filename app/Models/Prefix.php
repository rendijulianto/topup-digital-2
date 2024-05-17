<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prefix extends Model
{
    use HasFactory;

    // table name
    protected $table = 'prefix';

    // fillable attrib
    protected $fillable = [
        'brand_id',
        'nomor',
    ];

    // scopeBrand,scopeSearch
    public function scopeBrand($query, $brand_id)
    {
       if ($brand_id) {
           return $query->where('brand_id', $brand_id);
       }
    }

    public function scopeSearch($query, $request)
    {
        if ($request->search) {
             $query->where('nomor', 'like', '%' . $request->search . '%');
        }

        if ($request->brand && $request->brand != 'Semua') {
             $query->where('brand_id', $request->brand);
        }

        return $query;
    }

   

    // relationship
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
