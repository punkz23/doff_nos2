<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $connection = 'dailyove_online_site';

    protected $fillable = [
        'question_id','answer'
    ];

    public function question(){
        return $this->belongsTo('App\OnlineSite\Question');
    }
}
