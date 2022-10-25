<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi
use Illuminate\Support\Facades\Crypt;

use Auth;//menjalankan printah auth
use App\User;
use App\User_guru;
use App\Master_sekolah;
use App\Master_jabatan;
use App\Setting;

class CadminAkun extends Controller
{
	public function __construct()
  {
    $this->middleware('auth:admin');
	}
 
	public function index()
	{
		$params = [
			'title'	=>'Setting Akun Admin',
			'label'	=>'<b>SETTING AKUN ADMIN </b>',
			'idUser'	=> Crypt::encrypt(Auth::user()->admId),
			
		];
		return view('crew/akun/profile')->with($params);
	}
	function UpdateAdmin(Request $request){
		$idd = Crypt::decrypt($request->id);
		$data = User::find($idd);
		$data->admFullName	= $request->nama;

		if(!empty($request->newpassword)){
			$data->admPassword	= Hash::make($request->newpassword);
		}

		if($data->save()){
			$response = ['save'=>'Berhasil Update Data Akun'];
		}
		return response()->json($response,200);
		
	}
	function DeleteAdmin(Request $request){
		$idd = decrypt_url($request->id);
		$data = User::find($idd);

		if($data->delete()){
			$response = ['save'=>'Berhasil Hapus Data Akun'];
		}
		return response()->json($response,200);
	}
	//tampilkan akun admin
	function listAkunAdmin(){
		$params = [
			'title'	=>'Data Akun Admin',
			'label'	=>'<b>DATA AKUN ADMIN </b>',
			'no'=>1,
			'getData' => User::get(),
		];
		return view('crew/akun/view_admin')->with($params);
	}
	function add(){
		$params = [
			'title'	=>'Data Akun Admin',
			'label'	=>'<b>DATA AKUN ADMIN </b>',
			'no'=>1,
			
		];
		return view('crew/akun/add_admin')->with($params);
	}

	function edit($id){
		$idd = decrypt_url($id);
		$data = User::find($idd);
		$params = [
			'title'	=>'Data Akun Admin',
			'label'	=>'<b>DATA AKUN ADMIN </b>',
			'no'=>1,
			
			'id'	=>$idd,
			'dataAdmin'	=>$data,
		];
		return view('crew/akun/edit_admin')->with($params);
	}

	function insertAdmin(Request $request)
	{
		

		$data = new User();
		$data->admKode = $request->akses;
		$data->admUsername = $request->username;
		$data->admPassword = Hash::make($request->password);
		$data->admFullName = $request->name;
		

		if($data->save()){
			$response = ['save'=>'Berhasil Tambah Akun'];
		}
		else{
			$response = ['error'=>'Opss Gagal '];
		}
		return response()->json($response,200);

	}

	function updateAkunAdmin(Request $request)
	{
		$id = $request->id;
		$data = User::find($id);
		if(empty($request->password)){
			
			$data->admKode = $request->akses;
			$data->admUsername = $request->username;
			$data->admFullName = $request->name;
			$data->admInsert = $request->insert;
			$data->admUpdate = $request->update;
			$data->admDelete = $request->delete;

		}else{
			
			$data->admKode = $request->akses;
			$data->admUsername = $request->username;
			$data->admPassword = Hash::make($request->password);
			$data->admFullName = $request->name;
			$data->admInsert = $request->insert;
			$data->admUpdate = $request->update;
			$data->admDelete = $request->delete;
		}
		
		if($data->save()){
			$response = ['save'=>'Berhasil Update Akun'];
		}
		else{
			$response = ['error'=>'Opss Gagal '];
		}
		return response()->json($response,200);

	}

	function ResetPassword(Request $request){
		$set = Setting::first();
    $idd = decrypt_url($request->id);
    $data = User::find($idd);
    $data->admPassword  = Hash::make($set->setResetPassAdmin);
    $data->admUpdated = date("Y-m-d H:i:s");
    $data->admUpdatedBy = Auth::user()->admId;
    
    if($data->save()){
      return response()->json([
        'success' => 'Akun Berhasil Di Reset Passwordnya '.$set->setResetPassAdmin,
      ]);
    }
    else{
      return response()->json([
        'error' => 'Opsss Gagal !'
      ]);
    }
	}

	/**
	 * Form Logo Sekolah
	 * upload logo sekolah dan backgrup halaman login
	 * 24-10-2022
	 */
	public function LogoSekolah(){
		$params = [
			'title'	=>'Logo Sekolah',
			'label'	=>'<b>Logo Sekolah</b>',
		];
		return view('crew/sekolah/form_logo')->with($params);
	}

	
}
