<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banner';

    protected $fillable = [
        'judul',
        'gambar',
        'status',
    ];


    public function scopeSearch($query, $request)
    {
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return $query;
    }

    public function scopeActive($query, $request)
    {
        if ($request->status) {
            $query->where('status', $request->status);
        }
        return $query;
    }
    
}
