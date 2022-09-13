<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::get('locale/{locale}', 'App\Http\Controllers\MainController@changeLocale')->name('locale');
Route::get('currency/{currencyCode}', 'App\Http\Controllers\MainController@changeCurrency')->name('currency');

Route::get('reset', 'App\Http\Controllers\ResetController@reset')->name('reset');

Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('get-logout');

Route::middleware(['set_locale'])->group(function () {
    Route::middleware(['auth'])->group(function () {
     Route::group([
        'prefix' => 'person',
        'namespace' => 'App\Http\Controllers\Person',
        'as' => 'person.',
    ], function () {
        Route::get('/orders', 'OrderController@index')->name('orders.index');
        Route::get('/orders/{order}', 'OrderController@show')->name('orders.show');
    });
 
    Route::group([
        'namespace' => 'App\Http\Controllers\Admin',
        'prefix' => 'admin',
    ], function () {
        Route::group(['middleware' => 'is_admin'], function () {
            Route::get('/orders', 'OrderController@index')->name('home');
            Route::get('/orders/{order}', 'OrderController@show')->name('orders.show');
        });

        Route::resource('categories', 'CategoryController');
        Route::resource('products', 'ProductController');
    });
});


Route::get('/', 'App\Http\Controllers\MainController@index')->name('index');
Route::get('/categories', 'App\Http\Controllers\MainController@categories')->name('categories');
Route::post('subscription/{product}', 'App\Http\Controllers\MainController@subscribe')->name('subscription');


Route::group(['prefix' => 'basket'], function () {
    Route::post('/add/{product}', 'App\Http\Controllers\BasketController@basketAdd')->name('basket-add');

    Route::group([
        'middleware' => 'basket_not_empty',
    ], function () {
        Route::get('/', 'App\Http\Controllers\BasketController@basket')->name('basket');
        Route::get('/place', 'App\Http\Controllers\BasketController@basketPlace')->name('basket-place');
        Route::post('/remove/{product}', 'App\Http\Controllers\BasketController@basketRemove')->name('basket-remove');
        Route::post('/place', 'App\Http\Controllers\BasketController@basketConfirm')->name('basket-confirm');
    });
});


Route::get('/{category}', 'App\Http\Controllers\MainController@category')->name('category');
Route::get('/{category}/{product}', 'App\Http\Controllers\MainController@product')->name('product');
});





