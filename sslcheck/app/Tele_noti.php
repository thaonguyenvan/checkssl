<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tele_noti extends Model
{
    protected $table = "tele_noti";
    public function users()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }
}
