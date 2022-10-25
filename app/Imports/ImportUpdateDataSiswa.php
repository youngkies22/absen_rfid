<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use App\User_siswa;
use App\Profile_siswa;
use App\Master_sekolah;
use App\Master_jurusan;
use App\Master_rombel;
use App\Anggota_rombel;
use App\Master_smp;
use App\Penghasilan;
use App\Master_agama;
use App\Transportasi;
use Auth;
use DB;
//ToCollection
//class ImportDataSiswa implements ToModel, WithHeadingRow
class ImportUpdateDataSiswa implements ToCollection, WithHeadingRow
{
    public $hasil; //varibale untuk menampung hasil dan di kirim kembali
    public $jm = 0;
    protected $namakolom;
    protected $kunci;
    public function __construct($kolom,$key){
     $this->namakolom = $kolom;
     $this->kunci = $key;
     
   }
  function penghasilan($value)
  {
    if(empty($value)){
      return $data=null;
    }
    else{
      $data = Penghasilan::where('pnKode',$value)->first();
      return $data->pnId;
    }
  }
  function agama($value)
  {
    if(empty($value)){
      return $data=null;
    }
    else{
      $data = Master_agama::where('agmKode',$value)->first();
      return $data->agmId;
    }
  }

  function UbahTanggal($value, $format = 'Y-m-d')
  {
    try {
      return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
    } catch (\ErrorException $e) {
      return \Carbon\Carbon::createFromFormat($format, $value);
    }
  }
  function transport($value)
  {
    
    if(empty($value)){
      return $data=null;
    }
    else{
      $data = Transportasi::where('trsKode',$value)->first();
      return $data->trsId;
    }
    
  }
  function sekolah($value){
    $skl = Master_sekolah::where('sklKode',$value)->first();
    return $skl->sklId;
  }
  function jurusan($value){
    if(empty($value)){
      return $data=null;
    }
    else{
      $jrs = Master_jurusan::where('jrsSlag',$value)->first();
      return $jrs->jrsId;
    }
  }
  

  public function collection(Collection $rows )
  {
    $dataUser = array(
      'ssaFirstName',
      'ssaLastName',
      'ssaEmail',
      'ssaQrCode',
      'ssaTahunAngkata',
      'ssaAgama',
      'ssaPassword',
      'ssaHp',
      'ssaWa',
      'ssaJrsId',
      'ssaRblId',
      'ssaSklId',
    );
    $kolomnya = $this->namakolom;
    foreach ($rows as $key => $row) 
    {
      //cek apakah nama kolom ada di dalam array akun siswa
     if(in_array($kolomnya, $dataUser)){
       //update akun menggunakan username siswa
       $data= User_siswa::where('ssaUsername',$row['key'])->first();
       $data->$kolomnya = $row['namakolom'];
       if($data->save()){
        $oke=true;
       }
     }
     else{
       //jika tidak ada makan di proses di profile siswa
       //update profile bisa menggunakan kolom NIS,NISN dll
      $data= Profile_siswa::where($this->kunci,$row['key'])->first();
       $data->$kolomnya = $row['namakolom'];
       if($data->save()){
        $oke=true;
       }
     }
     
      ++$this->jm;
    }

    
    if($oke){
      $this->hasil = 1; //untuk mengembalikan hasil menjadi array 
      $this->jm;
    }
    else{ $this->hasil = 0; }

  }//end function


  //untuk membaca data pada awal kolom berapa pada excel
  public function headingRow(): int
  {
    return 7;
  }

}
//$cek = Profile_siswa::find($dataKey)->update($data_profil);
// $cek = DB::table('profile_siswa')->where($data_profil[$i][$this->kunci])->update($data_profil);
// $data_profil[] = array(
        
      //   $this->namakolom =>$row['namakolom'], //namakolom ini dari nama pada excel
      // );

      // $dataKey[]= array(
      //   $this->kunci => $row['key'],
      // );

