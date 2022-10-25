<?php
/*
  Upload Foto Conttroller
  mryes
  Braja Sakti Way Jepara Lampung Timur
  SMK Budi Utomo Way Jepara
*/
//membaca lokasi Controller dalam Folder
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\User_siswa;
use App\User_guru;
use App\Foto_upload;
use App\Foto_informasi;
use Carbon\Carbon;
use Image;
use File;
use Auth;//menjalankan printah auth
use Artisan;
use App\Log_aksi_user;

class UploadFotoController extends Controller
{
  public $pathguru;
  public $pathinfo;
  public $pathsiswa;
  public $dimensions;
  public $dimensionsinfo;
  public function __construct()
  {
    //DEFINISIKAN PATH
    $this->pathguru = storage_path('app/public/images/guru');
    $this->pathsiswa = storage_path('app/public/images/siswa');
    //DEFINISIKAN DIMENSI untuk memecah dimensi foto
    $this->dimensions = ['100','200','300'];

  }

  

  public function UploadFotoAdmin(Request $request)
  {
    $original = 'original';
    $idd = Crypt::decrypt($request->id);
    $getuser = User::find($idd);
    // $this->validate($request, [
    //  'foto_upload' => 'required|image|max:1024|mimes:jpg,png,jpeg'
    // ]);

    //validasi type gambar buat mryes
    $pesan =[
        'foto_upload.required' =>'Foto Tidak Boleh Kosong',
        'foto_upload.mimes' =>'Foto Harus Berekstensi jpg/png/jpeg',
        'foto_upload.image' =>'Foto Harus Berekstensi jpg/png/jpeg',
        //'foto_upload.max' =>'Maksimal Ukuran Foto 3 Mb',
      ];
     $validator = Validator::make(request()->all(), [
        'foto_upload' => 'required|image|mimes:jpg,png,jpeg'
      ],$pesan);
    
    if($validator->fails()) {
      return response()->json([
          'danger' => 'Foto Harus Berekstensi jpg/png/jpeg'
        ]);
    }
    else{ 
      //JIKA FOLDERNYA BELUM ADA
      // if (!File::isDirectory($this->pathguru)) {
     //    //MAKA FOLDER TERSEBUT AKAN DIBUAT
      //  File::makeDirectory($this->pathguru);
      // }

      //DARI ERAPOR SMK
      if (!Storage::exists($this->pathguru)) {
        //MAKA FOLDER TERSEBUT AKAN DIBUAT
        Storage::makeDirectory($this->pathguru, 0775, true); //creates directory
      }
      else {
        //Artisan::call('storage:link');
      }
      
      //MENGAMBIL FILE IMAGE DARI FORM
      $file = $request->file('foto_upload');
      
      //MEMBUAT NAME FILE DARI GABUNGAN TIMESTAMP DAN UNIQID()
      $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
      
      //UPLOAD ORIGINAN FILE (BELUM DIUBAH DIMENSINYA)
      Image::make($file)->save($this->pathguru . '/'.$original.'/'. $fileName);
      //HAPUS FILE SEBELUMNYA (JIKA ADA FILENYA)
      File::delete($this->pathguru . '/'. $original.'/'.$getuser->admFotoProfile);
      
      //LOOPING ARRAY DIMENSI YANG DI-INGINKAN
      //YANG TELAH DIDEFINISIKAN PADA CONSTRUCTOR
       foreach ($this->dimensions as $row) {
         //MEMBUAT CANVAS IMAGE SEBESAR DIMENSI YANG ADA DI DALAM ARRAY 
          $canvas = Image::canvas($row, $row);
          
          //RESIZE IMAGE SESUAI DIMENSI YANG ADA DIDALAM ARRAY 
          //DENGAN MEMPERTAHANKAN RATIO
          //$resizeImage  = Image::make($file)->resize(500, 300, function($constraint) {
          $resizeImage  = Image::make($file)->resize($row, $row, function($constraint) {

            $constraint->aspectRatio();
          });

          //CEK JIKA FOLDERNYA BELUM ADA
          // if (!File::isDirectory($this->pathguru . '/' . $row)) {
         //    //MAKA BUAT FOLDER DENGAN NAMA DIMENSI
          //  File::makeDirectory($this->pathguru . '/' . $row);
          // }
          if(!Storage::exists($this->pathguru . '/' . $row)) {
            Storage::makeDirectory($this->pathguru . '/' . $row, 0775, true); //creates directory
          }
          //MEMASUKAN IMAGE YANG TELAH DIRESIZE KE DALAM CANVAS
          $canvas->insert($resizeImage, 'center');
          //SIMPAN IMAGE KE DALAM MASING-MASING FOLDER (DIMENSI)
          $canvas->save($this->pathguru . '/' . $row . '/' . $fileName);
          //HAPUS FILE SEBELUMNYA (JIKA ADA FILENYA)
          File::delete($this->pathguru . '/' . $row . '/' . $getuser->admFotoProfile);


       }//end foreach

       // $upload = Foto_upload::create([
       //   'fotoIdAdmin'=> $idUser,
       //   'fotoName' => $fileName,
       //   'fotoDimensions' => implode('|', $this->dimensions),
       //   'fotoPath' => $this->pathguru
       // ]);
       // return redirect()->back()->with(['success' => 'Gambar Telah Di-upload']);

       //SIMPAN DATA IMAGE YANG TELAH DI-UPLOAD
       $upload = User::find($idd);
       $upload->admFotoProfile = $fileName;
      

       if($upload->save()){
        return response()->json([
          'save' => 'Foto Berhasil Di Upload'
        ]);
       }
       else{
        return response()->json([
          'error' => 'Gagal Upload Foto'
        ]);
       }
    }//end else
     

  }//end function upload

