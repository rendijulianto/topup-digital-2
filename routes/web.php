<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/login', 'AuthController@login')->name('login');
// postLogin
Route::post('/login', 'AuthController@postLogin')->name('postLogin');
// logout
Route::get('/logout', 'AuthController@logout')->name('logout');
// forgotPassword
Route::get('/forgotPassword', 'AuthController@forgotPassword')->name('forgotPassword');
Route::post('/forgotPassword', 'AuthController@postForgotPassword')->name('postForgotPassword');
Route::get('/verifyResetPassword/{token}', 'AuthController@verifyResetPassword')->name('verifyResetPassword');

Route::post('update-topup-massal', 'Cron\TopupController@index')->name('update-topup-massal');

Route::get('/profile', 'UserController@profile')->name('profile')->middleware('role:admin,injector,kasir');
Route::put('/profile', 'UserController@updateProfile')->name('profile.update')->middleware('role:admin,injector,kasir');

// route admin
Route::group(['prefix' => '/admin', 'middleware' => ['role:admin'], 'as' => 'admin.'], function () {
    Route::get('dashboard', 'DashboardController@admin')->name('dashboard');
    Route::get('suppliers/performance', 'SupplierController@report')->name('suppliers.performance');
    Route::put('banners/{banner}/updateStatus', 'BannerController@updateStatus')->name('banners.updateStatus');
    Route::get('products/edit-masal', 'ProductController@editMasal')->name('products.edit-masal');
    Route::put('products/edit-masal', 'ProductController@updateMasal')->name('products.update-masal');
    Route::get('logs/cek-nama', 'ActivityLogController@cekNama')->name('logs.cek-nama');
    Route::delete('logs/cek-nama/{id}', 'ActivityLogController@destroyCekNama')->name('logs.cek-nama.destroy');
    Route::get('logs/cek-voucher', 'ActivityLogController@cekVoucher')->name('logs.cek-voucher');
    Route::get('logs', 'ActivityLogController@index')->name('logs.index');
    Route::get('website', 'WebsiteController@index')->name('website');
    Route::resource('brands', 'BrandController');
    Route::resource('banners', 'BannerController');
    Route::resource('categories', 'CategoryController');
    Route::resource('suppliers', 'SupplierController');
    Route::resource('products', 'ProductController');
    Route::resource('types', 'TypeController');
    Route::resource('prefixes', 'PrefixController');
    Route::resource('users', 'UserController');
    Route::resource('topups', 'TopupController');
    Route::resource('vouchers', 'VoucherController');
});

Route::group(['prefix' => '/cashier', 'middleware' => ['role:kasir'], 'as' => 'cashier.'], function () {
    Route::get('dashboard', 'DashboardController@cashier')->name('dashboard');
    Route::get('topup', 'TopupController@cashier')->name('topup');
    Route::get('storeApi', 'TopupController@storeApi')->name('topup.storeApi');
    Route::get('topup/cancel', 'TopupController@cancel')->name('topup.cancel');
    Route::get('topup/{topup}', 'TopupController@show')->name('topup.show');
    Route::get('topup/{topup}/edit', 'TopupController@edit')->name('topup.edit');
    Route::get('topup/{topup}/print', 'TopupController@print')->name('topup.print');
    Route::get('voucher', 'VoucherController@cashier')->name('voucher');
    Route::get('vouchers/create', 'VoucherController@create')->name('vouchers.create');
    Route::post('vouchers', 'VoucherController@store')->name('vouchers.store');
    Route::resource('voucher-kosong', 'VoucherKosongController');
    Route::get('/{topup}/check', 'Api\TopupController@checkStatus')->name('topups.check.status');
});

// injector
Route::group(['prefix' => '/injector', 'middleware' => ['role:injector'], 'as' => 'injector.'], function () {
    Route::get('dashboard', 'DashboardController@injector')->name('dashboard');
    Route::get('vouchers', 'VoucherController@injector')->name('vouchers.index');
    Route::get('vouchers/create', 'VoucherController@create')->name('vouchers.create');
});

// guest
Route::group(['middleware' => ['role:kasir']], function () {
    Route::get('/', 'DashboardController@customer')->name('dashboard');
    Route::get('/topup', 'TopupController@index')->name('topup.index');
    Route::post('/topup', 'TopupController@store')->name('topup.store');
    Route::get('/topup/{category}/create', 'TopupController@create')->name('topup.create');
    Route::get('/topup/{id}/detail', 'TopupController@detail')->name('topup.detail');
    Route::get('/topup/{category}/create/{brand}', 'TopupController@create')->name('topup.create.brand');
    Route::get('/produk', 'ProductController@list')->name('produk.list');
    Route::get('/voucher', 'TopupController@voucher')->name('voucher');
    Route::get('/voucher/{brand}', 'TopupController@voucherBrand')->name('voucher.brand');
});


