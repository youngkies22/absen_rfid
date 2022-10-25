<?php 
/*----------------------------------
  mryes
  Way Jepara
  SMK Budi Utomo Way Jepara
  Kumpulan Function Dashbord Admin
  Absensi Pada Siswa
-------------------------------------*/


use Illuminate\Support\Facades\Storage;
use App\User_guru;
use App\Sekolah;
use App\Setting;
use App\Rfid_mesin;
use App\Rfid_kartu_not_found;





/*Function Secara Global untuk admin guru siswa */ 
  function Setting(){
    
      $data = Setting::first();
     
    return $data;
  }
  function getInformasiSekolah($id=null){
   
      if(!empty($id)){ //id sekolah
        $data = Informasi::whereIn('infoIdTujuan',[$id,0])
        ->orderBy('infoCreated','ASC')
        ->where('infoIsActive',1)
        ->limit(3)
        ->get();
      }
      else{
        $data = Informasi::where('infoIsActive',1)
        ->orderBy('infoCreated','ASC')
        ->limit(5)
        ->get();
      }
     
    
    if(empty($data)){
      $data2 = [];
    }
    else{
      $data2 = $data;
    }
    return $data2;
  }


  function getDataKepalaSekolah($id=null){
    
      if(!empty($id)){
        $data = User_guru::where('ugrSklId',$id)->where('ugrTugasTambahan','KEPSEK')->with('profile_guru')->first();
      }
      else{
        $data = User_guru::where('ugrTugasTambahan','KEPSEK')->with('profile_guru')->get();
      }
      
    return $data;
  }
/*Function Secara Global untuk admin guru siswa*/

function getIdSekolah()
{
  //get id sekolah pada user
  $IdSekolah = Auth::user()->admSklId;
  return $IdSekolah ;
}
function getDataSekolahById(){
  $data = Sekolah::first();
      
    return $data;
}
function IsAcktive(){
  return 1;
}
/*Get Function Database Table*/
 
  
  


/* /Get Function Database Table*/
/*Get Function Profile*/
  function FullNama(){
    $fullnama = strtoupper(Auth::user()->admFirstName.' '.Auth::user()->admLastName);
    return $fullnama;
  }
  function NamaDepan(){
    $fullnama = strtoupper(Auth::user()->admFirstName);
    return $fullnama;
  }
  function NamaBelakang(){
    $fullnama = strtoupper(Auth::user()->admLastName);
    return $fullnama;
  }
/* hak aksi admin ---------------------------------------------*/
  function AksiInsert(){
    //cek apakah di beri izin untuk inser data
    $data = Auth::user()->admInsert;
    if($data == 1){ $aksi = true; }else{ $aksi = false; }
    return $aksi;
  }
  function AksiUpdate(){
    //cek apakah di beri izin untuk Update Data
    $data = Auth::user()->admUpdate;
    if($data == 1){ $aksi = true; }else{ $aksi = false; }
    return $aksi;
    
  }
  function AksiDelete(){
    //cek apakah di beri izin untuk Delete Data
    $data = Auth::user()->admDelete;
    if($data == 1){ $aksi = true; }else{ $aksi = false; }
    return $aksi;
  }
  function HakAkses(){
    //cek hak askes admin apakah superadmin
    $data = Auth::user()->admRole;
    if($data == "SUPERADMIN"){ 
      $aksi = true; }
    else{ $aksi = false; }
    return $aksi;
  }
  function KodeHakAkses(){
    //Kode hak akses untuk membatasi hak yg bukan SuperAdmin Full
    $data = Auth::user()->admKode;
    if($data == "SUPERADMIN"){ $aksi = true; }else{ $aksi = false; }
    return $aksi;
  }
