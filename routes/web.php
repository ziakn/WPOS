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

Route::get('/admin', function () {
    return view('admin.admin');
});

Route::get('/app', function () {
    return view('app.app');
});


Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');


Route::GET('admin/home','AdminController@index');

Route::GET('admin','Admin\LoginController@showLoginForm')->name('admin.login');
Route::POST('admin','Admin\LoginController@login');
Route::POST('admin-password/email','Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
Route::GET('admin-password/reset','Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
Route::POST('admin-password/reset','Admin\ResetPasswordController@reset');
Route::GET('admin-password/reset/{token}','Admin\ResetPasswordController@showResetForm')->name('admin.password.reset');








Route::group(['as' => 'frontend.'], function() {

    Route::get('/', 'Frontend\FrontendController@home')
        ->name('home');

    Route::get('/features', 'Frontend\FrontendController@features')
        ->name('features');

    Route::get('/pricing', 'Frontend\FrontendController@pricing')
        ->name('pricing');

    Route::get('/faqs', 'Frontend\FrontendController@faqs')
        ->name('faqs');

    Route::get('/privacy', 'Frontend\FrontendController@privacy')
        ->name('privacy');

    Route::get('/terms', 'Frontend\FrontendController@terms')
        ->name('terms');

    Route::get('/about', 'Frontend\FrontendController@about')
        ->name('about');
});