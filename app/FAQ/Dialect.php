<?php

namespace App\FAQ;

use Illuminate\Database\Eloquent\Model;

class Dialect extends Model
{
    public function category(){
        return $this->hasMany('App\FAQ\Category');
    }
}
