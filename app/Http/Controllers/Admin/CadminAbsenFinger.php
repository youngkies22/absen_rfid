<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use DataTables;
use App\Sekolah;
use App\Absen;
use App\Tahun_ajaran;
use App\Semester;
use App\User_guru;
use Carbon\Carbon;
use PhpParser\Node\Stmt\Return_;

class CadminAbsenFinger extends Controller
{
	public function __construct()
  {
    //$this->middleware('auth:admin');
  }

  function getSkl()
  {
  	$data = Sekolah::first();
  	return $data->sklId;
  }
  function getDataSkl()
  {
  	$data = Sekolah::first();
  	return $data;
  }


  function getBulanTahunAbsen(){
    
    $data = Absen::select(DB::raw('MONTH(hgTgl) AS bulan,YEAR(hgTgl) AS tahun'))
    ->groupBy('tahun')
    ->get();
     
    return $data;
    
  }
  function getTahunAjaranNama()
	{ //ambil kode tahun ajaran yg aktif
		
			$data = Tahun_ajaran::where('tajrIsActive',1)->first();
		
		return $data->tajrKode;
	}
  function getTahunAjaranNama2()
	{ //ambil kode tahun ajaran yg aktif
		
			$data = Tahun_ajaran::where('tajrIsActive',1)->first();
		
		return $data->tajrNama;
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
  
//---------------------------------------------------------------------------------------------------------
  function add()
  {
    $params = [
      'title' =>'Tambah Absensi',
      'label' =>'<b>TAMBAH ABSENSI</b> ',
      'guru'  => $this->getDataGuru(),
      'jam_masuk' => $this->getDataSkl()->sklJamIn,
      'jam_pulang' => $this->getDataSkl()->sklJamOut,
    ];
    return view('crew/absen_user/add_absen')->with($params);
  }
 
  public function LihatAbsenFinger()
  {
    $params = [
      'title' =>'Absensi',
      'label' =>'DATA ABSENSI ',
      'getBulanTahunAbsen' => $this->getBulanTahunAbsen(),

    ];
    return view('crew/absen_user/view_absen')->with($params);
  }
  function jsonAbsenFinger(Request $request)
  {
    $thn = $request->input('thn');
    $bln = $request->input('amp;bln');
    $hari = $request->input('amp;hari');
    

      if(empty($hari)){
        $data = Absen::whereYear('hgTgl', $thn)
          ->whereMonth('hgTgl', $bln)
          ->get();
          
      }
      else{
        $data = Absen::whereYear('hgTgl', $thn)
          ->whereMonth('hgTgl', $bln)
          ->whereDay('hgTgl', $hari)
          ->get();
      }

      $dt =  DataTables::of($data)
      ->addColumn('no','')
      ->addColumn('hari',function ($data) { 
          return hariIndo($data->hgHari);
        })
      ->addColumn('aksi',function ($data) { 
        $id = Crypt::encrypt($data->hgId);
        $button = '<a
        data-id="'.$data->hgId.'"
        data-jamin="'.$data->hgJamIn.'"
        data-jamout="'.$data->hgJamOut.'"
        data-tgl="'.$data->hgTgl.'" 
        data-status="'.$data->hgKodeAbsen.'" 
        data-toggle="modal" data-target="#modal_form_vertical"
        title="Edit Data" class="btnmodal btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple " ><i class="icon-pencil7"></i></a> ';
        $button .='<a title="Hapus Data" id="delete" class="btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'.$id.'"><i class="icon-trash"></i></a>';
        return $button;
        })->rawColumns(['aksi']);
      return $dt->make();
      
    
  }
  function InsertAbsenFinger(Request $request){
    
    $guruData = User_guru::where('ugrUsername',$request->user)->first();

    if(!empty($guruData->ugrGelarDepan)){
      $namaGuru =$guruData->ugrGelarDepan.'. '.$guruData->ugrFullName.', '.$guruData->ugrGelarBelakang;
    }
    else if(empty($guruData->ugrGelarDepan) and empty($guruData->ugrGelarBelakang)){
      $namaGuru =$guruData->ugrFullName;
    }
    else{
      $namaGuru =$guruData->ugrFullName.', '.$guruData->ugrGelarBelakang;
    }

    $user = $request->user;
    $nama = $namaGuru;
    $status = $request->status;
    $tgl = date('Y-m-d',strtotime($request->tgl));
    $tahun = date('Y',strtotime($request->tgl));
    $hari = date('l', strtotime($request->tgl));
    $skl = $this->getSkl();
    $jamin = $request->jamin;
    $jamout = $request->jamout;
    $tahun_ajaran = $this->getTahunAjaranNama();
    $data = [];
    $cekabsen = Absen::where('hgUserGuru',$user)->where('hgTgl',$tgl)->count();
    //cek absen siswa berdasarkan tanggal
    if($cekabsen > 0){ 
      $response = ['error'=>'User Dengan Tanggal Ini Sudah Ada Absensinya !!!'];
    }
    else{
      $data[] = array(
        'hgSklId'  =>$skl,
        'hgTajrKode'  =>$tahun_ajaran,
        'hgTahun'   =>$tahun,
        'hgUserGuru' =>$user,
        'hgNamaFull' =>$nama,
        'hgTgl'     =>$tgl,
        'hgHari'    => $hari,
        'hgJamIn'    => $jamin,
        'hgJamOut'    =>$jamout,
        'hgKodeAbsen' =>$status,
        'hgJenisAbsen' =>2, //1 mesin, 2 user
      );
 
      $insert = Absen::insert($data);
      if($insert){ 
        
        $response = ['save'=>'Berhasil Tambah Absensi']; 
      }
      else{ $response = ['error'=>'Opsss Gagal !!!'];}
    }
    return response()->json($response,200);
  }

  function UpdateAbsenFinger(Request $request){
    
    $id = $request->id;
    $jamin = $request->jamin;
    $jamout = $request->jamout;
    $status = $request->status;
    
    $data               = Absen::find($id);
    $data->hgJamIn      = $jamin;
    $data->hgJamOut     = $jamout;
    $data->hgKodeAbsen  = $status;
    $data->hgCreated    = date('Y-m-d H:i:s');
    if($data->save()){
      $response = ['save'=>'Berhasil Update Absensi']; 
    } 
    else{
      $response = ['error'=>'Opsss Gagal !!!'];
    }
    return response()->json($response,200);

  }


 
  public function ViewRekapAbsenFinger(){

    $params = [
      'title' =>'Rekap Absen',
      'label' =>'REKAP ABSEN ',
      'getBulanTahunAbsen' => $this->getBulanTahunAbsen(),

    ];
    
    
    return view('crew/absen_user/view_rekap_absen_sekolah')->with($params);
  }
  public function CetakViewRekapAbsenFinger(Request $request){
    $thn = $request->input('thn');
    $bln = $request->input('bln');
    $hari = $request->input('hari');

    //$thn = date('Y',strtotime(date('Y-m-d')));
    $thn_bulan = $thn.'0'.$bln;
    $thn_bulan2 = $thn.'-'.$bln.'-';
    
    if(empty($hari) or $hari == 0){
      $data = Absen::whereYear('hgTgl', $thn)
      ->whereMonth('hgTgl', $bln)
      ->selectRaw("*,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=1,hgKodeAbsen,NULL))) AS tgl1,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=2,hgKodeAbsen,NULL))) AS tgl2,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=3,hgKodeAbsen,NULL))) AS tgl3,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=4,hgKodeAbsen,NULL))) AS tgl4,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=5,hgKodeAbsen,NULL))) AS tgl5,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=6,hgKodeAbsen,NULL))) AS tgl6,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=7,hgKodeAbsen,NULL))) AS tgl7,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=8,hgKodeAbsen,NULL))) AS tgl8,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=9,hgKodeAbsen,NULL))) AS tgl9,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=10,hgKodeAbsen,NULL))) AS tgl10,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=11,hgKodeAbsen,NULL))) AS tgl11,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=12,hgKodeAbsen,NULL))) AS tgl12,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=13,hgKodeAbsen,NULL))) AS tgl13,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=14,hgKodeAbsen,NULL))) AS tgl14,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=15,hgKodeAbsen,NULL))) AS tgl15,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=16,hgKodeAbsen,NULL))) AS tgl16,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=17,hgKodeAbsen,NULL))) AS tgl17,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=18,hgKodeAbsen,NULL))) AS tgl18,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=19,hgKodeAbsen,NULL))) AS tgl19,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=20,hgKodeAbsen,NULL))) AS tgl20,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=21,hgKodeAbsen,NULL))) AS tgl21,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=22,hgKodeAbsen,NULL))) AS tgl22,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=23,hgKodeAbsen,NULL))) AS tgl23,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=24,hgKodeAbsen,NULL))) AS tgl24,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=25,hgKodeAbsen,NULL))) AS tgl25,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=26,hgKodeAbsen,NULL))) AS tgl26,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=27,hgKodeAbsen,NULL))) AS tgl27,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=28,hgKodeAbsen,NULL))) AS tgl28,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=29,hgKodeAbsen,NULL))) AS tgl29,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=30,hgKodeAbsen,NULL))) AS tgl30,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=31,hgKodeAbsen,NULL))) AS tgl31,
          SUM(CASE WHEN hgKodeAbsen='H' THEN 1 ELSE 0 END) AS HADIR,
          SUM(CASE WHEN hgKodeAbsen='A' THEN 1 ELSE 0 END) AS ALPHA,
          SUM(CASE WHEN hgKodeAbsen='I' THEN 1 ELSE 0 END) AS IZIN,
          SUM(CASE WHEN hgKodeAbsen='T' THEN 1 ELSE 0 END) AS TERLAMBAT,
          SUM(CASE WHEN hgKodeAbsen='S' THEN 1 ELSE 0 END) AS SAKIT
          ")
        ->groupBy('hgUserGuru')
        ->get();
    }
    else{
      $data = Absen::whereYear('hgTgl', $thn)
      ->whereMonth('hgTgl', $bln)
      ->whereDay('hgTgl', $hari)
      ->selectRaw("*,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=1,hgKodeAbsen,NULL))) AS tgl1,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=2,hgKodeAbsen,NULL))) AS tgl2,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=3,hgKodeAbsen,NULL))) AS tgl3,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=4,hgKodeAbsen,NULL))) AS tgl4,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=5,hgKodeAbsen,NULL))) AS tgl5,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=6,hgKodeAbsen,NULL))) AS tgl6,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=7,hgKodeAbsen,NULL))) AS tgl7,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=8,hgKodeAbsen,NULL))) AS tgl8,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=9,hgKodeAbsen,NULL))) AS tgl9,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=10,hgKodeAbsen,NULL))) AS tgl10,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=11,hgKodeAbsen,NULL))) AS tgl11,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=12,hgKodeAbsen,NULL))) AS tgl12,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=13,hgKodeAbsen,NULL))) AS tgl13,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=14,hgKodeAbsen,NULL))) AS tgl14,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=15,hgKodeAbsen,NULL))) AS tgl15,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=16,hgKodeAbsen,NULL))) AS tgl16,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=17,hgKodeAbsen,NULL))) AS tgl17,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=18,hgKodeAbsen,NULL))) AS tgl18,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=19,hgKodeAbsen,NULL))) AS tgl19,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=20,hgKodeAbsen,NULL))) AS tgl20,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=21,hgKodeAbsen,NULL))) AS tgl21,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=22,hgKodeAbsen,NULL))) AS tgl22,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=23,hgKodeAbsen,NULL))) AS tgl23,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=24,hgKodeAbsen,NULL))) AS tgl24,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=25,hgKodeAbsen,NULL))) AS tgl25,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=26,hgKodeAbsen,NULL))) AS tgl26,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=27,hgKodeAbsen,NULL))) AS tgl27,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=28,hgKodeAbsen,NULL))) AS tgl28,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=29,hgKodeAbsen,NULL))) AS tgl29,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=30,hgKodeAbsen,NULL))) AS tgl30,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=31,hgKodeAbsen,NULL))) AS tgl31,
          SUM(CASE WHEN hgKodeAbsen='H' THEN 1 ELSE 0 END) AS HADIR,
          SUM(CASE WHEN hgKodeAbsen='A' THEN 1 ELSE 0 END) AS ALPHA,
          SUM(CASE WHEN hgKodeAbsen='I' THEN 1 ELSE 0 END) AS IZIN,
          SUM(CASE WHEN hgKodeAbsen='T' THEN 1 ELSE 0 END) AS TERLAMBAT,
          SUM(CASE WHEN hgKodeAbsen='S' THEN 1 ELSE 0 END) AS SAKIT
          ")
        ->groupBy('hgUserGuru')
        ->get();
    }    
    
   
    $params = [
      'judul' =>'REKAPITULASI ABSENSI',
      'sekolah' =>$this->getDataSkl()->sklNama,
      'ajaran'	=>'TAHUN PELAJARAN ' .$this->getTahunAjaranNama2(),
      'ajaran2' =>$this->getTahunAjaranNama2(),
      'absen' => $data,
      'bulan'	=> bulanIndo($bln),
      'kecamatan'	=> $this->getDataSkl()->sklKecamatan,
      'kepsek'    =>'Kepala Sekolah',
      'tgl' => tgl_indo(date('Y-m-d')),
      'namaKepsek'  => $this->getDataSkl()->sklKepalaSekolah,
      'nikepsek'    => $this->getDataSkl()->sklNipKepsek,
      'tahun'  => $thn,
      'thn_bulan'     => $thn_bulan,
			'thn_bulan2'    => $thn_bulan2

    ];
    return view('crew/absen_user/cetak_absen_sekolah')->with($params);
    

  }

  function DeleteAbsenFinger(Request $request){
    $id = Crypt::decrypt($request->id);
    $data = Absen::find($id);
    if($data->delete()){
      $response = ['save'=>'Berhasil Hapus Absensi']; 
    }
    else{ $response = ['error'=>'Opsss Gagal !!!'];}
    return response()->json($response,200);
  }
 
  public function jsonAbsenFingerLive()
  {
    
    $thn  = Carbon::now()->format('Y');
    $bln  = Carbon::now()->format('m');
    $hari = Carbon::now()->format('d'); 
    
    $data = Absen::whereYear('hgTgl', $thn)
    ->whereMonth('hgTgl', $bln)
    ->whereDay('hgTgl', $hari)
    //->orderBy('hgCreated','desc')
    ->get();
    
    $dt =  DataTables::of($data);
    return $dt->make();
    
  }
  public function jsonAbsenFingerLiveMonitor($id){
    if($id == 'jsonSmkBudiUtomo'){

      $thn  = Carbon::now()->format('Y');
      $bln  = Carbon::now()->format('m');
      $hari = Carbon::now()->format('d'); 
      $data = Absen::whereYear('hgTgl', $thn)
      ->whereMonth('hgTgl', $bln)
      ->whereDay('hgTgl', $hari)
      ->orderBy('hgCreated','desc')
      ->get();
      
      $dt =  DataTables::of($data)
      ->addColumn('hari',function ($data) { 
        return hariIndo($data->hgHari);
      })
      ->addColumn('tanggal',function ($data) { 
        return formatTanggalIndo($data->hgTgl);
      })
      ->addColumn('jamMasuk',function ($data) { 
        if(empty($data->hgJamIn)){
          $jamIn = "00:00";
        }
        else{
          $jamIn = $data->hgJamIn;
        }
        return $jamIn;
      })
      ->addColumn('jamPulang',function ($data) { 
        if(empty($data->hgJamOut)){
          $jamOut = "00:00";
        }
        else{
          $jamOut = $data->hgJamOut;
        }
        return $jamOut;
      })
      ->addColumn('status',function ($data) { 
        if($data->hgKodeAbsen == "T"){
          $status = "<span class='badge badge-warning'>TERLAMBAT</span>";
        }
        elseif($data->hgKodeAbsen == "A"){
          $status = "<span class='badge badge-danger '>ALPHA</span>";
        }
        else{
          $status = "<span class='badge badge-primary '>HADIR</span>";
        }
        return $status;
      })
      ->addColumn('tanggalWaktu',function ($data) { 
        //return $data->hgTgl.' '.$data->hgJamIn;
        return $data->hgCreated;
      });
      return $dt->rawColumns(['status'])->make();
    }
    else{
      $data =[];
      $dt =  DataTables::of($data);
      return $dt->make();
    }
  }
  public function formLive(){
   
    return view('crew/absen_user/live');
  }

  /**
   * view form rekap absensi rentang
   * 23-10-2022
   */
  public function ViewRekapAbsenRentang(){
    $params = [
      'title' =>'Rekap Absen ',
      'label' =>'REKAP ABSENSI RENTANG',
      'getBulanTahunAbsen' => $this->getBulanTahunAbsen(),

    ];
    
    
    return view('crew/absen_user/view_rekap_absen_rentang')->with($params);
  }

  public function CetakViewRekapAbsenRentang(Request $request){
    $tgl = $request->input('tgl');
    $tgl2 = $request->input('tgl2');
    
    $date = date('Y-m-d', strtotime($tgl));
    $date2 = date('Y-m-d', strtotime($tgl2));

    $thn = date('Y',strtotime($date));
    $bln = date('m',strtotime($date));
    $thn_bulan = $thn.'0'.$bln;
    $thn_bulan2 = $thn.'-'.$bln.'-';

    $data = Absen::whereBetween('hgTgl', [$date,$date2])
      ->selectRaw("*,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=1,hgKodeAbsen,NULL))) AS tgl1,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=2,hgKodeAbsen,NULL))) AS tgl2,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=3,hgKodeAbsen,NULL))) AS tgl3,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=4,hgKodeAbsen,NULL))) AS tgl4,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=5,hgKodeAbsen,NULL))) AS tgl5,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=6,hgKodeAbsen,NULL))) AS tgl6,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=7,hgKodeAbsen,NULL))) AS tgl7,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=8,hgKodeAbsen,NULL))) AS tgl8,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=9,hgKodeAbsen,NULL))) AS tgl9,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=10,hgKodeAbsen,NULL))) AS tgl10,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=11,hgKodeAbsen,NULL))) AS tgl11,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=12,hgKodeAbsen,NULL))) AS tgl12,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=13,hgKodeAbsen,NULL))) AS tgl13,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=14,hgKodeAbsen,NULL))) AS tgl14,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=15,hgKodeAbsen,NULL))) AS tgl15,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=16,hgKodeAbsen,NULL))) AS tgl16,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=17,hgKodeAbsen,NULL))) AS tgl17,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=18,hgKodeAbsen,NULL))) AS tgl18,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=19,hgKodeAbsen,NULL))) AS tgl19,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=20,hgKodeAbsen,NULL))) AS tgl20,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=21,hgKodeAbsen,NULL))) AS tgl21,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=22,hgKodeAbsen,NULL))) AS tgl22,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=23,hgKodeAbsen,NULL))) AS tgl23,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=24,hgKodeAbsen,NULL))) AS tgl24,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=25,hgKodeAbsen,NULL))) AS tgl25,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=26,hgKodeAbsen,NULL))) AS tgl26,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=27,hgKodeAbsen,NULL))) AS tgl27,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=28,hgKodeAbsen,NULL))) AS tgl28,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=29,hgKodeAbsen,NULL))) AS tgl29,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=30,hgKodeAbsen,NULL))) AS tgl30,
          GROUP_CONCAT( DISTINCT CONCAT(IF(DAY(hgTgl)=31,hgKodeAbsen,NULL))) AS tgl31,
          SUM(CASE WHEN hgKodeAbsen='H' THEN 1 ELSE 0 END) AS HADIR,
          SUM(CASE WHEN hgKodeAbsen='A' THEN 1 ELSE 0 END) AS ALPHA,
          SUM(CASE WHEN hgKodeAbsen='I' THEN 1 ELSE 0 END) AS IZIN,
          SUM(CASE WHEN hgKodeAbsen='T' THEN 1 ELSE 0 END) AS TERLAMBAT,
          SUM(CASE WHEN hgKodeAbsen='S' THEN 1 ELSE 0 END) AS SAKIT
          ")
        ->groupBy('hgUserGuru')
        ->get();

    $params = [
      'judul' =>'REKAPITULASI ABSENSI',
      'sekolah' =>$this->getDataSkl()->sklNama,
      'ajaran'	=>'TAHUN PELAJARAN ' .$this->getTahunAjaranNama2(),
      'ajaran2' =>$this->getTahunAjaranNama2(),
      'absen' => $data,
      'bulan'	=> bulanIndo($bln),
      'kecamatan'	=> $this->getDataSkl()->sklKecamatan,
      'kepsek'    =>'Kepala Sekolah',
      'tgl' => tgl_indo(date('Y-m-d')),
      'namaKepsek'  => $this->getDataSkl()->sklKepalaSekolah,
      'nikepsek'    => $this->getDataSkl()->sklNipKepsek,
      'tahun'  => $thn,
      'thn_bulan'     => $thn_bulan,
			'thn_bulan2'    => $thn_bulan2

    ];
    return view('crew/absen_user/cetak_absen_sekolah')->with($params);
    

  }
 
  
}
