<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Tahun_ajaran extends Model
{
    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'tajrId';
    public $incrementing = false;
    public $timestamps = false;
    
}
