<?php

namespace App\OnlineSite;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{


	public $timestamps = false;

    protected $connection = 'dailyove_online_site';

    protected $table = 'tblusers';

    protected $fillable = [
        'contact_id','user_name','user_password','user_status','usertype_no','fb_id','oauth_provider','oauth_uid'
    ];

    public function contact(){
        return $this->hasOne('App\OnlineSite\Contact','contact_id','contact_id');
    }


}
