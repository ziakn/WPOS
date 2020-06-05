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
Route::get('/admindashboard', function () {
    return view('admin.admin');
});



Route::get('/app', function () {
    return view('app.app');
});


Route::get('/logoutuser', 'UserController@logout');

Route::get('/logoutadmin', 'Admin\UserController@logout');



// Route::get('/home', 'HomeController@index')->name('home');


// Route::GET('admin/home','AdminController@index');

Route::GET('admin','Admin\LoginController@showLoginForm')->name('admin.login');
Route::POST('admin','Admin\LoginController@login');
Route::POST('admin-password/email','Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
Route::GET('admin-password/reset','Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
Route::POST('admin-password/reset','Admin\ResetPasswordController@reset');
Route::GET('admin-password/reset/{token}','Admin\ResetPasswordController@showResetForm')->name('admin.password.reset');

Auth::routes();
Route::prefix('/appadmin')->group(function () {
    Route::resource('/faq', 'Admin\FaqController');
    Route::resource('/createadmin', 'Admin\CreateAdminController');
 
});

//Admin
Route::post('/appadmin/createadminupdatepassword', 'Admin\CreateAdminController@updatepassword');
//Admin setting
Route::post('/appadmin/updateUser','Admin\UserController@updateUser');
Route::get('/appadmin/profile', 'Admin\UserController@profile');
Route::post('/appadmin/changepassword', 'Admin\UserController@changePass');
Route::post('/appadmin/updatepassword', 'Admin\UserController@updatepassword');
Route::post('/appadmin/avatar','Admin\UserController@avatar');


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



Route::get('/admindashboard/{slug}', function () {
    return view('admin.admin');
});
Route::get('/admindashboard/{slug}/{sub}', function () {
    return view('admin.admin');
});


// Route::get('/{any}', 'HomeController@index');
// Route::get('/{any}/{slug}', 'HomeController@index');
// Route::get('/{any}/{slug}/{id}', 'HomeController@index');