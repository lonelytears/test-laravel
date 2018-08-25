<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ContractSign extends Model
{
    protected $guarded = [];

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }
}
