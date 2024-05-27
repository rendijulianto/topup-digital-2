<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // table name
    protected $table = 'supplier';

    // fillable attrib
    protected $fillable = [
        'nama'
    ];


    public function scopeSearch($query, $request)
    {
        if ($request->q) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }
        return $query;
    }
    
    public function scopePerformance($query, $request)
    {
        $query->withCount([
            'topup_api as total_transaksi' => function ($query) use ($request) {
                if ($request->start && $request->end) {
                    $query->whereBetween('created_at', [$request->start.' 00:00:00', $request->end.' 23:59:59']);
                }
            },
            'topup_api as total_transaksi_berhasil' => function ($query) use ($request) {
                if ($request->start && $request->end) {
                    $query->whereBetween('created_at', [$request->start.' 00:00:00', $request->end.' 23:59:59']);
                }
                $query->where('status', 'sukses');
            }
        ]);
        return $query;
    }

    public function topup_api()
    {
        return $this->hasMany(TopupApi::class, 'supplier_id');
    }
}
