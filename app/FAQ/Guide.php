<?php

namespace App\FAQ;

use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
	protected $connection = 'dailyove_online_site';

    protected $fillable = [
        'question_id','platform_id','content','user_id'
    ];

    public function question(){
    	return $this->belongsTo('App\FAQ\Question');
    }
}
