<?php

//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;


use App\User_guru;
use App\Rfid_user;
use App\Rfid_mesin;
use App\Rfid_kartu_not_found;
use App\Absen;
use App\Semester;
use App\Tahun_ajaran;
use App\Sekolah;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;



class ApiRfidArduino extends Controller
{
    public function __construct()
    {
      $this->pesan = 'ABSENSI ANDA';
      $this->pesan2 = 'TELAH BERHASIL';
      
      $this->pesanGagal = 'GAGAL';
      $this->pesanGagal2 = 'ABSENSI ANDA';
      
    }
	// function Toket()
	// {
	// 	$token = Setting::first();
	// 	return $token->setTokenApi;
	// }

    function getArduinoCoba($token, $idkartu = null){
         $response = [
				'status'	=> 'oke',
				'pesan'		=> $this->pesan,
				'pesan2'    => $this->pesan2,
				'kode'		=> 200
			];
      return response()->json($response,200);
    }
    
	//function untuk kebutuhan variabel pada tabel absensi fingerprint -------------------------------------------------------
	function getDataSkl()
  {
  	$data = Sekolah::first();
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
	
		
	
		
	//function untuk kebutuhan variabel pada tabel absensi fingerprint -------------------------------------------------------

//function insert RFID ----------------------------------------------------------------------


//jika user tidak absen masuk dan hanya melakukan absen pulang, user akan di anggap terlambat
  function cekJamAbsenMasukStatus($jam){
		$jamMasuk = strtotime($this->getDataSkl()->sklJamIn);
		$jamPulang = strtotime($this->getDataSkl()->sklJamOut);
		$jamUser = strtotime($jam);
		//jika jam absen user lebih dari sama dengan jam masuk dan kurang dari jam pulang
		
		  if($jamUser >= $jamMasuk AND $jamUser < $jamPulang){
				$status = "H";
			}
			elseif($jamUser > $jamMasuk AND $jamUser > $jamPulang){
				$status = "T";
			}
			elseif($jamUser > $jamMasuk AND $jamUser < $jamPulang){
				$status = "T";	
			}
			else{
				$status = "99";
			}
		
		return $status;
		
	}

	function cekJamAbsen($jam){
		$jamMasuk = strtotime($this->getDataSkl()->sklJamIn);
		$jamPulang = strtotime($this->getDataSkl()->sklJamOut);
		$jamUser = strtotime($jam);

		//jika jam absen user lebih dari sama dengan jam masuk dan kurang dari jam pulang
		if($jamUser >= $jamMasuk AND $jamUser < $jamPulang){
				$data = "M"; //masuk
		}
		elseif($jamUser < $jamMasuk AND $jamUser < $jamPulang){
			$data = "M"; //masuk
		}
		//jam user lebih dari sama dengan jam pualng absen
		elseif($jamUser >= $jamPulang){
			$data = "P"; //pulang
		}
		else{
			$data = "E"; //error
		}

		return $data;

	}
	
	function insetAbsenSekolahRfid($username){
			
		// $jensiAbsen = 4; // 4 kode absen pakai rfid
		// $namahari = date('l');
    // $tgl = date('Y-m-d');
    // // $jamsekarang = date("H:i");
		// $jamsekarang = date("06:30");
		// $bulan = date('n');

		$jamMasuk = $this->getDataSkl()->sklJamIn;
		$jamPulang = $this->getDataSkl()->sklJamOut;

		$jamStatus = $this->getDataSkl()->sklCekJamAbsen;

		$userRfid = Rfid_user::where('rfidUsername',$username)->first();
		$guruData = User_guru::where('ugrUsername',$username)->first();
		
		#cek jika colom foto profile guru tidak kosong
		if(!empty($guruData->ugrFotoProfile)){
			#cek jika file ada pada sistem
			if(File::exists(storage_path('app/public/images/guru/300/'.$guruData->ugrFotoProfile))){
				$foto = storage_path('app/public/images/guru/300/'.$guruData->ugrFotoProfile);
			}
			else{
				$foto = public_path('image/avatar3.png');
			}
		}
		else{
			$foto = public_path('image/avatar3.png');
		}

		if(empty($userRfid)){
			$response = [
				'status'	=> 'error',
				'pesan'		=> 'USER RFID',
				'pesan2'	=> 'KOSONG',
				'kode'		=> 403
			];
			return response()->json($response,403);
		}
		else{

			//bagian nama guru dan gelar ----------------------------------------------------------------------------
			if(!empty($guruData->ugrGelarDepan)){
				$namaGuru =$guruData->ugrGelarDepan.'. '.$guruData->ugrFullName.', '.$guruData->ugrGelarBelakang;
			}
			else if(empty($guruData->ugrGelarDepan) and empty($guruData->ugrGelarBelakang)){
				$namaGuru =$guruData->ugrFullName;
			}
			else{
				$namaGuru =$guruData->ugrFullName.', '.$guruData->ugrGelarBelakang;
			}
			//bagian nama guru dan gelar ----------------------------------------------------------------------------

			
			$user = $username;
			$nama = $namaGuru;
			// $status = $request->status;
			$tgl = date('Y-m-d');
			$tahun = date('Y',strtotime($tgl));
			$hari = date('l');
			$skl = $this->getDataSkl()->sklId;
			//JAM ABSEN SEKOLAH -----------------------------------------
			$jam = date("H:i");
			$cekJam = $this->cekJamAbsen($jam);
			//$tglabsenpesan = date('d-m-Y', strtotime($tgl));
			//JAM ABSEN SEKOLAH -----------------------------------------

			$tahun_ajaran = $this->getTahunAjaranNama();
			$cekabsen = Absen::where('hgUserGuru',$user)->where('hgTgl',$tgl);
			$absenPulang = 'ABSEN PULANG';
			$absenMasuk = 'ABSEN MASUK';
			
			if($cekabsen->count() > 0){ //jika sudah ada absen di databasenya
				$data=[];
				
				if($cekJam == "P"){ 
					if($cekabsen->first()->hgScanPulang == 1){
						//jika data scan sudah ada dan cek apakah scan pulang ada
						$response = [
							'status'	=> 'oke',
							'pesan'		=> 	$absenPulang,
							'pesan2'    => $this->pesan2,
							'kode'		=> 200
						];
						return response()->json($response,200);
					}
					else{ //jika scan pulang tidak ada atau masih kosong
						//cek jam masuk untuk terlambat atau tidak -----------------------------------------------
						if($jamStatus ==1){
							if($cekabsen->first()->hgScanMasuk == 1){ //cek absen masuk apakah sudah ada
								$data = array(
									'hgJamOut'     =>$jam,
									'hgScanPulang' =>1,
								);
							}
							else{
								$data = array(
									'hgJamOut'     =>$jam,
									'hgScanPulang' =>1,
									'hgKodeAbsen'		=> 'T'
								);
							}
						}
						else{ //jika cekstatus jam absen tidak aktif
							$data = array(
								'hgJamOut'     =>$jam,
								'hgScanPulang' =>1,
							);
						}
					//cek jam masuk untuk terlambat atau tidak -----------------------------------------------

						
						
						$insert = Absen::find($cekabsen->first()->hgId)->update($data);
						if($insert){ 
							$response = [
								'status'	=> 'oke',
								'pesan'		=> 	$absenPulang,
								'pesan2'    => $this->pesan2,
								'kode'		=> 200
							];
							//telegram ----------------------------------------------------------------------------
							$message ="*BERHASIL ABSEN PULANG* \n";
							$message .="*".$nama."*\n";
							$message.="PUKUL : ".$jam."\n";
							$message.="TANGGAL : ".formatTanggalIndo($tgl)."\n";
							$message.="HARI : ".hariIndo($hari)."\n";

							sendMessageWithFoto($message,$foto);
						//telegram ----------------------------------------------------------------------------
							return response()->json($response,200);

							
						}
						else{ 
							$response = [
								'status'	=> 'error',
								'pesan'		=> $this->pesanGagal,
								'pesan2'	=> $this->pesanGagal2,
								'kode'		=> 403
							];
							return response()->json($response,403);
						}
					}
					
				}
				else{
					
					$response = [
						'status'	=> 'oke',
						'pesan'		=> $this->pesan,
						'pesan2'    => $this->pesan2,
						'kode'		=> 200
					];
					return response()->json($response,200);
				}
				
				
			} //$cekabsen->count() > 0

			else{ //jika absen belum ada di database
				$data=[];
				
				
				if($cekJam == "M"){
					if($cekabsen->count() > 0){ 
						
						$response = ['success'=>'Anda Sudah Absen Masuk']; 
						return response()->json($response,200);
					}
					else{
						//cek jam masuk untuk terlambat atau tidak -----------------------------------------------
							if($jamStatus ==1){
								$status = $this->cekJamAbsenMasukStatus($jam);
							}
							else{
								$status ="H";
							}
						//cek jam masuk untuk terlambat atau tidak -----------------------------------------------
						$data= array(
							'hgSklId'  		=>$skl,
							'hgTajrKode'  =>$tahun_ajaran,
							'hgTahun'   	=>$tahun,
							'hgUserGuru' 	=>$user,
							'hgNamaFull' 	=>$nama,
							'hgTgl'     	=>$tgl,
							'hgHari'    	=> $hari,
							'hgJamIn'   	=> $jam,
							'hgKodeAbsen' => $status,
							'hgJenisAbsen'=>1,
							'hgScanMasuk' =>1,
						);
						$insert = Absen::insert($data);
						if($insert){ 
							$response = [
								'status'	=> 'oke',
								'pesan'		=> $absenMasuk,
								'pesan2'    => $this->pesan2,
								'kode'		=> 200
							];
							
							//telegram ----------------------------------------------------------------------------
								$message ="*BERHASIL ABSEN MASUK* \n";
								$message .="*".$nama."*\n";
								$message.="PUKUL : ".$jam."\n";
								$message.="TANGGAL : ".formatTanggalIndo($tgl)."\n";
								$message.="HARI : ".hariIndo($hari)."\n";

								sendMessageWithFoto($message,$foto);
							//telegram ----------------------------------------------------------------------------
						
						
							return response()->json($response,200);
						}
						else{ 
							$response = [
								'status'	=> 'error',
								'pesan'		=> $this->pesanGagal,
								'pesan2'	=> $this->pesanGagal2,
								'kode'		=> 403
							];
							return response()->json($response,403);
						}
						
					}
						
				}//end if cek status jam M
				else{
					//belum ada data absen di database dan status absen adalah absen pulang
					//cek jam masuk untuk terlambat atau tidak -----------------------------------------------
					if($jamStatus ==1){
						$status = $this->cekJamAbsenMasukStatus($jam);
					}
					else{
						$status ="H";
					}
				//cek jam masuk untuk terlambat atau tidak -----------------------------------------------
					$data= array(
						'hgSklId'  		=>$skl,
						'hgTajrKode'  =>$tahun_ajaran,
						'hgTahun'   	=>$tahun,
						'hgUserGuru' 	=>$user,
						'hgNamaFull' 	=>$nama,
						'hgTgl'     	=>$tgl,
						'hgHari'    	=> $hari,
						'hgJamOut'     =>$jam,
						'hgKodeAbsen' => $status,
						'hgJenisAbsen'=>1,
						'hgScanPulang' =>1,
					);
					$insert = Absen::insert($data);
					if($insert){ 
						$response = [
							'status'	=> 'oke',
							'pesan'		=> 	$absenPulang,
							'pesan2'    => $this->pesan2,
							'kode'		=> 200
						];
						//telegram ----------------------------------------------------------------------------
						$message ="*BERHASIL ABSEN PULANG* \n";
						$message .="*".$nama."*\n";
						$message.="PUKUL : ".$jam."\n";
						$message.="TANGGAL : ".formatTanggalIndo($tgl)."\n";
						$message.="HARI : ".hariIndo($hari)."\n";

						sendMessageWithFoto($message,$foto);
					//telegram ----------------------------------------------------------------------------
						return response()->json($response,200);
					}
					else{ 
						$response = [
							'status'	=> 'error',
							'pesan'		=> $this->pesanGagal,
							'pesan2'	=> $this->pesanGagal2,
							'kode'		=> 403
						];
						return response()->json($response,403);
					}
					
				}

			}//end if data absen sudah ada
			

		}//endif jika ada kartu rfidnya

	}

	//insert kartu rfid yang tidak terdaftar pada database
	function insertKartuRfidNotFoud($dataArray){
		$data = Rfid_kartu_not_found::firstOrNew($dataArray);
		if($data->save()){
			$response = [
				'status'	=> 'error',
				'pesan'		=> 'Kartu Tidak',
				'pesan2'	=> 'Terdaftar',
				'kode'		=>  403
			];
			return response()->json($response, 403); //Forbidden
		}
		else{
			$response = [
				'status'	=> 'error',
				'pesan'		=> 'Gagal Save',
				'pesan2'	=> 'Kartu',
				'kode'		=>  403
			];
			return response()->json($response, 403); //Forbidden
		}

	}
//function insert RFID ----------------------------------------------------------------------


//url utama dari proses arduino send data ke web server
// menerima send post data dari arduino -----------------------------------------------------
	public function getArduino($token, $idkartu)
	{
		$data_rfid = Rfid_user::firstWhere('rfidKartuId', $idkartu);
		$dataMesin = Rfid_mesin::firstWhere('rfMesinToken', $token);
		//jika toke sesuai
		if (!is_null($dataMesin)) {
			//jika data tidak kosong
			if(is_null($data_rfid)){
				$dataArray = array(
					'knfKartuId'		=> $idkartu,
					'knfKodeMesin'	=> $dataMesin->rfMesinKode,
					'knfNamaMesin'	=> $dataMesin->rfMesinNama,

				);
				//tambah kartu yang di scan tidak di temukan ke database kartu not found
				$data = $this->insertKartuRfidNotFoud($dataArray);

			}
			else{
				//tambah absen ke data absensi fingerprint di database
				$data = $this->insetAbsenSekolahRfid($data_rfid->rfidUsername);
			}
			return $data;
			
		} else {
			$response = [
				'status'	=> 'error',
				'pesan'		=> 'TOKEN MESIN',
				'pesan2'	=> 'TIDAK COCOK',
				'kode'		=>  401
			];
			return response()->json($response, 401); //Unauthorized
			
		}
	}

// menerima send post data dari arduino -----------------------------------------------------



}
