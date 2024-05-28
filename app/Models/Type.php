<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'types';

    protected $fillable = [
        'name'
    ];

    public function scopeSearch($query, $request)
    {
        if ($request->search) {
            $query = $query->where('name', 'like', '%' . $request->search . '%');
        }
        return $query;
    }

    public function scopeBrand($query, $brand)
    {
        if ($brand) {
            return $query->whereHas('product', function ($query) use ($brand) {
                $query->whereHas('brand', function ($query) use ($brand) {
                    $query->where('name', $brand)->orWhere('id', $brand);
                });
            });
        }
    }

    public function scopeCategory($query, $category)
    {
        if ($category) {
            return $query->whereHas('product', function ($query) use ($category) {
                $query->whereHas('category', function ($query) use ($category) {
                    $query->where('name', $category)->orWhere('id', $category);
                });
            });
        }
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'type_id', 'id');
    }
}
