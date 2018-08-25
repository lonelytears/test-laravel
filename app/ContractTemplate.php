<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ContractTemplate extends Model
{
    protected $guarded = [];

    //指定表名   默认表名是模型+s，若是指定表名则会使用指定的表名
    protected $table = 'contract_template';

    public function contract()
    {
        return $this->hasMany('App\Contract');
    }
}
