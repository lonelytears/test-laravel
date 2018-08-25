<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ContractUser extends Model
{
    protected $guarded = [];
    //指定表名   默认表名是模型+s，若是指定表名则会使用指定的表名
    protected $table = 'contract_user';

    public function findByMobile($mobile)
    {
        return json_decode($this->where('mobile','=',$mobile)->get()->first());
    }

    public function findByCustomerID($customer_id)
    {
        return json_decode($this->where('customerId','=',$customer_id)->get()->first());
    }

    public function findById($id)
    {
        return json_decode($this->find($id));
    }

}
