<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $connection = 'dailyove_online_site';

    protected $fillable = [
        'order','question','is_multiple_answer'
    ];

    public function answer(){
        return $this->hasMany('App\OnlineSite\Answer');
    }
}
