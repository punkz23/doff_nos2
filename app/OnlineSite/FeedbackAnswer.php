<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class FeedbackAnswer extends Model
{
    protected $connection = 'dailyove_online_site';

    protected $fillable = [
        'feedback_id','answer_id'
    ];

    public function feedback(){
        return $this->belongsTo('App\Feedback');
    }
}
