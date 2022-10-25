<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use App\User_guru;

use App\Sekolah;



//ToCollection
//class ImportDataSiswa implements ToModel, WithHeadingRow
class ImportDataGuru implements ToCollection, WithHeadingRow
{
    public $hasil; //varibale untuk menampung hasil dan di kirim kembali
    public $jm = 0;
    public function __construct(){
     $this->hasil = [];
   }
  
  

 
  
  function sekolah($value){
    $skl = Sekolah::where('sklKode',$value)->first();
    return $skl->sklId;
  }
  
  public function collection(Collection $rows)
   	//public function model(array $rows)
  {

    foreach ($rows as $key => $row) 
    {
      //echo $this->jurusan($row['slag_jurusan']);
      $data_akun = array(
        'ugrUsername'  =>$row['username'],
        'ugrPassword' =>Hash::make("12345678"),
        'ugrSklId'  =>$this->sekolah($row['kode_sekolah']),
        //'ugrEmail' =>$row['email'],
        'ugrFullName' => strtoupper($row['nama_full']),
        'ugrNip' => $row["nip"],
        'ugrHp' =>$row["no_wa"],
        'ugrWa' => $row["no_hp"],
        'ugrTelegram' => $row["telegram"],
        'ugrGelarDepan' => $row["gelar_depan"],
        'ugrGelarBelakang' => $row["gelar_belakang"],
        'ugrJsk' => $row["jenis_kelamin"],
      );
      
      $data_akun2[] = $data_akun;
      ++$this->jm;
    }
    $cek = User_guru::insert($data_akun2);
    if($cek){
          $this->hasil = 1; //untuk mengembalikan hasil menjadi array 
          $this->jm;
        }
        else{
          $this->hasil = 0;
        }
  }

  //untuk membaca data pada awal kolom berapa pada excel
  public function headingRow(): int
  {
    return 7;
  }
  //Limit Bari yg di proses 
  public function chunkSize(): int
  {
    return 1000; //jumlah baris yg akan di ekseskusi
  }

}
