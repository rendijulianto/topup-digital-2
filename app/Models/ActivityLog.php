<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'ip',
        'note',
        'user_agent',
    ];

    public function scopeSearch($query, $request)
    {
        if ($request->search) {
            $query->where('note', 'like', '%' . $request->search . '%');
        }

        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
