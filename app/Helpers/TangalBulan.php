<?php

function JenisMapel($data){
  if($data == "T"){
    $val = "Teori";
  }
  elseif($data == "P"){
    $val = "Praktik";
  }
  elseif($data == "PT"){
    $val = "Teori & Praktik";
  }
  else{
    $val = "ERROR DATA";
  }
  return $val;
}
function hariIndo($hariInggris) {
  switch ($hariInggris) {
    case 'Sunday':
      return 'Minggu';
    case 'Monday':
      return 'Senin';
    case 'Tuesday':
      return 'Selasa';
    case 'Wednesday':
      return 'Rabu';
    case 'Thursday':
      return 'Kamis';
    case 'Friday':
      return 'Jumat';
    case 'Saturday':
      return 'Sabtu';
    default:
      return 'hari tidak valid';
  }
}
 function bulanIndo($bulan) {
  switch ($bulan) {
    case '1':
      return 'Januari';
    case '2':
      return 'Februari';
    case '3':
      return 'Maret';
    case '4':
      return 'April';
    case '5':
      return 'Mei';
    case '6':
      return 'Juni';
    case '7':
      return 'Juli';
    case '8':
      return 'Agustus';
    case '9':
      return 'September';
    case '10':
      return 'Oktober';
    case '11':
      return 'November';
    case '12':
      return 'Desember';
    default:
      return 'Bulan tidak valid';
  }
}

function getBulan()
{
   for ($i=1; $i <=12 ; $i++) { 
     $data[]=$i;
   }
   return $data;
}
function getTanggal()
 {
   for ($i=1; $i <=31 ; $i++) { 
     $data[]=$i;
   }
   return $data;
 } 
 function getTahunSekarang(){
  $data=date('Y');
  return $data;
 }

 function formatTanggalIndo($date){
  // ubah string menjadi format tanggal
  return date('d-m-Y', strtotime($date));
 }
 function formatTanggalJamIndo($date){
  return date('d-m-Y H:i:s', strtotime($date));
 }
 function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
	
	// variabel pecahkan 0 = tanggal
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tahun
 
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

function tanggal_indo($tanggal, $cetak_hari = false)
{
  //true jika menampilkan nama hari
  /*
    echo tanggal_indo ('2016-03-20'); // Hasil: 20 Maret 2016;
    echo tanggal_indo ('2016-03-20', true); // Hasil: Minggu, 20 Maret 2016
  */
	$hari = array ( 1 =>    'Senin',
				'Selasa',
				'Rabu',
				'Kamis',
				'Jumat',
				'Sabtu',
				'Minggu'
			);
			
	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split 	  = explode('-', $tanggal);
	$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
	
	if ($cetak_hari) {
		$num = date('N', strtotime($tanggal));
		return $hari[$num] . ', ' . $tgl_indo;
	}
	return $tgl_indo;
}

/*
* bagian cetar ke PDF untuk cek style background tgl 1-31
*/
function colorTgl($status)
{
  if ($status == 'H') {
    $style = 'style="background-color:#89ff00;"';
  } elseif ($status == 'A') {
    $style = 'style="background-color:#ff0000;"';
    //$style = 'style="background-color:#d94e4e;"';
  } elseif ($status == 'B') {
    $style = 'style="background-color:#cc00ffdb;"';
    //$style = 'style="background-color:#d94e4e;"';
  } elseif ($status == 'T') {
    $style = 'style="background-color:#ff8d00;"';
    //$style = 'style="background-color:#ffcaca;"';
  } elseif ($status == 'L') {
    $style = 'style="background-color:#ffeb3b;"';
  } elseif ($status == 'I') {
    $style = 'style="background-color:#03a9f457;"';
  } elseif ($status == 'S') {
    $style = 'style="background-color:#03a9f457;"';
  } elseif ($status == 'LS') {
    $style = 'style="background-color:#3bff482b"'; #3bff48b3;
  } elseif ($status == 'U') {
    $style = 'style="background-color:#12e9f4"'; #3bff48b3;
  } elseif ($status == 'K') {
    $style = 'style="background-color:#12e9f4"'; #3bff48b3;
  }
  else {
    $style = '';
  }
  echo $style;
}

function hariIndo3($hariInggris)
{
  $hari = date('l',strtotime($hariInggris));
  switch ($hari) {
    case 'Sunday':
      return 'Min';
    case 'Monday':
      return 'Sen';
    case 'Tuesday':
      return 'Sel';
    case 'Wednesday':
      return 'Rab';
    case 'Thursday':
      return 'Kam';
    case 'Friday':
      return 'Jum';
    case 'Saturday':
      return 'Sab';
    default:
      return 'eror';
  }
}