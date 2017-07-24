<?php

namespace App\Counters;

use Illuminate\Database\Eloquent\Model;

class VisitsLog extends Model
{
    /** 
     * Get the patron category that owns the log.
     */
    public function patronCategory()
    {
        return $this->belongsTo('App\Counters\PatronCategory');
    }
}
