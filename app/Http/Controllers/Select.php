<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Auth;//menjalankan printah auth
use App\User_siswa;
use App\Master_sekolah;
use App\Master_jurusan;
use App\Master_rombel;
use App\Master_kelas;
use App\Anggota_rombel;
use App\Master_smp;
use App\Master_mapel;
use App\Master_mapel_jadwal;

class Select extends Controller
{
	// public function __construct()
  // {
  //   $this->middleware(['admin','siswa','guru']);
  // }

  function getSkl()
  {
  	$idskl = Auth::user()->admSklId; 
  	return $idskl;
  }
  // panggil fungsi pada model
  function getSekolah()
  {
  	$data = new Master_sekolah();
    return $data->getSekolah($this->getSkl());
  }
	public function SelectJurusan(Request $request)
	{
		
    $id=decrypt_url($request->id);
    if(!empty($id)){

      $data =  Master_jurusan::Where('jrsSklId',$id)->get();
      foreach ($data as $jrs) {
        $data2 = array(
          'idjrs' => Crypt::encrypt($jrs->jrsId),
          'slugjrs' => $jrs->jrsSlag,
          'nmjrs' => $jrs->jrsNama, 
        );
        $data3[] = $data2;
      }
    }else{ $data3[]=''; }
    return $data3 ;
  }
  
