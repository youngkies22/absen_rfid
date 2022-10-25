<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'setting';
    protected $primaryKey = 'setId';
    public $incrementing = false;
    public $timestamps = false;
    
}
