<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),
    'address' => env('APP_ADDRESS', 'Jl. Jend. Sudirman No. 1'),	
    'phone' => env('APP_PHONE', '08123456789'),


    'pagination' => [
        'default' => env('APP_PAGINATION_DEFAULT', 10),
        'limit' => env('APP_PAGINATION_LIMIT', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),
    'url_socket' => env('APP_URL_SOCKET', 'http://localhost:3000'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'id_ID',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => 'file',
        // 'store' => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\WebsiteDataServiceProvider::class,
    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
        'Webp' => Buglinjo\LaravelWebp\Facades\Webp::class
    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | DIGIFLAZZ CONFIGURATIONS
    |--------------------------------------------------------------------------
    |
    | This array of configurations for digiflazz
    |
    */
    'digiflazz' => [
        'username' => env('DIGIFLAZZ_USERNAME', 'demo'),
        'api_key' => env('DIGIFLAZZ_API_KEY_DEVELOPMENT', 'demo'),
        'api_key_production' => env('DIGIFLAZZ_API_KEY_PRODUCTION', 'demo'),
        'private_key' => env('DIGIFLAZZ_PRIVATE_KEY', 'demo'),
    ],

    /*
    |--------------------------------------------------------------------------
    | PIWAPI CONFIGURATIONS
    |--------------------------------------------------------------------------
    |
    | This array of configurations for piwapi
    |
    */
    'piwapi' => [
        'api_key' => env('PIWAPI_API_KEY', 'demo'),
        'device_id' => env('PIWAPI_DEVICE_ID', 'demo'),
    ],

    /*
    |--------------------------------------------------------------------------
    | MASA AKTIF VOUCHER
    |--------------------------------------------------------------------------
    |
    | This array of configurations for masa aktif voucher
    |
    */
    'masa_aktif_voucher' => [
        'telkomsel' => env('MASA_AKTIF_VOUCHER_TELKOMSEL', 365),
        'indosat' => env('MASA_AKTIF_VOUCHER_INDOSAT', 365),
        'xl' => env('MASA_AKTIF_VOUCHER_XL', 365),
        'tri' => env('MASA_AKTIF_VOUCHER_TRI', 365),
        'smartfren' => env('MASA_AKTIF_VOUCHER_SMARTFREN', 365),
    ],
];
