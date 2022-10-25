<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LoginAdmin extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'admId';

    protected $fillable = [
        'admUsername', 'admPassword','admRole',
    ];
    
    protected $hidden = [
        'admPassword', 'admRemember_token',
    ];
    
    //menyesuaikan password login pada tabel databse
    public function getAuthPassword()
    {
        return $this->admPassword;
    }

}