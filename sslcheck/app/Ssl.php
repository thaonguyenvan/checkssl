<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ssl extends Model
{
	protected $table = "ssl";
    public function users()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }
}
