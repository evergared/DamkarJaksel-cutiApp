<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Calendars\DisableCutiManual;
use Illuminate\Support\Facades\Auth;


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
Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');
Route::get('/kepegawaian','App\Http\Controllers\DashboardController@loadKepegawaian')->name('kepegawaian')->middleware('auth');
Route::get('/report','App\Http\Controllers\DashboardController@loadReport')->name('report')->middleware('auth');
Route::get('/form','App\Http\Controllers\DashboardController@loadForm')->name('form')->middleware('auth');
Route::get('/pengguna','App\Http\Controllers\DashboardController@loadUser')->name('pengguna')->middleware('auth');


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
Route::post('/form/create','App\Http\Controllers\FormCutiController@submitCuti')->name('submit-cuti');
Route::patch('/form/update','App\Http\Controllers\FormCutiController@modifyCuti')->name('modify-cuti');
Route::get('/report/table/self/delete/{nip}/{no_cuti}',[App\Http\Controllers\FormCutiController::class,'cancelCuti'])->name('report.self.delete');
Route::get('/report/table/self/application/{nip}/{no_cuti}',[App\Http\Controllers\FormCutiController::class,'getCutiApplication'])->name('report.self.app');
Route::get('/report/table/asn/delete/{nip}/{no_cuti}',[App\Http\Controllers\FormCutiController::class,'cancelCuti'])->name('report.asn.delete');
Route::get('/report/table/asn/application/{nip}/{no_cuti}',[App\Http\Controllers\FormCutiController::class,'getCutiApplication'])->name('report.asn.app');
Route::get('/report/table/pjlp/delete/{nip}/{no_cuti}',[App\Http\Controllers\FormCutiController::class,'cancelCuti'])->name('report.pjlp.delete');
Route::get('/report/table/pjlp/application/{nip}/{no_cuti}',[App\Http\Controllers\FormCutiController::class,'getCutiApplication'])->name('report.pjlp.app');
Route::get('/report/table/asn/approval',[App\Http\Controllers\FormCutiController::class,'approvalAction'])->name('report.asn.approval');
Route::get('/report/table/pjlp/approval',[App\Http\Controllers\FormCutiController::class,'approvalAction'])->name('report.pjlp.approval');

Route::post('/data-cuti/delete','App\Http\Controllers\FormCutiController@cancelCuti')->name('delete-cuti');
Route::post('/data-cuti/approval/fetch','App\Http\Controllers\FormCutiController@approvalStatus')->name('get-approval');
Route::post('/data-cuti/approval/action',[App\Http\Controllers\FormCutiController::class,'approvalAction'])->name('action-approval');
Route::post('/form/print',[App\Http\Controllers\FormCutiController::class,'getCutiApplication'])->name('form.print');
Route::get('/print',[App\Http\Controllers\FormCutiController::class,'testDocument']);

Route::get('/admin/list-penempatan',[App\Http\Controllers\UserController::class,'getArrayPenempatan']);
Route::post('/admin/action/add-user',[App\Http\Controllers\UserController::class,'addUser']);
Route::patch('/user/action/change-password',[App\Http\Controllers\UserController::class,'changePassword']);

// Table Query Routes
Route::get('/kepegawaian/table/asn',[App\Http\Controllers\TabelController::class,'createTableASN'])->name('list.asn');
Route::get('/kepegawaian/table/pjlp',[App\Http\Controllers\TabelController::class,'createTablePJLP'])->name('list.pjlp');
Route::get('/report/table/self',[App\Http\Controllers\TabelController::class,'createTableAssignmentSELF'])->name('report.self');
Route::get('/report/table/asn',[App\Http\Controllers\TabelController::class,'createTableAssignmentASN'])->name('report.asn');
Route::get('/report/table/pjlp',[App\Http\Controllers\TabelController::class,'createTableAssignmentPJLP'])->name('report.pjlp');
Route::get('/user/list',[App\Http\Controllers\TabelController::class,'createTableUser']);

// Admin Calendar Routes
// Route::get('/calendar',function(){
// 	Route::get('/calendar','App\Http\Controllers\DashboardController@loadCalendar')->name('calendar');
// 	Route::get('/calendar/array',[App\Http\Controllers\CalendarController::class,'index']);
// 	Route::get('/calendar/json',[App\Http\Controllers\CalendarController::class,'fetchJson']);
// 	Route::get('/calendar/update',[App\Http\Controllers\CalendarController::class,'updateEvent']);
// 	Route::get("/calendar/libur",[App\Http\Controllers\CalendarController::class,"fetchLibur"]);
// 	Route::post('/calendar/create',[App\Http\Controllers\CalendarController::class,'createEvent']);
// 	Route::post('/calendar/update',[App\Http\Controllers\CalendarController::class,'updateEvent']);
// 	Route::delete('/calendar/delete/{calId}/{eventId}',[App\Http\Controllers\CalendarController::class,'deleteEvent']);
// })->middleware('admin');

	Route::get('/calendar','App\Http\Controllers\DashboardController@loadCalendar')->name('calendar');
	Route::get('/calendar/array',[App\Http\Controllers\CalendarController::class,'index'])->name('calendarArray');
	Route::get('/calendar/json',[App\Http\Controllers\CalendarController::class,'fetchJson']);
	Route::get('/calendar/update',[App\Http\Controllers\CalendarController::class,'updateEvent']);
	Route::get("/calendar/libur",[App\Http\Controllers\CalendarController::class,"fetchLibur"]);
	Route::post('/calendar/create',[App\Http\Controllers\CalendarController::class,'createEvent']);
	Route::post('/calendar/update',[App\Http\Controllers\CalendarController::class,'updateEvent']);
	Route::delete('/calendar/delete/{calId}/{eventId}',[App\Http\Controllers\CalendarController::class,'deleteEvent']);



// TODO : make route for admin report cuti view
// TODO : organize cuti related route with middleware for protection against snoopers

// Halaman test, utk keperluan test implementasi fungsi
//Route::get('/try','App\Http\Controllers\TabelController@createTablePegawai');
Route::get('/try', function(){ 

	return dd((array)App\Models\User::get());
	
	//return dd($test->fetchEvents());
	//return dd(implode("|",$test->extractDatesAsArray()));
	//return dd(Auth::user()->data);
});

Route::get('/test/{test1}/{test2}','App\Http\Controllers\FormCutiController@approvalStatus');

