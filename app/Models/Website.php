<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $table = 'website';

    protected $fillable = [
        'name',
        'address',
        'telp',
        'logo_website',
        'logo_print',	
    ];

    protected $appends = [
        'logo_website_url',
        'logo_print_url',
    ];

    // get logo website url
    public function getLogoWebsiteUrlAttribute()
    {
        return asset('websites/' . $this->logo_website);
    }

    // get logo print url
    public function getLogoPrintUrlAttribute()
    {
        return asset('websites/' . $this->logo_print);
    }
}
