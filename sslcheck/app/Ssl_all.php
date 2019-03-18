<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ssl_all extends Model
{
    protected $table = "ssl_all";
    public function users()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }
}
