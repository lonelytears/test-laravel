<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LookHouse extends Model
{
    protected $guarded = [];

    public function look()
    {
        return $this->belongsTo('App\Look');
    }
}