/* end hak aksi admin ---------------------------------------------*/

  //get foto pada guru dan admin
  function GetFotoProfile($id=null){

   if(!empty($id)){

      $img3 = asset('storage/images/guru/original/'.$id);
      //jika foto tidaka ada atau kosong makan retrun defaul img
      if(empty($img3)){
        $img2 = asset('image/avatar3.png');
      }
      else{
        $img2 = asset('storage/images/guru/original/'.$id);
      }
      $img=$img2;
    
    }
    elseif(!empty(Auth::user()->admFotoProfile)){
      $img = asset('storage/images/guru/original/'.Auth::user()->admFotoProfile);
    }
    else{
      $img = asset('image/avatar3.png');
    } 
    return $img;
            
  }
  function GetFotoMenu(){

    if(!empty(Auth::user()->admFotoProfile)){
      $img = asset('storage/images/guru/100/'.Auth::user()->admFotoProfile);
    }
    else{
      $img = asset('image/avatar3.png');
    } 
    return $img;
            
  }
  //menampilkan foto siswa tumblis pada data siswa
  function FotoSiswaSmall($id=null){
    if(!empty($id)){
      $img = asset('storage/images/siswa/100/'.$id);
    }
    else{
      $img = asset('image/noimage.png');
    } 
    return '<img width="80" height="80" src="'.$img.'?date='.time().'" class="img-fluid rounded-circle">';
  }
  function FotoGuruSmall($id=null){
    if(!empty($id)){
      $img = asset('storage/images/guru/100/'.$id);
    }
    else{
      $img = asset('image/noimage.png');
    } 
    return '<img width="80" height="80" src="'.$img.'?date='.time().'" class="img-fluid rounded-circle">';
  }

  function LogoSekolah(){
    $img = asset('image/logo_sekolah.png');
    return $img;
  }

  function BgLogin(){
    $img = asset('image/bg.jpg');
    return $img;
  }
  

  function GetHakAkses(){
    if(Auth::user()->admKode != "SUPERADMIN"){
      return false;
    }
    else{
      return true;
    }
  }
  
  function HakAksesFilterMenu()
  {
    if(Auth::user()->admKode != "SUPERADMIN"){
      return false;
    }
    else{
      return true;
    }
    
  }
  function HakAksesFilterMenuSuper(){
    if(Auth::user()->admKode != "SUPERADMIN"){
      return false;
    }
    else{
      return true;
    }
  }


