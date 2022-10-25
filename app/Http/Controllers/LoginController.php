<?php
//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //menjalankan printah Hash Enkripsi

use Auth;//menjalankan printah auth

class LoginController extends Controller
{
	public function getLogin()
	{
		if(Auth::guard('admin')->check()){
			return redirect()->route('homeAdmin');
		}
		else{
			return view('login_admin');
		}
	}
	public function getLoginAdmin(){
		// cek apakah session login masih ada 
		if(Auth::guard('admin')->check()){
			return redirect()->route('homeAdmin');
		}
		else{
			return view('login_admin');
		}
	}

	public function CekLogin(Request $request)
	{
			
			// admin -------------------------------------------------------
			if($request->jabatan == 'ADM'){ //jika jabtan admin
				//validasi reques yang masuk
				$this->validate($request, [
					'username' => 'required', 
					'jabatan' => 'required', 
					'password' => 'required|string|min:6',
				]);
				$credentials = [
					'admUsername' => $request->username, 
					'password' => $request->password,
				];
				if (Auth::guard('admin')->attempt($credentials)) { return redirect()->route('homeAdmin'); }
				else{ return redirect()->route('login')->with(['error' => 'Username/Password salah!']); }
				
			}
		
			
	}
	public function logout(){
		if(Auth::guard('admin')->check()){
			Auth::guard('admin')->logout();
			return redirect()->route('login');
		}
		
		else{
			return redirect()->route('login.user')->with(['success' => 'Berhasil Logout']);
		}
		
    
	}

}