  public function UploadFotoSiswa(Request $request)
  {
   
    $original = 'original';
    $idd = decrypt_url($request->id);
    $getuser = User_siswa::find($idd);
   
    //validasi type gambar buat mryes
    $pesan =[
        'foto_upload.required' =>'Foto Tidak Boleh Kosong',
        'foto_upload.mimes' =>'Foto Harus Berekstensi jpg/png/jpeg',
        'foto_upload.image' =>'Foto Harus Berekstensi jpg/png/jpeg',
        //'foto_upload.max' =>'Maksimal Ukuran Foto 3 Mb',
      ];
     $validator = Validator::make(request()->all(), [
        'foto_upload' => 'required|image|mimes:jpg,png,jpeg'
      ],$pesan);
    
    if($validator->fails()) {
      return response()->json([
          'danger' => 'Foto Harus Berekstensi jpg/png/jpeg'
        ]);
    }
    else{ 
      //JIKA FOLDERNYA BELUM ADA
      // if (!File::isDirectory($this->pathguru)) {
     //    //MAKA FOLDER TERSEBUT AKAN DIBUAT
      //  File::makeDirectory($this->pathguru);
      // }

      //DARI ERAPOR SMK
      if (!Storage::exists($this->pathsiswa)) {
        //MAKA FOLDER TERSEBUT AKAN DIBUAT
        Storage::makeDirectory($this->pathsiswa, 0775, true); //creates directory
      }
      else {
        //Artisan::call('storage:link');
      }
      
      //MENGAMBIL FILE IMAGE DARI FORM
      $file = $request->file('foto_upload');
      
      //MEMBUAT NAME FILE DARI GABUNGAN TIMESTAMP DAN UNIQID()
      $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
      
      //UPLOAD ORIGINAN FILE (BELUM DIUBAH DIMENSINYA)
      Image::make($file)->save($this->pathsiswa . '/'.$original.'/'. $fileName);
      //HAPUS FILE SEBELUMNYA (JIKA ADA FILENYA)
      File::delete($this->pathsiswa . '/'. $original.'/'.$getuser->ssaFotoProfile);
      
      //LOOPING ARRAY DIMENSI YANG DI-INGINKAN
      //YANG TELAH DIDEFINISIKAN PADA CONSTRUCTOR
       foreach ($this->dimensions as $row) {
         //MEMBUAT CANVAS IMAGE SEBESAR DIMENSI YANG ADA DI DALAM ARRAY 
          $canvas = Image::canvas($row, $row);
          
          //RESIZE IMAGE SESUAI DIMENSI YANG ADA DIDALAM ARRAY 
          //DENGAN MEMPERTAHANKAN RATIO
          //$resizeImage  = Image::make($file)->resize(500, 300, function($constraint) {
          $resizeImage  = Image::make($file)->resize($row, $row, function($constraint) {

            $constraint->aspectRatio();
          });

          //CEK JIKA FOLDERNYA BELUM ADA
          // if (!File::isDirectory($this->pathguru . '/' . $row)) {
         //    //MAKA BUAT FOLDER DENGAN NAMA DIMENSI
          //  File::makeDirectory($this->pathguru . '/' . $row);
          // }
          if(!Storage::exists($this->pathsiswa . '/' . $row)) {
            Storage::makeDirectory($this->pathsiswa . '/' . $row, 0775, true); //creates directory
          }
          //MEMASUKAN IMAGE YANG TELAH DIRESIZE KE DALAM CANVAS
          $canvas->insert($resizeImage, 'center');
          //SIMPAN IMAGE KE DALAM MASING-MASING FOLDER (DIMENSI)
          $canvas->save($this->pathsiswa . '/' . $row . '/' . $fileName);
          //HAPUS FILE SEBELUMNYA (JIKA ADA FILENYA)
          File::delete($this->pathsiswa . '/' . $row . '/' . $getuser->ssaFotoProfile);


       }//end foreach

       // $upload = Foto_upload::create([
       //   'fotoIdAdmin'=> $idUser,
       //   'fotoName' => $fileName,
       //   'fotoDimensions' => implode('|', $this->dimensions),
       //   'fotoPath' => $this->pathguru
       // ]);
       // return redirect()->back()->with(['success' => 'Gambar Telah Di-upload']);

       //SIMPAN DATA IMAGE YANG TELAH DI-UPLOAD
       $upload = User_siswa::find($idd);
       $upload->ssaFotoProfile = $fileName;
       $upload->ssaUpdated = date("Y-m-d h:i:s");
       $upload->ssaUpdatedBy = Auth::user()->ssaId;

       if($upload->save()){
        return response()->json([
          'save' => 'Foto Berhasil Di Upload'
        ]);
       }
       else{
        return response()->json([
          'error' => 'Gagal Upload Foto'
        ]);
       }
    }//end else
     

  }//end function upload siswa
  public function UploadFotoGuru(Request $request)
  {
    $original = 'original';
    $idd = decrypt_url($request->id);
    $getuser = User_guru::find($idd);
    // $this->validate($request, [
    //  'foto_upload' => 'required|image|max:1024|mimes:jpg,png,jpeg'
    // ]);

    //validasi type gambar buat mryes
    $pesan =[
        'foto_upload.required' =>'Foto Tidak Boleh Kosong',
        'foto_upload.mimes' =>'Foto Harus Berekstensi jpg/png/jpeg',
        'foto_upload.image' =>'Foto Harus Berekstensi jpg/png/jpeg',
        //'foto_upload.max' =>'Maksimal Ukuran Foto 3 Mb',
      ];
     $validator = Validator::make(request()->all(), [
        'foto_upload' => 'required|image|mimes:jpg,png,jpeg'
      ],$pesan);
    
    if($validator->fails()) {
      return response()->json([
          'danger' => 'Foto Harus Berekstensi jpg/png/jpeg'
        ]);
    }
    else{ 
      //JIKA FOLDERNYA BELUM ADA
      // if (!File::isDirectory($this->pathguru)) {
     //    //MAKA FOLDER TERSEBUT AKAN DIBUAT
      //  File::makeDirectory($this->pathguru);
      // }

      //DARI ERAPOR SMK
      if (!Storage::exists($this->pathguru)) {
        //MAKA FOLDER TERSEBUT AKAN DIBUAT
        Storage::makeDirectory($this->pathguru, 0775, true); //creates directory
      }
      else {
        //Artisan::call('storage:link');
      }
      
      //MENGAMBIL FILE IMAGE DARI FORM
      $file = $request->file('foto_upload');
      
      //MEMBUAT NAME FILE DARI GABUNGAN TIMESTAMP DAN UNIQID()
      $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
      
      //UPLOAD ORIGINAN FILE (BELUM DIUBAH DIMENSINYA)
      Image::make($file)->save($this->pathguru . '/'.$original.'/'. $fileName);
      //HAPUS FILE SEBELUMNYA (JIKA ADA FILENYA)
      if(!empty($getuser->ugrFotoProfile)){
        File::delete($this->pathguru . '/'. $original.'/'.$getuser->ugrFotoProfile);
      }
      
      
      //LOOPING ARRAY DIMENSI YANG DI-INGINKAN
      //YANG TELAH DIDEFINISIKAN PADA CONSTRUCTOR
       foreach ($this->dimensions as $row) {
         //MEMBUAT CANVAS IMAGE SEBESAR DIMENSI YANG ADA DI DALAM ARRAY 
          $canvas = Image::canvas($row, $row);
          
          //RESIZE IMAGE SESUAI DIMENSI YANG ADA DIDALAM ARRAY 
          //DENGAN MEMPERTAHANKAN RATIO
          //$resizeImage  = Image::make($file)->resize(500, 300, function($constraint) {
          $resizeImage  = Image::make($file)->resize($row, $row, function($constraint) {

            $constraint->aspectRatio();
          });

          //CEK JIKA FOLDERNYA BELUM ADA
          // if (!File::isDirectory($this->pathguru . '/' . $row)) {
         //    //MAKA BUAT FOLDER DENGAN NAMA DIMENSI
          //  File::makeDirectory($this->pathguru . '/' . $row);
          // }
          if(!Storage::exists($this->pathguru . '/' . $row)) {
            Storage::makeDirectory($this->pathguru . '/' . $row, 0775, true); //creates directory
          }
          //MEMASUKAN IMAGE YANG TELAH DIRESIZE KE DALAM CANVAS
          $canvas->insert($resizeImage, 'center');
          //SIMPAN IMAGE KE DALAM MASING-MASING FOLDER (DIMENSI)
          $canvas->save($this->pathguru . '/' . $row . '/' . $fileName);
          //HAPUS FILE SEBELUMNYA (JIKA ADA FILENYA)
          
          if(!empty($getuser->ugrFotoProfile)){
            File::delete($this->pathguru . '/'. $row.'/'.$getuser->ugrFotoProfile);
          }


       }//end foreach

       //SIMPAN DATA IMAGE YANG TELAH DI-UPLOAD
       $upload = User_guru::find($idd);
       $upload->ugrFotoProfile = $fileName;

       if($upload->save()){
        return response()->json([
          'save' => 'Foto Berhasil Di Upload'
        ]);
       }
       else{
        return response()->json([
          'error' => 'Gagal Upload Foto'
        ]);
       }
    }//end else
     

  }//

 


}
