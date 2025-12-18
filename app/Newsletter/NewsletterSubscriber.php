<?php

namespace App\Newsletter;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
	public $timestamps = false;

    protected $connection = 'news_letter';

    protected $table = 'subscribers';
    public $primaryKey = "idsubscribers";

    protected $fillable = [
        'email_address','customer_id','token','confirmed_datetime','subscription_status'
    ];
}
