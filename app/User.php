<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'admId';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'admUsername', 'admPassword','admRole',
    ];

    //hiden atribut password
    protected $hidden = ['admPassword'];
    
    public function role_admin()
    {
        return $this->belongsTo(Master_jabatan::class,'mjbKode','admRole');
    }
    

}
