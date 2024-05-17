<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $('#harga').mask('000.000.000', {reverse: true});
       
        Validator::extend('numeric_rupiah', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $value);
        });

        // pesan
        Validator::replacer('numeric_rupiah', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, ':attribute harus berupa angka dan maksimal 2 digit di belakang koma.');
        });
        Paginator::useBootstrap();
    }
}
