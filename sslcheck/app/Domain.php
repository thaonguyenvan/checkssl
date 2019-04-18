<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table = "domain";
    public function users()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }
}
