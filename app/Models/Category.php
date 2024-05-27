<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'slug',
    ];

    public function scopeSearch($query, $request)
    {
        if ($request->search) {
            $query = $query->where('nama', 'like', '%' . $request->search . '%');
        }
        return $query;
    }
}
