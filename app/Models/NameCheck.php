<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NameCheck extends Model
{
    use HasFactory;

    protected $table = 'name_checks';

    protected $fillable = [
        'ref_id',
        'brand_id',
        'target',
        'name',
        'status',
    ];
}
