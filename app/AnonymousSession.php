<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnonymousSession extends Model
{
    protected $fillable = ['anonymous_id','session_key','user_id','subject','description'];
}
