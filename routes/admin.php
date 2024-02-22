<?php


use Illuminate\Support\Facades\Route;

Route::namespace('Admin')->name('admin.')->group(function () {

    Route::controller('AuthController')->prefix('auth')->group(function () {
        Route::get('login', 'index')->name('login')->middleware('admin.guest');
        Route::post('/', 'auth')->name('login.auth')->middleware('admin.guest');
        // Logout function
        Route::get('logout', 'logout')->name('logout')->middleware('admin');
    });

    Route::middleware('admin')->group(function () {

        Route::controller('AdminController')->group(function () {
            Route::get('dashboard', 'index')->name('dashboard');
        });

        Route::controller('ManageUsersController')->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'allUsers')->name('all');
            Route::get('/active', 'activeUsers')->name('active');
            Route::get('/unverified-email', 'unverifiedEmailUsers')->name('email.unverified');
            Route::get('/verified-email', 'verifiedEmailUsers')->name('email.verified');
            Route::get('/banned', 'bannedUsers')->name('banned');

            Route::get('detail/{id}', 'detail')->name('detail');
        });
    });
});
