<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi

use Auth;//menjalankan printah auth
use App\User_siswa;
use App\Master_sekolah;

class CadminHome extends Controller
{
	public function __construct()
  {
    $this->middleware('auth:admin');
  }
 
	public function index()
	{
		$idskl = auth()->user()->admSklId;
		
		
		$params = [
			'title'	=>'HOME',
			//'all_siswa' => $query,
		];
		return view('crew/crew_home')->with($params);
	}


	
}
