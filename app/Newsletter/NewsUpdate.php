<?php

namespace App\Newsletter;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
	public $timestamps = false;

    protected $connection = 'news_letter';

    protected $table = 'tblnews_update_template';
    public $primaryKey = "news_update_id";

    protected $fillable = [
        'news_update_ref_no',
        'news_title',
        'news_caption',
        'news_update_attachment_id',
        'latest_logs',
        'prepared_by',
        'prepared_datetime',
        'news_post_status',
        'date_of_posting',
        'post_expiration_date',
        'updated_by'
    ];
}