//ARDUINO RFID -----------------------------------------------------------------------------------------------------------------------
  //cek status kehadiran absen siswa RFID
  function CekKehadiranJamMasukRfid($jam,$username,$tgl,$namahari){
    $jamSekolah = Master_jam_sekolah::first();
    $jamMasuk = strtotime($jamSekolah->jamsklMasuk);
    $jamPulang= strtotime($jamSekolah->jamsklPulang);
    $jamTerlambat = strtotime($jamSekolah->jamBatasTerlambar);
    $jamsklPulangJumat = strtotime($jamSekolah->jamsklPulangJumat);
    
    $jamNow = strtotime($jam);
    //$hariIndo = hariIndo($namahari);
    $hariIndo = 'Sabtu';

    //Jumat
    //Sabtu
    //Minggu
    // $cek = Absen_finger_siswa::where('afsDatetime',$tgl)->where('afsSsaUsername',$username)->first();
    
    // if(is_null($cek->afsIn)){
    //   echo"Kosong";
    // }
    // else{
    //   echo"Oke";
    // }

    if($hariIndo == 'Jumat'){
      //jam masuk sesuai jam mausk
      if ($jamNow <= $jamMasuk) {
        $status = 'H';

        $cek = Absen_finger_siswa::where('afsDatetime', $tgl)
          ->where('afsSsaUsername', $username)
          ->first();

        //jika data abse sudah ada maka false jika beluma ada maka true
        if (is_null($cek)) {
          $cekData = 0; //belum ada data absen 

        } else {
          // if(!is_null($cek->afsIn)){
          //   $status = 'H';
          //   $idabsen = $cek->afsId;
          //   $cekData = 1; //data ada tapi absen masuk kosong
          // }
          // else{
          //   $status = 'H';
          //   $idabsen = $cek->afsId;
          //   $cekData = 2; //data ada tpi absen masuk tidak kosong
          // }
          $cekData = 1; //data ada tapi absen masuk kosong

        }
        $array = array(
          'status'     => $status,
          'statusData' => $cekData,
        );

        return $array;
      }

      //jam masuk tetapi terlambat
      elseif ($jamNow > $jamMasuk and  $jamNow < $jamsklPulangJumat) {
        $status = 'T';
        $cek = Absen_finger_siswa::where('afsDatetime', $tgl)
          ->where('afsSsaUsername', $username)
          ->first();

        //jika data abse sudah ada maka false jika beluma ada maka true
        // if(is_null($cek)){ 
        //   $cekData = 10; //belum ada data absen  
        // }else{ 
        //   if(is_null($cek->afsIn)){
        //     $cekData =11; //data ada tapi absen masuk kosong
        //   }
        //   else{
        //     $cekData = 12; //data ada tpi absen masuk tidak kosong
        //   }

        // }

        //jika data abse sudah ada maka false jika beluma ada maka true
        if (is_null($cek)) {
          $cekData = 0; //belum ada data absen 
        } else {
          $cekData = 1; //data ada tapi absen masuk kosong
        }
        $array = array(
          'status'     => $status,
          'statusData' => $cekData,
        );
        return $array;
      } elseif ($jamNow >= $jamsklPulangJumat) {
        $cek = Absen_finger_siswa::where('afsDatetime', $tgl)
          ->where('afsSsaUsername', $username)
          ->first();
        if (is_null($cek)) { //jika data absen null
          $status = 'T';
          $cekData = 30; //belum ada data absen pulang  
          $array = array(
            'status'     => $status,
            'statusData' => $cekData,
          );
        } else {
          if (is_null($cek->afsIn)) { // jika ada data tapi data absen masuk kosong
            $status = 'T';
            $idabsen = $cek->afsId;
            $cekData = 31; //ada data tpi jam masuk kosong
            $array = array(
              'status'     => $status,
              'statusData' => $cekData,
              'idabsen'    => $idabsen,
            );
          } else {
            //jika data ada dan data absen masuk sudah terisi
            if ($cek->afsAkId == 'H') { //kondiis cek status absen masuk
              $status = 'H';
              $idabsen = $cek->afsId;
              $cekData = 32; //data ada tpi jam masuk tidak kosong
              $array = array(
                'status'     => $status,
                'statusData' => $cekData,
                'idabsen'    => $idabsen,
              );
            } else if ($cek->afsAkId == 'I' or $cek->afsAkId == 'S' or $cek->afsAkId == 'T') {
              $status   = $cek->afsAkId;
              $idabsen = $cek->afsId;
              $cekData = 33; //data ada tpi jam masuk tidak kosong
              $array = array(
                'status'     => $status,
                'statusData' => $cekData,
                'idabsen'    => $idabsen,
              );
            } else {
            }
          }
        }

        return $array;
      } else {
        $status = 'T';
      }
    }
    else if($hariIndo == 'Sabtu' AND $hariIndo == 'Minggu'){

    }
    
    else{
    //HARI SELAIN JUMAT SABTU MINNGGU
    //---------------------------------------------------------------------------------------------------
      //jam masuk sesuai jam mausk
      if ($jamNow <= $jamMasuk) {
        $status = 'H';

        $cek = Absen_finger_siswa::where('afsDatetime', $tgl)
          ->where('afsSsaUsername', $username)
          ->first();

        //jika data abse sudah ada maka false jika beluma ada maka true
        if (is_null($cek)) {
          $cekData = 0; //belum ada data absen 

        } else {
          // if(!is_null($cek->afsIn)){
          //   $status = 'H';
          //   $idabsen = $cek->afsId;
          //   $cekData = 1; //data ada tapi absen masuk kosong
          // }
          // else{
          //   $status = 'H';
          //   $idabsen = $cek->afsId;
          //   $cekData = 2; //data ada tpi absen masuk tidak kosong
          // }
          $cekData = 1; //data ada tapi absen masuk kosong

        }
        $array = array(
          'status'     => $status,
          'statusData' => $cekData,
        );

        return $array;
      }

      //jam masuk tetapi terlambat
      elseif ($jamNow > $jamMasuk and  $jamNow < $jamPulang) {
        $status = 'T';
        $cek = Absen_finger_siswa::where('afsDatetime', $tgl)
          ->where('afsSsaUsername', $username)
          ->first();

        //jika data abse sudah ada maka false jika beluma ada maka true
        // if(is_null($cek)){ 
        //   $cekData = 10; //belum ada data absen  
        // }else{ 
        //   if(is_null($cek->afsIn)){
        //     $cekData =11; //data ada tapi absen masuk kosong
        //   }
        //   else{
        //     $cekData = 12; //data ada tpi absen masuk tidak kosong
        //   }

        // }

        //jika data abse sudah ada maka false jika beluma ada maka true
        if (is_null($cek)) {
          $cekData = 0; //belum ada data absen 
        } else {
          $cekData = 1; //data ada tapi absen masuk kosong
        }
        $array = array(
          'status'     => $status,
          'statusData' => $cekData,
        );
        return $array;
      } elseif ($jamNow >= $jamPulang) {
        $cek = Absen_finger_siswa::where('afsDatetime', $tgl)
          ->where('afsSsaUsername', $username)
          ->first();
        if (is_null($cek)) { //jika data absen null
          $status = 'T';
          $cekData = 30; //belum ada data absen pulang  
          $array = array(
            'status'     => $status,
            'statusData' => $cekData,
          );
        } else {
          if (is_null($cek->afsIn)) { // jika ada data tapi data absen masuk kosong
            $status = 'T';
            $idabsen = $cek->afsId;
            $cekData = 31; //ada data tpi jam masuk kosong
            $array = array(
              'status'     => $status,
              'statusData' => $cekData,
              'idabsen'    => $idabsen,
            );
          } else {
            //jika data ada dan data absen masuk sudah terisi
            if ($cek->afsAkId == 'H') { //kondiis cek status absen masuk
              $status = 'H';
              $idabsen = $cek->afsId;
              $cekData = 32; //data ada tpi jam masuk tidak kosong
              $array = array(
                'status'     => $status,
                'statusData' => $cekData,
                'idabsen'    => $idabsen,
              );
            } else if ($cek->afsAkId == 'I' or $cek->afsAkId == 'S' or $cek->afsAkId == 'T') {
              $status   = $cek->afsAkId;
              $idabsen = $cek->afsId;
              $cekData = 33; //data ada tpi jam masuk tidak kosong
              $array = array(
                'status'     => $status,
                'statusData' => $cekData,
                'idabsen'    => $idabsen,
              );
            } else {
            }
          }
        }

        return $array;
      } else {
        $status = 'T';
      }
    //---------------------------------------------------------------------------------------------------  
    }
 

  }
  /**
   * send notif ke grup telegram menggunakan bot
   */
  function sendMessage($message_text) {

		$secret_token = env('TELEGRAM_TOKEN');
		$telegram_id = env('TELEGRAM_IDGRUP');

    $url = "https://api.telegram.org/bot" . $secret_token . "/sendMessage?parse_mode=markdown&chat_id=" . $telegram_id;
    $url = $url . "&text=" . urlencode($message_text);
    $ch = curl_init();
    $optArray = array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    curl_exec($ch);
    curl_close($ch);
	}
  function sendMessageWithFoto($message,$foto) 
  {
   
    $secret_token = env('TELEGRAM_TOKEN');
		$telegram_id = env('TELEGRAM_IDGRUP');

    $url = 'https://api.telegram.org/bot'.$secret_token.'/sendPhoto?parse_mode=markdown';
    $param = array(
      'chat_id' =>$telegram_id,
      'caption' =>$message,
      'photo'   =>new CURLFile(realpath($foto))
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
    return curl_exec($ch);
    curl_close($ch);
  }
  // function sendMessageWithFoto($message,$foto) 
  // {
    
  //   $token = env('TELEGRAM_TOKEN');
	// 	$telegram_id = env('TELEGRAM_IDGRUP');
  //   $ch = curl_init();
  //   curl_setopt($ch, CURLOPT_POST, true);
  //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  //   curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
  //   curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot'.$token.'/sendMessage');
  //   $file = storage_path('app/public/images/guru/300/1666445127_6353ef475da23.jpg');
  //   $postFields = array(
  //       'chat_id' => $telegram_id,
  //       'caption'=>$message,
  //       'photo'=> new CURLFile(realpath($file)),
  //       //'text'  =>$message,
  //       'parse_mode' => 'markdown',
  //       'disable_web_page_preview' => false,
  //   );
  //   curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
  //   if(!curl_exec($ch))
  //   echo curl_error($ch);
  //   curl_close($ch);
  //   curl_exec($ch);
    
  // }

  function getDataUser(){
    $data = User_guru::count();
    return $data;
  }
  function getDataMesin(){
    $data = Rfid_mesin::count();
    return $data;
  }
  function getDataKartuNotFound(){
    $data = Rfid_kartu_not_found::count();
    return $data;
  }
  /* /Get Function Dashbor*/