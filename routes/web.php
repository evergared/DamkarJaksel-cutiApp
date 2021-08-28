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

  Route::get('/', function () {
    if(Auth::check())
    {
      return redirect('/home');
    }
      return view('auth/login');
  });



 Auth::routes(); // helper class untuk menghandle permintaan autentikasi

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::get('/kepegawaian','App\Http\Controllers\DashboardController@loadKepegawaian')->name('kepegawaian');
Route::get('/report','App\Http\Controllers\DashboardController@loadReport')->name('report');
Route::get('/form','App\Http\Controllers\DashboardController@loadForm')->name('form');

// TODO : benahi middleware untuk routing, jika database sudah selesai

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade');
	 Route::get('map', function () {return view('pages.maps');})->name('map');
	 Route::get('icons', function () {return view('pages.icons');})->name('icons');
	 Route::get('table-list', function () {return view('pages.tables');})->name('table');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

Route::post('/form','App\Http\Controllers\FormCutiController@submitCutiPegawai')->name('form_cuti');

// route facade utk menampung query tabel
Route::get('/kepegawaian/table/asn',[App\Http\Controllers\PegawaiASNController::class,'createTable'])->name('list.asn');
Route::get('/kepegawaian/table/pjlp',[App\Http\Controllers\PegawaiASNController::class,'createTable'])->name('list.pjlp');

// Halaman test, utk keperluan test implementasi fungsi
Route::get('/try',function() {return view('try');});

