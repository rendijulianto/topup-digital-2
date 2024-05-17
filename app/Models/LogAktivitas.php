<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'pengguna_id',
        'ip',
        'keterangan',
        'user_agent',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    // scopeSearch
    public function scopeSearch($query, $request)
    {
        if ($request->search) {
            $query->where('keterangan', 'like', '%' . $request->search . '%');
        }

        return $query;
    }

    // observe
    
}
