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

// Cuti CRUD Related Routes
Route::post('/form','App\Http\Controllers\FormCutiController@submitCuti')->name('submit-cuti');
Route::get('/report/table/self/delete/{nip}/{no_cuti}',[App\Http\Controllers\FormCutiController::class,'cancelCuti'])->name('report.self.delete');
Route::get('/report/table/self/application/{nip}/{no_cuti}',[App\Http\Controllers\FormCutiController::class,'getCutiApplication'])->name('report.self.app');
Route::get('/report/table/asn/delete/{nip}/{no_cuti}',[App\Http\Controllers\FormCutiController::class,'cancelCuti'])->name('report.asn.delete');
Route::get('/report/table/asn/application/{nip}/{no_cuti}',[App\Http\Controllers\FormCutiController::class,'getCutiApplication'])->name('report.asn.app');
Route::get('/report/table/pjlp/delete/{nip}/{no_cuti}',[App\Http\Controllers\FormCutiController::class,'cancelCuti'])->name('report.pjlp.delete');
Route::get('/report/table/pjlp/application/{nip}/{no_cuti}',[App\Http\Controllers\FormCutiController::class,'getCutiApplication'])->name('report.pjlp.app');
Route::get('/report/table/asn/approval',[App\Http\Controllers\FormCutiController::class,'approvalAction'])->name('report.asn.approval');
Route::get('/report/table/pjlp/approval',[App\Http\Controllers\FormCutiController::class,'approvalAction'])->name('report.pjlp.approval');


// Table Query Routes
Route::get('/kepegawaian/table/asn',[App\Http\Controllers\TabelController::class,'createTableASN'])->name('list.asn');
Route::get('/kepegawaian/table/pjlp',[App\Http\Controllers\TabelController::class,'createTablePJLP'])->name('list.pjlp');
Route::get('/report/table/self',[App\Http\Controllers\TabelController::class,'createTableAssignmentSELF'])->name('report.self');
Route::get('/report/table/asn',[App\Http\Controllers\TabelController::class,'createTableAssignmentASN'])->name('report.asn');
Route::get('/report/table/pjlp',[App\Http\Controllers\TabelController::class,'createTableAssignmentPJLP'])->name('report.pjlp');



// TODO : make route for admin report cuti view
// TODO : organize cuti related route with middleware for protection against snoopers

// Halaman test, utk keperluan test implementasi fungsi
//Route::get('/try','App\Http\Controllers\TabelController@createTablePegawai');
Route::get('/try', function(){ return view('try');});