// route cron 
Route::prefix('cron')->group(function () {
    Route::get('update-produk', 'Cron\ProductController@update')->name('cron.update-produk');
    Route::get('update-produk-status', 'Cron\ProductController@updateStatus')->name('cron.update-produk-status');
    Route::get('update-topup', 'Cron\TopupController@update')->name('cron.update-topup');
});


// route api
Route::group(['prefix' => '/api', 'as' => 'api.'], function () {
    // add pelanggan
    Route::post('/pelanggan', 'Api\CustomerController@store')->name('customer.store');

    Route::group(['prefix' => '/digiflazz'], function () {
        Route::get('profile', 'Api\CheckController@profile')->name('digiflazz.profile');
    });
    Route::group(['prefix' => '/check'], function () {
        Route::post('e-wallet', 'Api\CheckController@eWallet')->name('check.e-wallet');
        Route::post('pln', 'Api\CheckController@pln')->name('check.pln');
        Route::post('voucher', 'Api\CheckController@voucher')->name('check.voucher');
    });

    // cek products
    Route::group(['prefix' => '/products'], function () {
        Route::get('/pln', 'Api\ProductController@getPLN')->name('products.pln');
        Route::get('/e-wallet', 'Api\ProductController@getEwalletByBrand')->name('products.e-wallet');
        Route::get('/seluler', 'Api\ProductController@getSeluler')->name('products.seluler');
        Route::get('/best-seller', 'Api\ProductController@getBestSeller')->name('products.best-seller');
        Route::get('/aktivasi-voucher', 'Api\ProductController@getActivationVoucher')->name('products.aktivasi-voucher');
        Route::get('/voucher-fisik', 'Api\ProductController@getVoucherFisikByBrand')->name('products.voucher-fisik');
        Route::get('/category-brand-type', 'Api\ProductController@categoryBrandType')->name('products.category-brand-type');
        Route::get('/{product}/supplier', 'Api\ProductController@getSupplierProduct')->name('products.supplier');
        Route::get('/{product}/selling-price', 'Api\ProductController@sellingPrice')->name('products.selling_price');
        Route::get('/{product}', 'Api\ProductController@show')->name('products.show');
        // categoryBrandType
    });

    // brand
    Route::group(['prefix' => '/brands'], function () {
        Route::get('/category', 'Api\BrandController@getByCategory')->name('brands.category');
        Route::get('/voucher', 'Api\BrandController@voucher')->name('brands.voucher');
    });
    
    // cek types
    Route::group(['prefix' => '/types'], function () {
        Route::get('/category-prefix', 'Api\TypeController@getByCategoryPrefix')->name('types.category-prefix');
        Route::get('/category-brand', 'Api\TypeController@getBycategoryBrand')->name('types.category-brand');
    });
    
    Route::group(['prefix' => '/topups'], function () {
        Route::post('/', 'Api\TopupController@store')->name('topups.store');
        Route::get('e-money/check-post-paid', 'Api\TopupController@checkPostPaid')->name('topups.checkPostPaid');
        Route::get('e-money/pay-post-paid', 'Api\TopupController@storePostPaid')->name('topups.storePostPaid');
        Route::middleware(['role:admin'])->group(function () {
            Route::put('/{topup}/update-status', 'Api\TopupController@updateStatus')->name('topups.updateStatus');
            Route::put('/{topup}/update-api-status', 'Api\TopupController@updateApiStatus')->name('topups.updateApiStatus');
        });

        Route::middleware(['role:kasir,admin,injector'])->group(function () {
            Route::post('/voucher', 'Api\TopupController@storeVoucher')->name('topups.storeVoucher');
            Route::get('/{topup}', 'Api\TopupController@show')->name('topups.show');
            Route::get('/{topup}/supplier', 'Api\TopupController@supplier')->name('topups.supplier');
            Route::get('/{topup}/check', 'Api\TopupController@checkStatus')->name('topups.check.status');
            Route::get('/{topup}/cancel', 'Api\TopupController@cancel')->name('topups.cancel');
            Route::get('/{topup}/print', 'Api\TopupController@print')->name('topups.print');
            Route::post('/{topup}/storeTopup', 'Api\TopupController@storeTopup')->name('topups.storeTopup');
            Route::get('/{target}/voucher', 'Api\TopupController@voucher')->name('topups.voucher');
            Route::post('/{topup}/voucher', 'Api\TopupController@sellVoucher')->name('topups.sellVoucher');
        });

    });
});



