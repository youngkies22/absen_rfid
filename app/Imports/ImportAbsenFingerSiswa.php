<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Cache;
use App\Anggota_rombel;
use App\User_siswa;
use App\Master_jam_sekolah;
use App\Absen_finger_siswa;
use App\Absen_kategori;

//ToCollection
class ImportAbsenFingerSiswa implements ToCollection
{
    public $hasil;
    public $jm = 0;
		public function __construct(){
			$this->hasil = [];
    }
    function KtgAbsen($value)
    {
      $data = Absen_kategori::where('akKode',$value)->first();
      return $data->akId;
    }

    function habist($nama_hari,$jam_siswa_masuk,$jam_siswa_pulang)
    {
    	//ambil jam jadwal
    	$jam = Master_jam_sekolah::first();
    	$jam_masuk = strtotime($jam->jamsklMasuk);
    	$jam_pulang = strtotime($jam->jamsklPulang);
    	$jam_pulang_jumat = strtotime($jam->jamsklPulangJumat);
    	$jam_alpha="";

    	if($nama_hari == 'Friday'){
          if($jam_siswa_masuk == $jam_alpha and $jam_siswa_pulang == $jam_alpha){
              $status = 'A'; //alpha
          }
          else if($jam_siswa_masuk == $jam_alpha and $jam_siswa_pulang >= $jam_pulang_jumat){
              $status = 'T'; //terlambar
          }
          else if($jam_siswa_masuk <= $jam_masuk and $jam_siswa_pulang == $jam_alpha){
              $status = 'B'; //bolos
          }
          else if($jam_siswa_masuk > $jam_masuk and $jam_siswa_pulang == $jam_alpha){
              $status = 'B'; //bolos
          }
          else if($jam_siswa_masuk > $jam_masuk and $jam_siswa_pulang >= $jam_pulang_jumat){
              $status = 'T'; //terlambar
          }
          else if($jam_siswa_masuk <= $jam_masuk and $jam_siswa_pulang < $jam_pulang_jumat){
              $status = 'T'; //terlambar
          }
          else if($jam_siswa_masuk > $jam_masuk and $jam_siswa_pulang < $jam_pulang_jumat){
              $status = 'T'; //terlambar
          }
          else if($jam_siswa_masuk <= $jam_masuk and $jam_siswa_pulang >= $jam_pulang_jumat){
            $status = 'H'; //Hadir
          }
          else{ }
      }
      else{
          if($jam_siswa_masuk == $jam_alpha and $jam_siswa_pulang == $jam_alpha){
              $status = 'A'; //alpha
          }
          else if($jam_siswa_masuk == $jam_alpha and $jam_siswa_pulang >= $jam_pulang){
              $status = 'T'; //terlambar
          }
          else if($jam_siswa_masuk <= $jam_masuk and $jam_siswa_pulang == $jam_alpha){
              $status = 'B'; //bolos
          }
          else if($jam_siswa_masuk > $jam_masuk and $jam_siswa_pulang == $jam_alpha){
              $status = 'B'; //bolos
          }
          else if($jam_siswa_masuk > $jam_masuk and $jam_siswa_pulang >= $jam_pulang){
              $status = 'T'; //terlambar
          }
          else if($jam_siswa_masuk <= $jam_masuk and $jam_siswa_pulang < $jam_pulang){
              $status = 'T'; //terlambar
          }
          else if($jam_siswa_masuk > $jam_masuk and $jam_siswa_pulang < $jam_pulang){
              $status = 'T'; //terlambar
          }
          else if($jam_siswa_masuk <= $jam_masuk and $jam_siswa_pulang >= $jam_pulang){
            $status = 'H'; //Hadir
          }
          else{ }
      }
    	return $status;
    }

     public function collection(Collection $rows)
   	//public function model(array $rows)
    {

        foreach ($rows as $key => $row) 
        {
        	//cari siswa
        	$siswa = User_siswa::where('ssaUsername',$row[0])
        	->where('ssaIsActive',1)
        	->with('anggota_rombel')->first();
      			
	      	if(!empty($siswa)){ //cek siswa ada atau tidak
	      		$cekabsen = Absen_finger_siswa::where('afsSsaId',$siswa->ssaId)
	        	->where('afsDatetime',$row[1])->count();
	        	//cek absen siswa sudah ada atau tidal ada tanggal hari di uplaod absenya
	      		if($cekabsen > 0){ 
	      			$data2[]="";
	      		}
	      		else{
	      			$nama_hari = date('l', strtotime($row[1]));
		        	$jam_siswa_masuk = strtotime($row[2]);
		        	$jam_siswa_pulang = strtotime($row[3]);
		        	$tanggal = date('Y-m-d', strtotime($row[1]));
		        	//cek status siswa absen 
	      			$status = $this->habist($nama_hari,$jam_siswa_masuk,$jam_siswa_pulang);
	      			$rombel = $siswa->anggota_rombel->arbRblId;
  	      			$data = array(
  		        		'afsSklId' 	=>$siswa->ssaSklId,
  		        		'afsJrsId' 	=>$siswa->ssaJrsId,
  		        		'afsRblId'	=>$rombel,
  		        		'afsSsaId' 	=>$siswa->ssaId,
  		        		'afsAkId' 	=>$status,
                  'afsDatetime' =>$tanggal,
  		        		'afsIn' 		=>$row[2],
  		        		'afsOut' 		=>$row[3],
  	        		);
				        $data2[] = $data;
				      Cache::forget('absen_siswa_finger'.$siswa->ssaSklId.$rombel);
				     ++$this->jm;
	      		}
	      	}

      	}//end forace
      	//buat array yang kosong
      	
      	$dataabsen = array_filter($data2);
      	if(empty($dataabsen)){
      		$this->hasil = 2;
      	}
      	else{
      		$insert = Absen_finger_siswa::insert($dataabsen);
	        if($insert){ 
	        	$this->hasil = 1;
	        	$this->jm;
	        }
	        else{ $this->hasil = 0;} 
      	}
    }
    // public function getRowCount()
    // {
    // 	return $this->collection();
    // }
   
}
