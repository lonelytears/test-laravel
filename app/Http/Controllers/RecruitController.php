<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Rules\Mobile;
use Illuminate\Support\Facades\Log;
use App\Recruit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecruitNotify;

class RecruitController extends Controller
{
    /**
     * 提交页面
     * @return [type] [description]
     */
    public function submit()
    {
        return view('recruit/submit');
    }

    /**
     * 提交处理逻辑
     * @return [type] [description]
     */
    public function create(Request $request)
    {
        // 校验规则
        $this->validate(request(), [
            'username' => 'required|string|min:2',
            'city_name' => 'required|string|min:2',
            'job_name' => 'required|string|min:2',
            'birthday' => 'required|date',
            'mobile' => [
                'required',
                new Mobile
            ],
            'education' => 'required|string|min:2'
        ]);

        $recruit = Recruit::create(request([
            'username',
            'city_name',
            'job_name',
            'birthday',
            'mobile',
            'education'
        ]));

        // 将邮件推入发送队列
        Mail::to(env('HR_EMAIL'))->queue(new RecruitNotify($recruit));

        return back()->with('messages', '您的简历已投递到人力资源邮箱，将尽快与您联系~');
    }
}