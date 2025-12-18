<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestLinkAccount extends Model
{
    protected $fillable = ['contact_id','is_approve'];
}
