<?php

namespace App\Http\Controllers\Arduino;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Auth;
use App\User_guru;
use App\Rfid_user;
use App\Rfid_mesin;
use App\Rfid_kartu_not_found;
use App\Sekolah;



class Carduino extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:admin');
    //parameter pada dapodik
    //$this->getSekolah = 'getSekolah';
    
  }

  function getSekolah()
  {
  	$data = new Sekolah();
    return $data;
  }


  public function AddMesinRfid(){
    $token = Str::random(32);
    $params = [
      'title' =>'Tambah Mesin RFID',
      'label' =>'<b>TAMBAH MESIN RFID</b> ',
      'token'     => $token,
    ];
    return view('crew/arduino_rfid/add')->with($params);
  }
  
  public function viewMesinRfid(){
    $data = Rfid_mesin::get();
    $params = [
      'title' =>'Data Mesin RFID',
      'label' =>'<b>DATA MESIN RFID</b> ',
      'data'  => $data,
    ];
    return view('crew/arduino_rfid/view')->with($params);
  }

  public function viewUserRfid(){
    $dataa = Rfid_user::with('user_guru')->get();
    foreach($dataa as $value){
      $data2['iduser'] =$value->rfidId;
      $data2['username'] =$value->rfidUsername;
      $data2['koderfid'] =$value->rfidKartuId;
      if(!empty($value->user_guru->ugrGelarDepan)){
        $data2['namafull'] =$value->user_guru->ugrGelarDepan.'. '.$value->user_guru->ugrFullName.', '.$value->user_guru->ugrGelarBelakang;
      }
      else if(empty($value->user_guru->ugrGelarDepan) and empty($value->user_guru->ugrGelarBelakang)){
        $data2['namafull'] =$value->user_guru->ugrFullName;
      }
      else{
        $data2['namafull'] =$value->user_guru->ugrFullName.', '.$value->user_guru->ugrGelarBelakang;
      }

      $data3[]= $data2;
    }
    $params = [
      'title' =>'Data User RFID',
      'label' =>'<b>DATA USER RFID</b> ',
      'data'  => $data3,
    ];
    return view('crew/arduino_rfid/view_user')->with($params);
  }

  public function viewKartuNotFound(){
    $data = Rfid_kartu_not_found::get();
    $params = [
      'title' =>'Data Kartu Not Found RFID',
      'label' =>'<b>DATA KARTU NOT FOUND RFID</b> ',
      'data'  => $data,
    ];
    return view('crew/arduino_rfid/kartu_not_found')->with($params);
  }
  function getDataGuru(){
    $data = User_guru::get();
    foreach($data as $value){
      
      if(!empty($value->ugrGelarDepan)){
        $data2['namafull'] =$value->ugrGelarDepan.'. '.$value->ugrFullName.', '.$value->ugrGelarBelakang;
      }
      else if(empty($value->ugrGelarDepan) and empty($value->ugrGelarBelakang)){
        $data2['namafull'] =$value->ugrFullName;
      }
      else{
        $data2['namafull'] =$value->ugrFullName.', '.$value->ugrGelarBelakang;
      }
      $data2['username'] = $value->ugrUsername;
      $data3[] = $data2;
    }
    return $data3;
  }
  
  public function EditKartuNotFound(Request $request){
    $data = Rfid_kartu_not_found::find($request->id);
    $dataGuru = $this->getDataGuru();

    $params = [
      'title' =>'Edit Kartu Not Found RFID',
      'label' =>'<b>EDIT KARTU NOT FOUND RFID</b> ',
      'data'  => $data,
      'id'    => $request->id,
      'data_user'  =>$dataGuru,
    ];
    return view('crew/arduino_rfid/edit_kartu_not_found')->with($params);
  }
  function DeleteMesinRfid(Request $request){
    
    $data = Rfid_mesin::find($request->id);
    if($data->delete()){
      $response = ['success'=>'Berhasil Hapus Mesin']; 
    }
    else{
      $response = ['error'=>'Opsss Gagal !!!'];
    }
    return response()->json($response,200);
  }

  function DeleteUserRfid(Request $request){
    
    $data = Rfid_user::find($request->id);
    if($data->delete()){
      $response = ['success'=>'Berhasil Hapus Mesin']; 
    }
    else{
      $response = ['error'=>'Opsss Gagal !!!'];
    }
    return response()->json($response,200);
  }

  function Insert(Request $request){
    //dd($request);
    $data = Rfid_mesin::where('rfMesinKode',$request->kode)->count();
    
    if($data > 0){
      $response = ['error'=>'Mesin Dengan Kode ini Sudah Ada !!!'];
    }
    else{
      $dataArray = array(
          'rfMesinSklId'  => $this->getSekolah()->sklId,
          'rfMesinKode'   => $request->kode,
          'rfMesinNama'   => $request->nama,
          'rfMesinToken'  => $request->token
      );
      $cek = Rfid_mesin::insert($dataArray);
      if($cek){ 
        $response = ['save'=>'Berhasil Tambah Absensi']; 
      }
      else{ $response = ['error'=>'Opsss Gagal !!!'];}

    }
    return response()->json($response,200);
    
  }

  //tambah kartu rfid not found ke database sesuai username siswa
  function InsertKartuRfid(Request $request){
   
    $dataArray = array(
      'rfidKartuId'   => $request->idkartu,
      'rfidUsername'  => $request->username,
    );
    

    $cek = Rfid_user::where('rfidUsername', $request->username)
    ->orWhere('rfidKartuId', $request->idkartu)
    ->first();
    if(is_null($cek)){
      $data = Rfid_user::insert($dataArray);
      if ($data) {
          $del = Rfid_kartu_not_found::find($request->id);
          $del->delete();
          $response = ['save' => 'Berhasil Tambah Kartu RFID'];
          return response()->json($response, 200); //Forbidden
        } else {
          $response = ['message' => 'Gagal Tambah Kartu RFID'];
          return response()->json($response, 403); //Forbidden
        }
    }
    else{
      $data = Rfid_user::where('rfidUsername', $request->username)->update($dataArray);
      if ($data) {
          $del = Rfid_kartu_not_found::find($request->id);
          $del->delete();
          $response = ['save' => 'Berhasil Update Kartu RFID'];
          return response()->json($response, 200); //Forbidden
        } else {
          $response = ['message' => 'Gagal Update Kartu RFID'];
          return response()->json($response, 403); //Forbidden
        }
    }

    // $cek = Rfid_user::firstWhere('rfidUsername', $request->username);
    // if(is_null($cek)){
    //   $data = Rfid_user::insert($dataArray);
    //   if($data->save()){
    //     $response = ['save'=>'Berhasil Tambah Kartu RFID']; 
    //     return response()->json($response, 200); //Forbidden
    //   }
    //   else{
    //     $response = ['error'=>'Gagal Save Kartu RFID'];
    //     return response()->json($response, 403); //Forbidden
    //   }
    // }
    // else{
    //   $response = ['error'=>'Username Siswa dan Kartu RFID Sudah Ada'];
    //     return response()->json($response, 403); //Forbidden
    // }
    
    

  }

  function DeleteKartuNotFound(Request $request){
    $id = $request->id;
    $data = rfid_kartu_not_found::find($id);
    if($data->delete()){
      $response = ['success'=>'Berhasil Hapus Kartu RFID']; 
      return response()->json($response, 200); //Forbidden
    }
    else{
      $response = ['error'=>'Opsss Gagal !!!'];
      return response()->json($response, 403); //Forbidden
    }

  }


  /**
   * test bot telegram
   * kirim pesan ke grup telegram melalui bot
   * 22-10-2022
   */
  public function TestBotTelegram(){
    //telegram ----------------------------------------------------------------------------
    
    $tgl = date('Y-m-d');
		$hari = date('l');
    
    $message ="*TEST NOTIF TELEGRAM* \n";
    $message.="TANGGAL : ".formatTanggalIndo($tgl)."\n";
    $message.="HARI : ".hariIndo($hari)."\n";
    $message.="STATUS : BERHASIL"; 
    $foto = public_path('image/avatar3.png');
    

    //sendMessage($message);
    sendMessageWithFoto($message,$foto);
  }






}
