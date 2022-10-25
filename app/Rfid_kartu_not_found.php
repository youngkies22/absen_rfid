<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rfid_kartu_not_found extends Model
{
  protected $table = 'rfid_kartu_not_found';
  protected $primaryKey = 'knfId';
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = ['knfKartuId','knfKodeMesin','knfTokenMesin','knfNamaMesin','knfSklId'];

  public function master_sekolah()
    {
        return $this->belongsTo(Master_sekolah::class,'knfSklId','sklId');  
    }

 

}
