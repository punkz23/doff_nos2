<?php

namespace App\FAQ;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'dialect_id','category_id','question'
    ];

    public function guide(){
    	return $this->hasOne('App\FAQ\Guide');
    }

    public function platform(){
    	return $this->belongsTo('App\FAQ\Platform');
    }

    public function dialect(){
    	return $this->belongsTo('App\FAQ\Dialect');
    }

    public function category(){
    	return $this->belongsTo('App\FAQ\Category');
    }
}
