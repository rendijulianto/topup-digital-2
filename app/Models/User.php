<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model;

class User extends Model
{
    use HasFactory;

    // Your model implementation
    
    protected $table = 'users';

    protected $guarded = ['id'];

    // primary key
    protected $primaryKey = 'id';

    // fillable attrib
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'token'
    ];

    protected $hidden = [
        'kata_sandi',
        'pin'
    ];

    // search
    public function scopeSearch($query, $request)
    {
        if ($request->search){
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('role', 'like', '%' . $request->search . '%');
        }

        // role
        if ($request->has('role') && $request->role != 'Semua') {
            $query->where('role', $request->role);
        }

        return $query;
    }


    public function pin()
    {
        return $this->hasOne(UserPin::class, 'user_id', 'id');
    }
}
