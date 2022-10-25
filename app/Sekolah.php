<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Sekolah extends Model
{
    protected $table = 'sekolah';
    protected $primaryKey = 'sklId';
    public $incrementing = false;
    public $timestamps = false;

    public function getSekolah($id=null)
    {
       
        $skl = Sekolah::first(); 
        
		return $skl;
       
    }
    
}
