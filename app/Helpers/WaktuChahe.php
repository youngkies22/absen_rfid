<?php 

use Illuminate\Support\Facades\Cache;


function ClearAllCache()
{
  $data = Cache::flush();
  if($data){ 
    echo 1;
   }else{ 
    echo 0;
   }
   
  
}
function hapusKeyRedis($id){
  $data = Cache::forget($id);
  if($data){ 
    echo 1;
   }else{ 
    echo 0;
   }
 
}
function ChaceJam() {
    //satu Jam 3600
    $jam = 60;
    return $jam;
}
function ChaceMenit()
{
	$menit = 60;
	return $menit;
}
function ChaceDetik()
{
	$detik = 60;
	return $detik;
}

function WaktuChaceRedis($value){
  //memberikan secara manual waktu cache yang di inginkan
  return $value;
}
//--------------------------------------------------------------------------------------------
// JAM MASUK DAN JAM PULANG ABSENSI FINGER
function JamAbsenIn($status){
  if($status=='H'){ $in='06:20:00';}
  elseif($status=='I'){ $in='00:00:00'; }
  elseif($status=='S'){ $in='00:00:00';}
  elseif($status=='T'){ $in='08:00:00';}
  elseif($status=='B'){ $in='00:00:00';}
  elseif($status=='A'){ $in='00:00:00';}
  else{ $in='00:00:00'; }
  return $in;
}
function JamAbsenOut($status){
  if($status=='H'){ $in='06:20:00'; $out='13:00:00'; }
  elseif($status=='I'){ $in='00:00:00'; $out='00:00:00'; }
  elseif($status=='S'){ $in='00:00:00'; $out='00:00:00'; }
  elseif($status=='T'){ $in='08:00:00'; $out='13:00:00'; }
  elseif($status=='B'){ $in='00:00:00'; $out='00:00:00'; }
  elseif($status=='A'){ $in='00:00:00'; $out='00:00:00'; }
  else{ $out='00:00:00';}
  return $out;
}