<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model;

class Pengguna extends Model
{
    use HasFactory;

    // Your model implementation
    

    protected $guarded = ['id'];

    public function getAuthIdentifierName()
    {
        return 'id'; // Replace 'id' with the name of your primary key column
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
    

    // table name
    protected $table = 'pengguna';

    // primary key
    protected $primaryKey = 'id';

    // fillable attrib
    protected $fillable = [
        'nama',
        'email',
        'password',
        'jabatan',
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
            $query->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('jabatan', 'like', '%' . $request->search . '%');
        }

        // jabatan
        if ($request->has('jabatan') && $request->jabatan != 'Semua') {
            $query->where('jabatan', $request->jabatan);
        }

        return $query;
    }


    public function pin()
    {
        return $this->hasOne(PenggunaPin::class, 'pengguna_id', 'id');
    }
}
