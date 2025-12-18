<?php

namespace App\FAQ;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function question(){
    	return $this->hasMany('App\FAQ\Question');
    }
}