  //filter rombel berdasrkan jurusan
  public function SelectRombel(Request $request)
  {
    $id = Crypt::decrypt($request->id);
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('SelectRombel'.$id)){ $data3= Cache::get('SelectRombel'.$id); }
		else{
      if(!empty($id)){
        $data =  Master_rombel::Where('rblJrsId',$id)->with('master_kelas')->get();
        foreach ($data as $val) {
          $data2 = array(
            'idrbl' => Crypt::encrypt($val->rblId),
            'koderbl' => $val->rblKode,
            'level' => $val->master_kelas->klsKode,
            'nmrbl' => $val->rblNama, 
          );
          $data3[] = $data2;
        }
      }else{ $data3[]=''; }
      Cache::put('SelectRombel'.$id, $data3, ChaceJam() );
    }
    //cache data redis ---------------------------------------------------------------------------
    return $data3 ;
  }
  //filter rombel berdasrkan sekolah
  public function SelectRombelSkl(Request $request)
  {
    $id=decrypt_url($request->id);
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('SelectRombelSkl'.$id)){ $data3= Cache::get('SelectRombelSkl'.$id); }
		else{ 
      if(!empty($id)){
        $data =  Master_rombel::Where('rblSklId',$id)->with('master_kelas')->get();
        foreach ($data as $val) {
          $data2 = array(
            'idrbl' => encrypt_url($val->rblId),
            'koderbl' => $val->rblKode,
            'level' => $val->master_kelas->klsKode,
            'nmrbl' => $val->rblNama, 
          );
          $data3[] = $data2;
        }
      }else{ $data3[]=''; }
      Cache::put('SelectRombelSkl'.$id, $data3, ChaceJam() );
    }
    //cache data redis ---------------------------------------------------------------------------
    return $data3 ;
  }
  //filter rombel berdasarkan kelas
  public function SelectRombelKelas(Request $request)
  {
    $kelas = $request->kelas; //tampung id kelas
    $jrs = Crypt::decrypt($request->id); //tampung id jurusan
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('rblkelas'.$jrs.$kelas)){ $data3 = Cache::get('rblkelas'.$jrs.$kelas); }
    else{ 
      $data =  Master_rombel::where('rblKlsId',$kelas)
      ->where('rblJrsId',$jrs)
      ->with('master_kelas')->get();
      foreach ($data as $val) {
        $data2 = array(
          // 'idrbl' => Crypt::encrypt($val->rblId),
          'koderbl' => $val->rblKode,
          'level' => $val->master_kelas->klsKode,
          'nmrbl' => $val->rblNama, 
        );
        $data3[] = $data2;
      }
      Cache::put('rblkelas'.$jrs.$kelas, $data3, ChaceJam());
    }
    //cache data redis ---------------------------------------------------------------------------

    return $data3 ;
  }
  //filter rombel berdasrkan sekolah dan kelas
  public function SelectRombelSklKelas(Request $request)
  {
    $idskl=decrypt_url($request->skl);
    $kelas = decrypt_url($request->kelas);
    $idkelas = master_kelas::where('klsKode',$kelas)->first();
    
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('SelectRombelSklkelas'.$idskl.$kelas)){ $data3= Cache::get('SelectRombelSklkelas'.$idskl.$kelas); }
		else{ 
      if(!empty($idskl)){
        if($idskl=='ALL'){
          $data =  Master_rombel::Where('rblKlsId',$idkelas->klsId)
          ->with('master_kelas')->get();
          foreach ($data as $val) {
            $data2 = array(
              'idrbl' => encrypt_url($val->rblId),
              'koderbl' => $val->rblKode,
              'level' => $val->master_kelas->klsKode,
              'nmrbl' => $val->rblNama, 
            );
            $data3[] = $data2;
          }
        }
        else{
          $data =  Master_rombel::Where('rblSklId',$idskl)
          ->Where('rblKlsId',$idkelas->klsId)
          ->with('master_kelas')->get();
          foreach ($data as $val) {
            $data2 = array(
              'idrbl' => encrypt_url($val->rblId),
              'koderbl' => $val->rblKode,
              'level' => $val->master_kelas->klsKode,
              'nmrbl' => $val->rblNama, 
            );
            $data3[] = $data2;
          }
        }
      }
      else{ $data3[]=''; }
      Cache::put('SelectRombelSklkelas'.$idskl.$kelas, $data3, ChaceJam() );
    }
    //cache data redis ---------------------------------------------------------------------------
    return $data3 ;
  }

  //filter mapel berdasarkan jurusan dan kelas dan sekolah
  public function SelectMataPelajaran(Request $request){
    $skl=decrypt_url($request->skl);
    $jurusan=Crypt::decrypt($request->jrs); //tampung id jurusan
    $kelas=$request->kelas;
    
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('SelectMataPelajaran'.$skl.$jurusan.$kelas)){ $data3 = Cache::get('SelectMataPelajaran'.$skl.$jurusan.$kelas); }
    else{ 
      $data =  Master_mapel::where('mapelSklId',$skl)
      ->where('mapelJrsId',$jurusan)
      ->orWhereNull('mapelJrsId')
      ->where('mapelKlsId', $kelas)
      ->orWhereNull('mapelKlsId')
      ->where('mapelIsActive',1)
      ->get();
      foreach ($data as $val) {
        $data2 = array(
          'kodemapel' => $val->mapelKode,
          'namamapel' =>$val->mapelNama.' ('.$val->mapelKode.')',
        );
        $data3[] = $data2;
      }
      Cache::put('SelectMataPelajaran'.$skl.$jurusan.$kelas, $data3, ChaceJam());
    }
    //cache data redis ---------------------------------------------------------------------------
    return $data3;
    
    
  }
  //filter mapel berdasarkan kelas dan sekolah
  public function SelectMataPelajaranKelas(Request $request){
    $skl=decrypt_url($request->skl);
    $kelas=$request->kelas;
    
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('SelectMataPelajaran'.$skl.$kelas)){ $data3 = Cache::get('SelectMataPelajaran'.$skl.$kelas); }
    else{ 
      $data =  Master_mapel::where('mapelSklId',$skl)
      ->orWhereNull('mapelJrsId')
      ->where('mapelKlsId', $kelas)
      ->orWhereNull('mapelKlsId')
      ->where('mapelIsActive',1)
      ->get();
      foreach ($data as $val) {
        $data2 = array(
          'kodemapel' => $val->mapelKode,
          'namamapel' =>$val->mapelNama.' ('.$val->mapelKode.')',
        );
        $data3[] = $data2;
      }
      Cache::put('SelectMataPelajaran'.$skl.$kelas, $data3, ChaceJam());
    }
    //cache data redis ---------------------------------------------------------------------------
    return $data3;
  }
  //filter mapel berdasarkan rombel dan sekolah
  public function SelectMataPelajaranRombel(Request $request){
    $id =Auth::user()->ugrId;
    $skl=decrypt_url($request->skl);
    $rombel=$request->rbl;
    
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('SelectMataPelajaran'.$skl.$rombel.$id)){ $data3 = Cache::get('SelectMataPelajaran'.$skl.$rombel.$id); }
    else{ 
      $data3 = Master_mapel_jadwal::where('majdUgrId',$id)
         // ->with('master_mapel')
          ->with('master_mapel',function($query){
            $query->select('mapelKode','mapelNama');
            })
          ->where('majdRblKode',$rombel)
          ->select('majdMapelKode','majdNama','majdJenisMapel')
          ->groupby('majdMapelKode')
          ->get();
      
      
      //Cache::put('SelectMataPelajaran'.$skl.$rombel.$id, $data3, ChaceJam());
    }
    //cache data redis ---------------------------------------------------------------------------
    return $data3;
  }
  
  // filter jadwal guru berdasarkan sekolah dan kelas
  public function SelectJadwalGuru(Request $request){
    $id =Auth::user()->ugrId;
    $skl=decrypt_url($request->skl);
    $kelas=decrypt_url($request->kelas);

    if (Cache::has('SelectJadwalGuru'.$id.$skl.$kelas)){ $data = Cache::get('SelectJadwalGuru'.$id.$skl.$kelas); }
    else{ 
      if(empty($id)){

      }
      else{
        if($skl == 'ALL'){
          $data = Master_mapel_jadwal::where('majdUgrId',$id)
          ->where('majdKlsKode',$kelas)
          ->select('majdMapelKode','majdNama','majdJenisMapel')
          ->groupby('majdMapelKode')
          ->get();
        }
        else{
          $data = Master_mapel_jadwal::where('majdUgrId',$id)
          ->where('majdSklId',$skl)
          ->where('majdKlsKode',$kelas)
          ->select('majdMapelKode','majdNama','majdJenisMapel')
          ->groupby('majdMapelKode')
          ->get();
        }
        
      }
      Cache::put('SelectJadwalGuru'.$id.$skl.$kelas, $data, ChaceJam());
    }
    return $data;
      
  }

  public function SelectSiswa(Request $request)
  {
    $id = Crypt::decrypt($request->id);
    if(!empty($id)){
      $data =  Anggota_rombel::Where('arbRblId',$id)->with('user_siswa')->get();
      foreach ($data as $val) {
        $data2 = array(
          'idsiswa' => Crypt::encrypt($val->user_siswa->ssaId),
          'namasiswa' => $val->user_siswa->ssaFirstName.' '.$val->user_siswa->ssaLastName,
        );
        $data3[] = $data2;
      }
    }else{ $data3[]=''; }
    return $data3;
  }
  public function SelectSiswaUsername(Request $request)
  {
    $id = Crypt::decrypt($request->id);
    if(!empty($id)){
      $data =  Anggota_rombel::Where('arbRblId',$id)->with('user_siswa')->get();
      foreach ($data as $val) {
        $data2 = array(
          'idsiswa' => Crypt::encrypt($val->arbSsaId),
          'namasiswa' => $val->user_siswa->ssaFirstName.' '.$val->user_siswa->ssaLastName,
        );
        $data3[] = $data2;
      }
    }else{ $data3[]=''; }
    return $data3;
  }

  //tampilkan data smp berdasarkan smp atau mts
  function FilterSmpSelect(Request $request){
    //cache data redis ---------------------------------------------------------------------------
    if (Cache::has('FilterSmpSelect')){ $data = Cache::get('FilterSmpSelect'); }
    else{ 
      $data =  Master_smp::Where('smpKategori',$request->id)->orderBy('smpNama', 'ASC')->get();
      Cache::put('FilterSmpSelect', $data, ChaceJam());
    }
    //cache data redis ---------------------------------------------------------------------------
    $json = json_encode($data);
    echo $json;
  }


  //untuk menampilkan nama kolom pada select yang akan di update
  function ListNamaKolomUpdateDataSiswa(){
    $data[] = array('value' => '','nama'=>'Pilih Kolom Update');
    
    $data[] = array('value' => 'ssaFirstName','nama'=>'Nama Depan');
    $data[] = array('value' => 'ssaLastName','nama'=>'Nama Belakang');
    $data[] = array('value' => 'ssaEmail','nama'=>'Email');
    $data[] = array('value' => 'ssaQrCode','nama'=>'Barcode');
    $data[] = array('value' => 'ssaTahunAngkata','nama'=>'Tahun Angkata');
    $data[] = array('value' => 'ssaAgama','nama'=>'Agama Siswa');

    $data[] = array('value' => 'psJsk','nama'=>'Jenis Kelamin');
    $data[] = array('value' => 'psTpl','nama'=>'Tempat Lahir');
    $data[] = array('value' => 'psTgl','nama'=>'Tanggal Lahir');
    $data[] = array('value' => 'psNis','nama'=>'NIS');
    $data[] = array('value' => 'psNisn','nama'=>'NISN');
    $data[] = array('value' => 'psNik','nama'=>'NIK');
    $data[] = array('value' => 'psNoKK','nama'=>'No KK');
    
    $data[] = array('value' => 'psHobi','nama'=>'Hobi Siswa');
    $data[] = array('value' => 'psTinggiBadan','nama'=>'Tinggi Badan Siswa');

    $data[] = array('value' => 'psJarak','nama'=>'Jarak Ke Sekolah');
    $data[] = array('value' => 'psAlamat','nama'=>'Alamat Siswa');
    $data[] = array('value' => 'psRt','nama'=>'RT');
    $data[] = array('value' => 'psRw','nama'=>'RW');
    $data[] = array('value' => 'psDesa','nama'=>'Desa');
    $data[] = array('value' => 'psKecamatan','nama'=>'Kecamatan');
    $data[] = array('value' => 'psKabupaten','nama'=>'Kabupaten');
    $data[] = array('value' => 'psProvinsi','nama'=>'Provinsi');

    $data[] = array('value' => 'psNoKKS','nama'=>'No KKS');
    $data[] = array('value' => 'psNoPKH','nama'=>'No PKH');
    $data[] = array('value' => 'psProvinsi','nama'=>'No KIP');
    $data[] = array('value' => 'psNoKip','nama'=>'Provinsi');
    $data[] = array('value' => 'psAsalSmp','nama'=>'Asal SMP');

    $data[] = array('value' => 'psNamaAyah','nama'=>'Nama Ayah');
    $data[] = array('value' => 'psNamaIbu','nama'=>'Nama Ibu');
    $data[] = array('value' => 'psNamaWali','nama'=>'Nama Wali');

    $data[] = array('value' => 'psHpIbu','nama'=>'No Hp Ayah');
    $data[] = array('value' => 'psHpAyah','nama'=>'No Hp Ibu');
    $data[] = array('value' => 'psHpWali','nama'=>'No Hp Wali');

    $json = json_encode($data);
    echo $json;
  }
	


	
}
