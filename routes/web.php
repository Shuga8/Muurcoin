<?php

use App\Http\Controllers\Api\CoinmarketcapApi;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signupmail', function () {
    return view('mail.UserRegistrationMail');
});


Route::get('/coinmarketapi-test/{symbol}', [CoinmarketcapApi::class, 'fetchSymbolPriceUsd']);
