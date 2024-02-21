<?php


use Illuminate\Support\Facades\Route;

Route::namespace('Admin')->name('admin.')->group(function () {

    Route::controller('AuthController')->prefix('auth')->group(function () {
        Route::get('/', 'index')->name('login')->middleware('admin.guest');
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
        });
    });
});
