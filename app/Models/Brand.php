<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $fillable = [
        'name',
        'logo',
    ];

    protected $appends = [
        'logo_url',
    ];

    public function scopeCategory($query, $category)
    {
        return $query->whereHas('produk', function ($query) use ($category) {
            $query->whereHas('category', function ($query) use ($category) {
                $query->where('name', $category)->orWhere('id', $category);
            });
        });
    }

    public function scopeSearch($query, $request)
    {
        return $query->when($request->search, function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        });
    }

    public function produk()
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }

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
