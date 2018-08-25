<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class Look extends Model
{
    protected $guarded = [];

    /**
     * 使用 Carbon 对象
     * @var [type]
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'look_at',
        'validate_at'
    ];

    /**
     * 关联用户
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * 关联看单房源
     * @return [type] [description]
     */
    public function look_houses()
    {
//        return $this->hasOne('App\LookHouse');
        return $this->hasMany('App\LookHouse');
    }

    /**
     * 返回带看状态
     * @param  [type] $look_at [description]
     * @param  [type] $status  [description]
     * @return [type]          [description]
     */
    public function status(Carbon $look_at, $status=0)
    {
        // 判断过期条件：时间不是今天且是过去，约看状态为 0
        if (!$look_at->isToday() && $look_at->isPast() && $status == 0) {
            return '已过期';
        }

        switch ($status) {
            case 0:
                return '预约中';
                break;
            case 1:
                return '已确认';
                break;
            case 2:
                return '已取消';
                break;
            default:
                return '预约中';
                break;
        }
    }
}
