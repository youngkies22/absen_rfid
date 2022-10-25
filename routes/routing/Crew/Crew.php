<?php

use App\Http\Controllers\Admin\CadminAkun;
use App\Http\Controllers\UploadFotoController;


//Router untuk User admin ---------------------------------------------------
	// 1.Router Bagian Admin
	Route::group(['prefix' => 'crew', 'middleware' => ['auth:admin']], function(){
		Route::get('/', function () {
				return redirect('/crew/home');
		});
		Route::get('/home', 'Admin\CadminHome@index')->name('homeAdmin');
		Route::get('/list-akun-admin', [CadminAkun::class, 'listAkunAdmin'])->name('list.akun.admin');
		Route::get('/add-akun-admin', [CadminAkun::class, 'add'])->name('add.akun.admin');
		Route::get('/edit-akun-admin/{id}', [CadminAkun::class, 'edit'])->name('edit.akun.admin');
		Route::post('/insert-akun-admin', [CadminAkun::class, 'insertAdmin'])->name('insert.akun.admin');
		Route::put('/update-akun-admin', [CadminAkun::class, 'updateAkunAdmin'])->name('update.akun.admin');
		Route::put('/reset-password-admin', [CadminAkun::class, 'ResetPassword'])->name('reset.password.admin');
		Route::put('/delete-admin', [CadminAkun::class, 'DeleteAdmin'])->name('delete.admin');

		//Bagian akun profile admin ---------------------------------------------------------------------------------
			Route::get('/lihat-profile', [CadminAkun::class, 'index'])->name('profile.admin');
			Route::put('/update-profile/{id}',[CadminAkun::class, 'UpdateAdmin']);
			
		//Bagian Upload Foto ---------------------------------------------------------------------------------
			Route::post('/upload-foto-admin', [UploadFotoController::class, 'UploadFotoAdmin'])->name('upload.foto.admin');
			Route::post('/upload-foto-guru', [UploadFotoController::class, 'UploadFotoGuru'])->name('upload.foto.guru');
			
		//Bagian sekolah ----------------------------------------------------------------------------------------------
			Route::get('/lihat-sekolah', 'Admin\CadminSekolah@lihatSekolah')->name('lihat.sekolah');
			Route::get('/json-sekolah', 'Admin\CadminSekolah@jsonSekolah')->name('json.sekolah');
			Route::get('{id}/edit-sekolah','Admin\CadminSekolah@edit')->name('edit.sekolah');
			Route::put('/update-sekolah/{id}','Admin\CadminSekolah@UpdateSekolah');

		//tahun ajaran ------------------------------------------------------------------------------------------------
			Route::get('/lihat-tahun-ajaran', 'Admin\CadminSekolah@LihatTahunAjaran')->name('lihat.tahun.ajaran');
			Route::post('/insert-tahun-ajaran', 'Admin\CadminSekolah@InsertTahunAjaran')->name('insert.tahun.ajaran');
			Route::post('/update-tahun-ajaran', 'Admin\CadminSekolah@UpdateTahunAjaran')->name('update.tahun.ajaran');

		//semester ------------------------------------------------------------------------------------------------
			Route::get('/lihat-semester', 'Admin\CadminSekolah@LihatSemester')->name('lihat.semester');
			Route::post('/insert-semester', 'Admin\CadminSekolah@InsertSemester')->name('insert.semester');
			Route::post('/update-semester', 'Admin\CadminSekolah@UpdateSemester')->name('update.semester');


		//Bagian guru ------------------------------------------------------------------------------------------------
			Route::get('/lihat-guru', 'Admin\CadminGuru@lihatGuru')->name('lihat.guru');
			Route::get('/form-import-guru', 'Admin\CadminGuru@formImportSiswa')->name('form.import.guru');
			Route::post('/import-data-guru', 'Admin\CadminGuru@ImportDataGuru')->name('import.data.guru');
			Route::get('/json-guru', 'Admin\CadminGuru@jsonGuru')->name('json.guru');
			Route::get('/addguru', 'Admin\CadminGuru@add')->name('add.guru');
			Route::get('{id}/edit-guru','Admin\CadminGuru@editGuru')->name('edit.guru');
			Route::post('/insertguru', 'Admin\CadminGuru@InsertGuru')->name('insert.guru');
			Route::put('/update-guru/{id}','Admin\CadminGuru@UpdateGuru');
			Route::put('/{id}/deleteguru','Admin\CadminGuru@deleteGuru');
			

		
			
		//Bagina Import Absen Finger -------------------------------------------------------------------------------------
			
			Route::get('/lihat-absen-finger', 'Admin\CadminAbsenFinger@LihatAbsenFinger')->name('lihat.absen.finger');
			Route::get('/json-absen-finger', 'Admin\CadminAbsenFinger@jsonAbsenFinger')->name('json.absen.finger');
			Route::get('/add-absen-finger', 'Admin\CadminAbsenFinger@add')->name('add.absen.finger');
			
			
			Route::post('/insert-absen-finger', 'Admin\CadminAbsenFinger@InsertAbsenFinger')->name('insert.absen.finger');
			Route::post('/update-absen-finger','Admin\CadminAbsenFinger@UpdateAbsenFinger')->name('update.absen.finger');
			Route::put('/{id}/delete-absen-finger','Admin\CadminAbsenFinger@DeleteAbsenFinger');

			//rekap absen sekolah siswa atau rekap absen fingerprint 02-08-2021 -----------------------------------------------------
			Route::get('/view-rekap-absen-finger', 'Admin\CadminAbsenFinger@ViewRekapAbsenFinger')->name('view.rekap.absen.finger');
			Route::get('/cetak-view-rekap-absen-finger', 'Admin\CadminAbsenFinger@CetakViewRekapAbsenFinger')->name('cetak.view.rekap.absen.finger');

			#23-10-2022
			Route::get('/view-rekap-absen-rentang', 'Admin\CadminAbsenFinger@ViewRekapAbsenRentang')->name('view.rekap.absen.rentang');
			Route::get('/cetak-view-rekap-absen-rentang', 'Admin\CadminAbsenFinger@CetakViewRekapAbsenRentang')->name('cetak.view.rekap.absen.rentang');

	

		//Absensi Usere----------------------------------------------------------------------------------------------
			Route::get('/lihat-absen-finger', 'Admin\CadminAbsenFinger@LihatAbsenFinger')->name('lihat.absen.finger');
			Route::get('/json-absen-finger', 'Admin\CadminAbsenFinger@jsonAbsenFinger')->name('json.absen.finger');
			Route::get('/add-absen-finger', 'Admin\CadminAbsenFinger@add')->name('add.absen.finger');

			Route::get('/json-absen-finger-live', 'Admin\CadminAbsenFinger@jsonAbsenFingerLive')->name('json.absen.finger.live');



	
	}); //end route admin

//End Router untuk User admin ------------------------------------------------