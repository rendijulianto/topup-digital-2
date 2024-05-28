<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function scopeSearch($query, $request)
    {
        if ($request->search) {
            $query = $query->where('name', 'like', '%' . $request->search . '%');
        }
        return $query;
    }
}
