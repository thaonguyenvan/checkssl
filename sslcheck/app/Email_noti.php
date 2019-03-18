<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email_noti extends Model
{
    protected $table = "email_noti";
    public function users()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }

    public function verifyAddEmail()
    {
        return $this->hasOne('App\VerifyEmail','email_id');
    }
}
