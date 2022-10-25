<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\CadminAbsenFinger;

/*
|--------------
| List Router
| @mryes | https://web.facebook.com/youngkq
| Way Jepara Lampung Timur
|--------------

*/


//login admin
Route::get('/sekolahku', [LoginController::class, 'getLoginAdmin'])->name('login');
Route::post('/login', [LoginController::class, 'CekLogin'])->name('ceklogin');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', [LoginController::class, 'getLogin'])->name('login.user');


Route::get('/phpinfo',function (){ phpinfo(); });

#live absen guru dari jurnal
Route::controller(CadminAbsenFinger::class)->group(function () {
	Route::get('/json/json-live-absen-guru-monitor/{id}', 'jsonAbsenFingerLiveMonitor');
	Route::get('/form-live', 'formLive')->name('form.live');
	
});



#live absen guru dari jurnal



//Router untuk User admin ---------------------------------------------------
	// 1.Router Bagian Admin
	include __DIR__.'/routing/Crew/Crew.php';

//End Router untuk User admin ------------------------------------------------


// Route Koneksi Get Api Dapodik ---------------------------------------------
include __DIR__.'/routing/Arduino/Arduino.php';
//End Route Koneksi Get Api Dapodik-------------------------------------------