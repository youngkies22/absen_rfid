<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Auth;//menjalankan printah auth
use DB;
use DataTables;
use App\Sekolah;
use App\Tahun_ajaran;
use App\Semester;
/*
1.sekolah
2.jabatan
3.agama
4.penghasilan
5.transportasi
6.tahunajaran
7.semester
8.tingkat_pendidikan
*/


class CadminSekolah extends Controller
{
	public function __construct()
  {
    $this->middleware('auth:admin');
  }

  function getSkl() //ambil id sekolah dari data user
  {
  	$idskl = Auth::user()->admSklId; 
  	return $idskl;
  }
  function getSekolah() //ambil data sekolah
  {
    $data = new Sekolah();
    return $data->getSekolah();
  }
  function getJabatan()
  {
    if (Cache::has('master_jabatan1')){ 
      $jabatan = Cache::get('master_jabatan1'); 
    }
    else{ 
       $jabatan = Master_jabatan::where('mjbIsActive',1)->get();
       $chace = Cache::put('master_jabatan1', $jabatan, ChaceJam());
    }
    return $jabatan;
  }
  
//1.sekolah
  public function lihatSekolah()
  {
    $params = [
      'title' =>'Data Sekolah',
      'label' =>'<b>DAFTAR DATA SEKOLAH</b> ',
    ];
    return view('crew/sekolah/viewskl')->with($params);
  }

	public function add()
	{
		$params = [
      'title' =>'Tambah Sekolah',
      'label' =>'<b>TAMBAH SEKOLAH</b> ',

    ];
    return view('crew/sekolah/add-skl')->with($params);
		
	}
  public function edit($id)
  {
    $idd = Crypt::decrypt($id);
    $data_skl = Sekolah::find($idd);
    $params = [
      'title' =>'Edit Sekolah',
      'label' =>'<b>FORM EDIT DATA SEKOLAH</b>',
      'idskl' =>$id,

      'getSkl' =>$data_skl,
    ];
    return view('crew/sekolah/edit-skl')->with($params);
  }
  
	
  public function UpdateSekolah($id,Request $request)
  {
    $idd = Crypt::decrypt($id);
   
    $skl = Sekolah::find($idd);
    
    $skl->sklNpsn = $request->npsn_skl;
    $skl->sklNis = $request->nis_skl;
    $skl->sklKode = $request->kode_skl;
    $skl->sklNama = $request->nama_skl;
    $skl->sklAlamat = $request->alamat_skl;
    //$skl->sklEmail = $request->email_skl;
    $skl->sklProvinsi = $request->provinsi;
    $skl->sklKabupaten = $request->kabupaten;
    $skl->sklKecamatan = $request->kecamatan;

    $skl->sklKepalaSekolah = $request->kepsek;
    $skl->sklNipKepsek = $request->nip;
    $skl->sklJamIn = $request->jamin;
    $skl->sklJamOut = $request->jamout;

    $skl->sklCekJamAbsen = $request->cekjam;

    if($skl->save()){ 
      $response = ['save'=>'Berhasil Update Sekolah'];
    }
    else{ $response = ['error'=>'Opsss Gagal !!!'];}
    
    return response()->json($response,200);
  }

	public function jsonSekolah()
	{
    
    $data = Sekolah::get();
     

    $dt= DataTables::of($data)
    ->addColumn('aksi',function ($data) { 
      $id = Crypt::encrypt($data->sklId);
      if(AksiUpdate()){
        $button = '<a href="'.$id.'/edit-sekolah" title="Edit Data" class="btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"></i></a> ';
      } else{ $button =""; }
      
      return $button;
      
    })->rawColumns(['aksi']);
    return $dt->make(true);  
	}
  
  
  public function DeleteSekolah($id)
  {
    
  }
//6.tahunajaran
  public function LihatTahunAjaran()
  {
    $params = [
      'title' =>'Data Tahun Ajaran',
      'label' =>'<b>DAFTAR DATA TAHUN AJARAN</b> ',
      'getTa' => Tahun_ajaran::all(),
    ];
    return view('crew/tahun_ajaran/view_tahun_ajaran')->with($params);
  }
  public function InsertTahunAjaran(Request $request)
  {
   
    $data = Tahun_ajaran::where('tajrKode',$request->taKode);
    if($data->count() > 0){
      $response = [
        'status'=>'gagal',
        'error'=>'Upss Kode Tahun Ajaran Sudah Ada'
      ];
    }
    else{
        $data = new Tahun_ajaran();
        $data->tajrKode = $request->taKode;
        $data->tajrNama = $request->taNama;
        $data->tajrDescription = $request->taKtr;

        $data->tajrCreatedBy = Auth::user()->admId;
        if($data->save()){ 
          $response = [
            'status'=>200,
            'success'=>'Berhasil Tambah Tahun Ajaran'];
        }
        else{ $response = [ 
          'status'=>'eror',
          'error'=>'Opsss Gagal !!!'
        ];}
     
    }
    return response()->json($response,200);

  }
  public function UpdateTahunAjaran(Request $request){
    
    
    if(empty($request->taKode)){
      $response = [
        'status'=>'gagal',
        'error'=>'Upss Kode Tahun Ajaran Tidak Boleh Kosong'
      ];
    }
    else{
      $id = decrypt_url($request->taKode2);

      $data = Tahun_ajaran::find($id);
      $data->tajrKode = $request->taKode;
      $data->tajrNama = $request->taNama;
      $data->tajrDescription = $request->taKtr;
      $data->tajrIsActive = $request->taStatus;
      $data->tajrUpdatedBy = Auth::user()->admId;

      if($data->save()){ 
        $response = [
          'status'=>200,
          'success'=>'Berhasil Update Tahun Ajaran'];
        }
      else{ $response = [ 
        'status'=>'eror',
        'error'=>'Opsss Gagal !!!'
        ];
      }
     
     }
    return response()->json($response,200);

  }
//7.semester
  public function LihatSemester()
  {
    $params = [
      'title' =>'Data Semester',
      'label' =>'<b>DAFTAR DATA SEMESTER</b> ',
      'getSemester' => Semester::with('Sekolah','tahun_ajaran')->get(),
      'getSekolah' => $this->getSekolah(),
      'getTa' => Tahun_ajaran::all(),
    ];
    return view('crew/semester/view_semester')->with($params);
  }
  public function InsertSemester(Request $request){
      $data = new Semester;
      $data->smTajrId = $request->ta;
      $data->smKode = $request->smkode;
      $data->smNama = $request->smnama;
      if($data->save()){ 
        $response = [
          'status'=>200,
          'success'=>'Berhasil Tambah Semester'];
        }
      else{ $response = [ 
        'status'=>'eror',
        'error'=>'Opsss Gagal !!!'
        ];
      }
      return response()->json($response,200);
  }
  public function UpdateSemester(Request $request){
      $id = decrypt_url($request->id);
      $data = Semester::find($id);
      $data->smKode = $request->kode;
      $data->smNama = $request->nama;
      $data->smIsActive = $request->status;
      if($data->save()){ 
        $response = [
          'status'=>200,
          'success'=>'Berhasil Tambah Semester'];
        }
      else{ $response = [ 
        'status'=>'eror',
        'error'=>'Opsss Gagal !!!'
        ];
      }
      return response()->json($response,200);
  }


}
