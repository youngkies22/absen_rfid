<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rfid_mesin extends Model
{
  protected $table = 'rfid_mesin';
  protected $primaryKey = 'rfMesinId';
  public $incrementing = false;
  public $timestamps = false;

  public function master_sekolah()
  {
      return $this->belongsTo(Sekolah::class,'rfMesinSklId','sklId');  
  }

}
