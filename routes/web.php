<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

/* QR code API */
Route::group([
    'as' => 'qrcode.',
    'prefix' => 'qrcode'
], function ($router) {
    /* Get signature QR code */
    Route::get('/signature/{type}/{code}/{size?}', 'Service\AuthController@qrcodeSignature')->name('signature')->where('code', '^([A-Z0-9_]+\-)?[A-F0-9]{72}$')->where('size', '^[1-9]{1}[0-9]{0,3}$');
});
