<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // table name
    protected $table = 'kategori';

    // fillable attrib
    protected $fillable = [
        'nama',
        'slug',
    ];

    // scopeSearch
    public function scopeSearch($query, $request)
    {
        if ($request->search) {
            $query = $query->where('nama', 'like', '%' . $request->search . '%');
        }
        return $query;
    }

    

}
