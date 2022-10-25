<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class User_guru extends Model
{
    protected $table = 'user_guru';
    protected $primaryKey = 'ugrId';
    public $incrementing = false;
    public $timestamps = false;


    public function sekolah()
    {
       return $this->belongsTo(Sekolah::class,'ugrSklId','sklId');
       
    }
    

   //  //get guru by id sekolah
   //  public function getGuruSkl($id)
   //  {
   //      if (Cache::has('guru_skl'.$id)){ $data = Cache::where('ugrIsActive',1)->get('guru_skl'.$id); }
   //      else{ 
   //          if(empty($id)){ $data = User_guru::get(); }
   //          else{ $data = User_guru::where('ugrSklId', $id)
   //             ->where('ugrIsActive',1)
   //             ->get(); }
   //          $chace = Cache::put('guru_skl'.$id, $data, ChaceJam());
   //      }
   //      return $data;
   //  }

   
}
