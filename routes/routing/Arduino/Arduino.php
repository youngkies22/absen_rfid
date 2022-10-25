<?php

use App\Http\Controllers\Arduino\Carduino;

// Route Koneksi Get Api Dapodik ------------------------------------------------------
Route::group(['prefix' => 'crew', 'middleware' => ['auth:admin']], function(){
	Route::get('/add-mesin-rfid', [Carduino::class, 'AddMesinRfid'])->name('add.mesin.rfid');
	Route::get('/view-mesin-rfid', [Carduino::class, 'viewMesinRfid'])->name('view.mesin.rfid');
	Route::get('/view-user-rfid', [Carduino::class, 'viewUserRfid'])->name('view.user.rfid');
	Route::get('/view-kartu-not-found', [Carduino::class, 'viewKartuNotFound'])->name('view.kartu.not.found');
	Route::get('/edit-kartu-not-found/{id}', [Carduino::class, 'EditKartuNotFound'])->name('edit.kartu.not.found');
	Route::post('/delete-kartu-not-found', [Carduino::class, 'DeleteKartuNotFound'])->name('delete.kartu.not.found');
	
	Route::post('/insert-mesin-rfid', [Carduino::class, 'Insert'])->name('insert.mesin.rfid');
	Route::post('/insert-kartu-rfid', [Carduino::class, 'InsertKartuRfid'])->name('insert.karu.rfid');
	Route::post('/delete-mesin-rfid', [Carduino::class, 'DeleteMesinRfid'])->name('delete.mesin.rfid');
	Route::post('/delete-user-rfid', [Carduino::class, 'DeleteUserRfid'])->name('delete.user.rfid');

	#22-10-20222
	Route::get('/test-bot-telegram', [Carduino::class, 'TestBotTelegram'])->name('bot.telegram');

});
//End Route Koneksi Get Api Dapodik------------------------------------------------------