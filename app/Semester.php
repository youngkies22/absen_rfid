<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Semester extends Model
{
    protected $table = 'semester';
    protected $primaryKey = 'smId';
    public $incrementing = false;
    public $timestamps = false;

    public function tahun_ajaran()
    {
        return $this->belongsTo(Tahun_ajaran::class,'smTajrId','tajrId');  
    }
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class,'smSklId','sklId');  
    }
    
}
