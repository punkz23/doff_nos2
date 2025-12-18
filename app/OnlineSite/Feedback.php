<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $connection = 'dailyove_online_site';

    protected $fillable = [
        'contact_id','reference_no','feedback_date'
    ];

    protected $table = 'feedbacks';

    public function feedback_answer(){
        return $this->hasMany('App\FeedbackAnswer');
    }
}
