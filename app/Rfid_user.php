<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rfid_user extends Model
{
  protected $table = 'rfid_user';
  protected $primaryKey = 'rfidId';
  public $incrementing = false;
  public $timestamps = false;

  protected $fillable = ['rfidKartuId','rfidUsername','rfidCreatedBy'];
  
  
  public function user_guru()
  { 
    return $this->belongsTo(User_guru::class,'rfidUsername','ugrUsername');

  }

}
