<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    // table name
    protected $table = 'brand';

    // fillable attrib
    protected $fillable = [
        'nama',
        'logo',
    ];

    // append
    protected $appends = [
        'logo_url',
    ];

    // scopeCategory
    public function scopeCategory($query, $category)
    {
        return $query->whereHas('produk', function ($query) use ($category) {
            $query->whereHas('kategori', function ($query) use ($category) {
                $query->where('nama', $category)->orWhere('id', $category);
            });
        });
    }

    // scopeSearch
    public function scopeSearch($query, $request)
    {
        return $query->when($request->search, function ($query) use ($request) {
            return $query->where('nama', 'like', '%' . $request->search . '%');
        });
    }



    public function produk()
    {
        return $this->hasMany(Produk::class, 'brand_id', 'id');
    }

    // topup
    public function topup()
    {
        return $this->hasMany(Topup::class, 'brand_id', 'id');
    }

    public function getLogoUrlAttribute()
    {
      
        // if($this->logo && file_exists(public_path('brands/' . $this->logo))) {
        if($this->logo && file_exists('brands/' . $this->logo)) {
            return asset('brands/' . $this->logo);
        } else {
            return asset('assets/images/default.jpg');
        }
    }
}
