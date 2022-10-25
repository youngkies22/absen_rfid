<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Auth;
use Excel;
use DataTables;
use App\User_guru;
use App\Sekolah;
use App\Imports\ImportDataGuru;



class CadminGuru extends Controller
{
	public function __construct()
  {
    $this->middleware('auth:admin');
  }

  function getSkl()
  {
    $data = Sekolah::first();
  	return $data->sklId;
  }
  
  
 
	public function index()
	{
		
		//return view('crew/crew_home')->with($params);
	}
	public function lihatGuru()
	{	
		$params = [
			'title'	=>'Data Guru',
			'label'	=>'FORM DATA GURU ',
		];
		return view('crew/guru/viewguru')->with($params);
	}
  
  public function editGuru($id)
  {
    $idd = Crypt::decrypt($id);
    $dataguru = User_guru::find($idd);
    $params = [
      'title' =>'Edit Guru',
      'label' =>'<b>FORM EDIT DATA GURU</b>',
      'idguru' =>$id,
      'guru' =>$dataguru,
    ];
    return view('crew/guru/editguru')->with($params);
  }
  public function add()
  {
    $params = [
      'title' =>'Tambah Aku Guru',
      'label' =>'<b>FORM TAMBAH AKUN GURU </b>',
      
    ];
    return view('crew/guru/addguru')->with($params);
  }
  public function formImportSiswa(){
    $params = [
      'title' =>'Import Data Guru',
      'label' =>'<b>FORM IMPORT DATA GURU </b>',
    ];
    return view('crew/guru/form_import_data_guru')->with($params);
  }
  public function ImportDataGuru(Request $request)
  {
    $file = $request->file('import_data_guru');
    $extensi = $file->getClientOriginalExtension();

    if($extensi == 'xls' or $extensi == 'xlsx' ){
      $import = new ImportDataGuru();
      Excel::import($import,$file);
      $insert = get_object_vars($import); //mengubah objek ke data array
      if($insert['hasil'] ==1){
        $response = ['save'=>'Berhasil Upload Data '.$insert['jm'].' Guru'];
      }
      elseif ($insert['hasil'] == 0) {
        $response = ['error'=>'Gagal Upload Data Guru'];
      }
      else{
        $response = ['error'=>'Sistem Error'];
      }

    }
    else{
      $response = ['danger'=>'Cek File, Pastikan Ber extensi atau Format XLSX'];
     }
     
     return response()->json($response,200);    
  }

  function InsertGuru(Request $request)
  {
    $pesan =[
        'ugrUsername.required' =>'Username Tidak Boleh Kosong',
        'unique' => 'Username Sudah Terdaftar Di Database',
      ];

      $validator = Validator::make(request()->all(), [
        'ugrUsername' => 'required|unique:user_guru',
      ],$pesan);

    if($validator->fails()) {
      $response = $validator->messages();
    }
    else{ 
      $guru = new User_guru();
      
      $guru->ugrSklId = $this->getSkl();
      $guru->ugrUsername = request()->ugrUsername;
      $guru->ugrPassword = Hash::make("123456789");
      $guru->ugrGelarDepan = request()->gdepan;
			$guru->ugrGelarBelakang = request()->gbelakang;
      $guru->ugrFullName = strtoupper(request()->fullname);
      $guru->ugrNip = request()->nip;
      $guru->ugrHp = request()->nohp;
      $guru->ugrWa = request()->nowa;
      $guru->ugrTelegram = request()->telegram;
      $guru->ugrJsk = request()->jsk;

      if($guru->save()){ 
        $response = ['save'=>'Berhasil Tambah Data Guru'];
      }
      else{ $response = ['error'=>'Gagal Tambah Data Guru'];
      }

    }
    return response()->json($response,200);
  }
  function UpdateGuru($id,Request $request)
  {
    $idd = Crypt::decrypt($id);
    $pesan =[
        'ugrUsername.required' =>'Username Tidak Boleh Kosong',
      ];

      $validator = Validator::make(request()->all(), [
        'ugrUsername' => 'required',
      ],$pesan);

    if($validator->fails()) {
      $response = $validator->messages();
    }
    else{ 
      $guru = User_guru::find($idd);
      $guru->ugrUsername = request()->ugrUsername;
      $guru->ugrPassword = Hash::make("123456789");
      $guru->ugrGelarDepan = request()->gdepan;
			$guru->ugrGelarBelakang = request()->gbelakang;
      $guru->ugrFullName = strtoupper(request()->fullname);
      $guru->ugrNip = request()->nip;
      $guru->ugrHp = request()->nohp;
      $guru->ugrWa = request()->nowa;
      $guru->ugrTelegram = request()->telegram;
      $guru->ugrJsk = request()->jsk;
      

      if($guru->save()){ 
        $response = ['save'=>'Berhasil Update Data Guru'];
      }
      else{ $response = ['error'=>'Gagal Update Data Guru'];}
    }
    return response()->json($response,200);
  }
  function deleteGuru($id)
  {
    $idd = Crypt::decrypt($id);
    $guru= User_guru::find($idd);
    
    if($guru->delete()){
     
      return response()->json([
        'success' => 'Data Berhasil Di Hapus'
      ]);
    }
    else{
      return response()->json([
        'error' => 'Gagal Hapus Data!'
      ]);
    }
  }
  public function jsonGuru()
  {
    
    $data = User_guru::where('ugrIsActive',1)
    ->get();
    
    foreach ($data as $value) {
      $data2['id'] =$value->ugrId;
      $data2['username'] =$value->ugrUsername;
      $data2['telegram'] =$value->ugrTelegram;
      $data2['wa'] =$value->ugrWa;
      $data2['hp'] =$value->ugrHp;
      $data2['nip'] =$value->ugrNip;
      $data2['jsk'] =$value->ugrJsk;
      $data2['foto'] = FotoGuruSmall($value->ugrFotoProfile);
      

      if(!empty($value->ugrGelarDepan)){
        $data2['namafull'] =$value->ugrGelarDepan.'. '.$value->ugrFullName.', '.$value->ugrGelarBelakang;
      }
      else if(empty($value->ugrGelarDepan) and empty($value->ugrGelarBelakang)){
        $data2['namafull'] =$value->ugrFullName;
      }
      else{
        $data2['namafull'] =$value->ugrFullName.', '.$value->ugrGelarBelakang;
      }
      $data3[]= $data2;
    }
    
    $dt= DataTables::of($data3)
    
    ->addColumn('aksi',function ($data3) { 
      $id = Crypt::encrypt($data3['id']);
      
      if(AksiUpdate()){
        $button = '<a href="'.$id.'/edit-guru" title="Edit Data" class="dropdown-item btn btn-sm btn-outline bg-primary text-primary border-primary legitRipple" ><i class="icon-pencil7"> Edit</i></a>';
        
      }else{ $button = '<a title="No Akses" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" ><i class="icon-cancel-circle2"> No Akses</i></a>';  }
        if(AksiDelete()){
          $button .='<a title="Hapus Data" id="delete" class="dropdown-item btn btn-sm btn-outline bg-danger text-danger border-danger legitRipple" data-id="'.$id.'"><i class="icon-trash"> Hapus</i></a>';
        }
        $return  = '<ul class="list-inline list-inline-condensed mb-0 mt-2 mt-sm-0">
          <li class="list-inline-item dropdown">
            <a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
              '.$button.'
            </div>
          </li>
        </ul>';
        return $return;
        
    })->rawColumns(['aksi','foto']);
    return $dt->make(true); 
    
  }
 
 
 
	
}
