<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Calendars\DisableCutiManual;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TabelController;
use App\Http\Controllers\FormCutiController;


Route::get('/', function () {
    if(Auth::check())
    {
      return redirect('/home');
    }
      return view('auth/login');
  });

  Auth::routes([ 'verify' => true]);
  
// TODO : middleware for admin related routes
Route::group(['middleware' => 'auth'], function () {

	// Dashboard Routes
	Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
	Route::get('/kepegawaian','App\Http\Controllers\DashboardController@loadKepegawaian')->name('kepegawaian');
	Route::get('/report','App\Http\Controllers\DashboardController@loadReport')->name('report');
	Route::get('/report-plt','App\Http\Controllers\DashboardController@loadPltReport')->name('plt');
	Route::get('/form','App\Http\Controllers\DashboardController@loadForm')->name('form');
	Route::get('/pengguna','App\Http\Controllers\DashboardController@loadUser')->name('pengguna');
	Route::get('/pengumuman/create','App\Http\Controllers\DashboardController@createPengumuman')->name('pengumuman.create');
	Route::get('/pengumuman/list','App\Http\Controllers\DashboardController@listPengumuman')->name('pengumuman.list');


	// Pengumuman Routes
	Route::post('/pengumuman/action/add-new-pengumuman',[App\Http\Controllers\PengumumanController::class,'addPengumuman'])->name('add-pengumuman');


	// Calendar Routes
	Route::get('/calendar','App\Http\Controllers\DashboardController@loadCalendar')->name('calendar');
	Route::get('/calendar/array',[App\Http\Controllers\CalendarController::class,'index'])->name('calendarArray');
	Route::get('/calendar/json',[App\Http\Controllers\CalendarController::class,'fetchJson']);
	Route::get('/calendar/update',[App\Http\Controllers\CalendarController::class,'updateEvent']);
	Route::get("/calendar/libur",[App\Http\Controllers\CalendarController::class,"fetchLibur"]);
	Route::post('/calendar/create',[App\Http\Controllers\CalendarController::class,'createEvent']);
	Route::post('/calendar/update',[App\Http\Controllers\CalendarController::class,'updateEvent']);
	Route::delete('/calendar/delete/{calId}/{eventId}',[App\Http\Controllers\CalendarController::class,'deleteEvent']);



	// Table Data Fetch
	Route::get('/kepegawaian/table/master',[App\Http\Controllers\TabelController::class,'createTableMaster'])->name('list.master');
	Route::get('/kepegawaian/table/asn',[App\Http\Controllers\TabelController::class,'createTableASN'])->name('list.asn');
	Route::get('/kepegawaian/table/pjlp',[App\Http\Controllers\TabelController::class,'createTablePJLP'])->name('list.pjlp');
	Route::get('/report/table/self',[App\Http\Controllers\TabelController::class,'createTableAssignmentSELF'])->name('report.self');
	Route::get('/report/table/asn',[App\Http\Controllers\TabelController::class,'createTableAssignmentASN'])->name('report.asn');
	Route::get('/report/table/pjlp',[App\Http\Controllers\TabelController::class,'createTableAssignmentPJLP'])->name('report.pjlp');
	Route::get('/report/table/plt/asn',[App\Http\Controllers\TabelController::class,'createTablePLTAssignmentASN'])->name('report.plt.asn');
	Route::get('/report/table/plt/pjlp',[App\Http\Controllers\TabelController::class,'createTablePLTAssignmentPJLP'])->name('report.plt.pjlp');
	Route::get('/user/list',[App\Http\Controllers\TabelController::class,'createTableUser'])->name('list.user');
	Route::get('/jabatan/list',[App\Http\Controllers\TabelController::class,'createTableJabatan'])->name('list.jabatan');
	Route::get('/penempatan/list',[App\Http\Controllers\TabelController::class,'createTablePenempatan'])->name('list.penempatan');
	Route::get('/plt/list',[App\Http\Controllers\TabelController::class,'createTablePLT'])->name('list.plt');


	// CRUD
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
	Route::get('/report/table/plt/approval',[App\Http\Controllers\FormCutiController::class,'approvalActionPLT'])->name('report.plt.approval');
	
	Route::post('/data-cuti/delete','App\Http\Controllers\FormCutiController@cancelCuti')->name('delete-cuti');
	Route::post('/data-cuti/approval/fetch','App\Http\Controllers\FormCutiController@approvalStatus')->name('get-approval');
	Route::post('/data-cuti/approval/fetch-plt','App\Http\Controllers\FormCutiController@approvalStatusPLT')->name('get-approval-plt');
	Route::post('/data-cuti/approval/action',[App\Http\Controllers\FormCutiController::class,'approvalAction'])->name('action-approval');
	Route::post('/data-cuti/approval/plt-action',[App\Http\Controllers\FormCutiController::class,'approvalActionPLT'])->name('action-approval-plt');
	Route::post('/form/print',[App\Http\Controllers\FormCutiController::class,'getCutiApplication'])->name('form.print');
	Route::get('/print',[App\Http\Controllers\FormCutiController::class,'testDocument']);
	
	Route::get('/admin/list-penempatan',[App\Http\Controllers\UserController::class,'getArrayPenempatan']);
	Route::get('/admin/list-jabatan',[App\Http\Controllers\JabatanController::class,'getArrayJabatan']);
	Route::get('/admin/list-ppk',[App\Http\Controllers\JabatanController::class,'getAssignedPPKandPPTK']);
	Route::post('admin/action/assign-ppk-pptk',[App\Http\Controllers\JabatanController::class,'setAssignedPPKandPPTK']);
	Route::post('/admin/action/add-user',[App\Http\Controllers\UserController::class,'addUser']);
	Route::post('/admin/action/delete-user',[App\Http\Controllers\UserController::class,'deleteUser']);
	Route::patch('/admin/action/change-level',[App\Http\Controllers\UserController::class,'changeLevel']);
	Route::post('/admin/action/add-penempatan',[App\Http\Controllers\PenempatanController::class,'addPenempatan']);
	Route::post('/admin/action/delete-penempatan',[App\Http\Controllers\PenempatanController::class,'deletePenempatan']);
	Route::post('/admin/action/add-jabatan',[App\Http\Controllers\JabatanController::class,'addJabatan']);
	Route::post('/admin/action/delete-jabatan',[App\Http\Controllers\JabatanController::class,'deleteJabatan']);
	Route::post('/admin/action/add-plt',[App\Http\Controllers\JabatanController::class,'addPLT']);
	Route::post('/admin/action/delete-plt',[App\Http\Controllers\JabatanController::class,'deletePLT']);

	Route::post('/admin/action/verify-nip',[App\Http\Controllers\PegawaiController::class,'checkNip']);
	Route::post('/admin/action/verify-nrk',[App\Http\Controllers\PegawaiController::class,'checkNrk']);
	Route::post('admin/action/f',[App\Http\Controllers\PegawaiController::class,'fetchByNip']);
	Route::post('admin/action/fn',[App\Http\Controllers\PegawaiController::class,'fetchByNrk']);
	Route::post('/admin/action/add-pegawai',[App\Http\Controllers\PegawaiController::class,'addSinglePegawai']);
	Route::post('/admin/action/update-pegawai',[App\Http\Controllers\PegawaiController::class,'updateSinglePegawai']);
	Route::post('/admin/action/delete-pegawai',[App\Http\Controllers\PegawaiController::class,'deleteSinglePegawai']);

	Route::patch('/user/action/change-password',[App\Http\Controllers\UserController::class,'changePassword']);

  });


?>