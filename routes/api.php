<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiRfidArduino;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
//cara aksesnya http://127.0.0.1:8000/api/apiuser/2/3
// 



//post data absen RFID Arduino Siswa
Route::get('apiarduino/{token}/{idkartu}', [ApiRfidArduino::class, 'getArduino']);
Route::get('apiarduinorfid/{token}/{idkartu}', [ApiRfidArduino::class, 'getArduinoCoba']);