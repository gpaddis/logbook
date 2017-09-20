<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogbookEntry extends Model
{
    public $timestamps = false;

    public $fillable = ['patron_category_id', 'visited_at', 'recorded_at'];
}
