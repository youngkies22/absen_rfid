<?php
namespace App;
use Illuminate\Database\Eloquent\Model;


class Absen extends Model
{
    protected $table = 'absen';
    protected $primaryKey = 'hgId';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];  
    
}
