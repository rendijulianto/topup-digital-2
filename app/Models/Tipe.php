<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipe extends Model
{
    use HasFactory;

    // table name
    protected $table = 'tipe';

    // fillable attrib
    protected $fillable = [
        'nama'
    ];


    // produk
    public function produk()
    {
        return $this->hasMany(Produk::class, 'tipe_id', 'id');
    }

    public function scopeBrand($query, $brand)
    {
        if ($brand) {
            return $query->whereHas('produk', function ($query) use ($brand) {
                $query->whereHas('brand', function ($query) use ($brand) {
                    $query->where('nama', $brand)->orWhere('id', $brand);
                });
            });
        }
    }

    public function scopeCategory($query, $category)
    {
        if ($category) {
            return $query->whereHas('produk', function ($query) use ($category) {
                $query->whereHas('kategori', function ($query) use ($category) {
                    $query->where('nama', $category)->orWhere('id', $category);
                });
            });
        }
    }

    public function scopeSearch($query, $request)
    {
        if ($request->search) {
            $query = $query->where('nama', 'like', '%' . $request->search . '%');
        }
        return $query;
    }
}
