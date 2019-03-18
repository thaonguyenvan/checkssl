<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerifyEmail extends Model
{
    protected $guarded = [];
 
    public function email_noti()
    {
        return $this->belongsTo('App\Email_noti', 'email_id');
    }
}
