<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class Contract extends Model
{
    //指定不允许批量赋值的字段
    protected $guarded = [];
    //指定运行批量赋值的字段
    protected $fillable = ['viewpdf_url','download_url'];
    //指定表名   默认表名是模型+s，若是指定表名则会使用指定的表名
    protected $table = 'contract';
    //时间自动维护 true打开 false关闭
    protected $timestamp = true;
    //若是时间戳   则可以重写getDateformat方法
    /*public function getDateformat()
    {
        return time();
    }*/

    /*//自动将时间格式转时间戳
    protected function asDateTime($value)
    {
        return $value;
    }*/



    /**
     * 使用 Carbon 对象
     * @var [type]
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * 关联合同模板
     * @return [type] [description]
     */
    public function contract_template()
    {
        return $this->belongsTo('App\ContractTemplate');
    }

    /**
     * 关联合同签署
     * @return [type] [description]
     */
    public function contract_sign()
    {
//        return $this->hasOne('App\LookHouse');
        return $this->hasMany('App\ContractSign');
    }

    public function get_status_name ($status)
    {
        switch ($status) {
            case 0: return '待甲方签约';
                break;
            case 1: return '待乙方签约';
                break;
            case 2: return '待丙方签约';
                break;
            case 3: return '签约完成';
                break;
        }
    }

    /**
     * 根据合同编号查合同信息
     * @param $contract_id
     * @return mixed
     */
    public function findByContractId($contract_id)
    {
        return $this->where('contract_id','=',$contract_id)->get();
    }

    /**
     * 根据创建人查询合同信息
     * @param $creator_id
     * @return mixed
     */
    public function findByCreatorId($creator_id)
    {
        $list = $this->where('creator_id','=',$creator_id)->join('contract_user','contract_user.id','=','contract.party_a_id')->get();
        return $list;
    }

    /**
     * 根据合同id查询合同信息
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
//        $list = $this->where('id','=',$id)->join('contract_user','contract_user.id','=','contract.party_a_id')->get();
        return json_decode($this->find($id));
    }
}
