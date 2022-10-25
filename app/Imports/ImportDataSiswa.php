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


//ToCollection
//class ImportDataSiswa implements ToModel, WithHeadingRow
class ImportDataSiswa implements ToCollection, WithHeadingRow
{
    public $hasil; //varibale untuk menampung hasil dan di kirim kembali
    public $jm = 0;
    public function __construct(){
     $this->hasil = [];
   }
  function penghasilan($value)
  {
    if(empty($value)){
      return $data=null;
    }
    else{
      $data = Penghasilan::where('pnKode',$value)->first();
      if(empty($data)){
        return $data=null;
      }
      else{
        return $data->pnId;
      }
      
    }
  }
  function agama($value)
  {
    if(empty($value)){
      return $data=null;
    }
    else{
      $data = Master_agama::where('agmKode',$value)->first();
      if(empty($data)){
        return $data=null;
      }
      else{
        return $data->agmKode;
      }
      
    }
  }

  function UbahTanggal($value, $format = 'Y-m-d')
  {
    if(!empty($value)){
      try {
        return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
      } catch (\ErrorException $e) {
        return \Carbon\Carbon::createFromFormat($format, $value);
      }
    }
    else{
      return null;
    }
  }
  function transport($value)
  {
    
    if(empty($value)){
      return $data=null;
    }
    else{
      $data = Transportasi::where('trsKode',$value)->first();
      if(empty($data)){
        return $data=null;
      }
      else{
        return $data->trsId;
      }
      
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
  function rombel($value){
    if(empty($value)){
      return $data=null;
    }
    else{
      $data = Master_rombel::where('rblKode',$value)->first();
      return $data->rblId;
    }
  }
  

  public function collection(Collection $rows)
   	//public function model(array $rows)
  {

    foreach ($rows as $key => $row) 
    {
      //echo $this->jurusan($row['slag_jurusan']);
      $data_akun = array(
        'ssaUsername'  =>$row['username'],
        'ssaPassword' =>Hash::make($row['password']),
        'ssaSklId'  =>$this->sekolah($row['kode_sekolah']),
        'ssaJrsId'  =>$this->jurusan($row['slag_jurusan']),
        'ssaRblId' =>$this->rombel($row['kode_rombel']),
        'ssaRole' =>$row['kode_role'],
        'ssaTahunAngkata' =>$row['tahun_angkatan'],
        'ssaEmail' =>$row['email'],
        'ssaFirstName' => strtoupper($row['nama_depan']),
        'ssaLastName' => strtoupper($row['nama_belakang']),
        'ssaAgama' => $this->agama($row["agama"]),
        'ssaHp' =>$row["hpsiswa"],
        'ssaWa' => $row["wasiswa"],
        'ssaCreatedBy' =>Auth::user()->admId,
      );
      $data_profil = array(
        'psSsaUsername' =>$row['username'],
        'psJsk' =>$row['jenis_kelamin'],
        'psTpl' =>strtoupper($row['tempat_lahir']),
        'psTgl' =>$this->UbahTanggal($row["tangal_lahir"]),
        'psNis' =>$row["nis"],
        'psNisn' =>$row["nisn"],
        'psNik' =>$row["nik"],
        'psNoKK' =>$row["nokk"],
        'psAlamat' =>strtoupper($row["alamatsiswa"]),
        'psJarak' =>$row["jarak_kesekolah"],
        'psTransport' =>$this->transport($row["kode_transportasi"]),
        'psNoKKS' =>$row["no_kks"],
        'psNoPKH' =>$row["no_pkh"],
        'psNoKip' =>$row["no_kip"],
         // DATA AYAH
        'psNamaAyah' =>strtoupper($row["nama_ayah"]),
        'psNikAyah' =>$row["nik_ayah"],
        'psTplAyah' =>strtoupper($row["tempat_lahir_ayah"]),
        'psTglAyah' =>$this->UbahTanggal($row["taggal_lahir_ayah"]),
        'psPendidikanAyah' =>$row["pendidikan_ayah"],
        'psPekerjaanAyah' =>$row["pekerjaan_ayah"],
        'psPenghasilanAyah' =>$this->penghasilan($row["penghasilan_ayah"]),
        'psAlamatAyah' =>strtoupper($row["alamat_ayah"]),
        'psHpAyah' =>$row["hp_ayah"],
        // DATA IBU
        'psNamaIbu' =>strtoupper($row["nama_ibu"]),
        'psNikIbu' =>$row["nik_ibu"],
        'psTplIbu' =>strtoupper($row["tempat_lahir_ibu"]),
        'psTglIbu' =>$this->UbahTanggal($row["taggal_lahir_ibu"]),
        'psPendidikanIbu' =>$row["pendidikan_ibu"],
        'psPekerjaanIbu' =>$row["pekerjaan_ibu"],
        'psPenghasilanIbu' =>$this->penghasilan($row["penghasilan_ibu"]),
        'psAlamatIbu' =>strtoupper($row["alamat_ibu"]),
        'psHpIbu' =>$row["hp_ibu"],
        //DATA WALI
        'psNamaWali' =>strtoupper($row["nama_wali"]),
        'psNikWali' =>$row["nik_wali"],
        'psTplWali' =>strtoupper($row["tempat_lahir_wali"]),
        'psTglWali' =>$this->UbahTanggal($row["taggal_lahir_wali"]),
        'psPendidikanWali' =>$row["pendidikan_wali"],
        'psPekerjaanWali' =>$row["pekerjaan_wali"],
        'psPenghasilanWali' =>$this->penghasilan($row["penghasilan_wali"]),
        'psAlamatWali' =>strtoupper($row["alamat_wali"]),
        'psHpWali' =>$row["hp_ibu"],
        //DATA SMP
        'psAsalSmp' =>$row["id_smp"],
        'psNpsnSmp' =>$row["npsn_smp"],
        'psNoUjianSmp' =>$row["no_ujian_smp"],
      );
      $data_anggota_rombel = array(
        'arbSklId' =>$this->sekolah($row['kode_sekolah']),
        'arbJrsId' =>$this->jurusan($row['slag_jurusan']),
        'arbRblId' =>$this->rombel($row['kode_rombel']),
        'arbSsaId' =>$row['username'],
        'arbCreatedBy' =>Auth::user()->admId,
      );

      $data_akun2[] = $data_akun;
      $data_profil2[] = $data_profil;
      $data_anggota_rombel2[] = $data_anggota_rombel;
      ++$this->jm;
    }
    $cek = User_siswa::insert($data_akun2);
    if($cek){
      $cek2 = Profile_siswa::insert($data_profil2);
      Anggota_rombel::insert($data_anggota_rombel2);
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
