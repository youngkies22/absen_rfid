<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use App\Master_mapel;
use App\Master_jurusan;
use App\Master_kelas;
use App\Master_sekolah;

use Auth;


//ToCollection
//class ImportDataSiswa implements ToModel, WithHeadingRow
class ImportDataMapel implements ToCollection, WithHeadingRow
{
    public $hasil; //varibale untuk menampung hasil dan di kirim kembali
    public $jm = 0;
    public function __construct(){
     $this->hasil = [];
   }
  
  function sekolah($value){
    $data = Master_sekolah::where('sklKode',$value)->first();
    return $data->sklId;
  }
  function jurusan($value){
    if(empty($value)){
      return null;
    }
    else{
      $data = Master_jurusan::where('jrsSlag',$value)->first();
      return $data->jrsId;
    }
    
  }
  function kelas($value){
    if(empty($value)){
      return null;
    }
    else{
      $data = Master_kelas::where('klsKode',$value)->first();
      return $data->klsId;
    }
    
  }
  
  
  public function collection(Collection $rows)
   	//public function model(array $rows)
  {

    foreach ($rows as $key => $row) 
    {
      $data = array(
        'mapelSklId'  =>$row['id_sekolah'],
        'mapelJrsId'  =>$this->jurusan($row['jurusan']),
        'mapelKlsId'  =>$this->kelas($row['kelas']),
        'mapelMpktKode'  =>$row['kategori'],
        'mapelKode'  =>$row['kode'],
        'mapelSlug'  =>$row['slug'],
        'mapelNama'  =>$row['nama_mapel'],
        'mapelPaket'  =>$row['paket'],
        
      );
      $data2[] = $data;
      ++$this->jm;
    }
    $cek = Master_mapel::insert($data2);
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
