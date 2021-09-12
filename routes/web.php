<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\DB;


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


// helper class untuk menghandle permintaan autentikasi
 Auth::routes([ 'verify' => true]); 

 // Email Verification Routes
//  Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

//  Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();

//     return redirect('/home');
// })->middleware(['auth', 'signed'])->name('verification.verify');

// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();

//     return back()->with('message', 'Link verifikasi telah dikirim!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// Dashboard Routes
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

// Cuti Related Routes
Route::post('/form','App\Http\Controllers\FormCutiController@submitCuti')->name('submit-cuti');

// Table Query Routes
// TODO : benahi route, tambah parameter id untuk pengecekan clearance
Route::get('/kepegawaian/table/asn',[App\Http\Controllers\TabelController::class,'createTableASN'])->name('list.asn');
Route::get('/kepegawaian/table/pjlp',[App\Http\Controllers\TabelController::class,'createTablePJLP'])->name('list.pjlp');

// Halaman test, utk keperluan test implementasi fungsi
Route::get('/try',function(Request $request) {
	
	DB::table('asigment_asn') -> insert([
		'no_cuti' => 2,
		'nip' => '11111',
	]);
	return view('try');
});

