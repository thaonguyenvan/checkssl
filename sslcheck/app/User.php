<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
// implements MustVerifyEmail
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function ssl(){
        return $this->hasMany('App\Ssl','user_id','id');
    }
    public function domain(){
        return $this->hasMany('App\Domain','user_id','id');
    }
    public function ssl_all(){
        return $this->hasMany('App\Ssl_all','user_id','id');
    }
    public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser');
    }
    public function email_noti(){
        return $this->hasMany('App\Email_noti','user_id','id');
    }
    public function tele_noti(){
        return $this->hasMany('App\Tele_noti','user_id','id');
    }
}
