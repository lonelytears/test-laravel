<?php
use App\User;
$user = new User();
?>

@extends('layout.main')

@section('title', '绑定手机号')

@section('contents')

        <form id="usereditform" action="{{url('user/binding', [$auth->id])}}" method="post">

            {{csrf_field()}}

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">手机号</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="tel" name="mobile" placeholder="请输入手机号" value="{{old('mobile')}}">
                </div>
                <div class="weui-cell__ft">
                    <button class="weui-vcode-btn" id="getValidataCode">获取验证码</button>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">验证码</label>
                </div>
                <div class="weui-cell__bd">
                  <input name="validate_code" class="weui-input" type="number" placeholder="请输入验证码">
                </div>
            </div>

            <div class="page-bd-15">
                <button type="submit" id="user_update_btn" class="weui-btn weui-btn-mini weui-btn_primary">确定</button>
                <a href="{{url('/')}}" class="weui-btn weui-btn-mini weui-btn_default">返回</a>
            </div>
        </form>

@endsection

@section('extend')
<script>
    var InterValObj; //timer变量，控制时间
    var count = {{env('PULIN_API_ALLOW_SMS_TIME') * 60}}; //间隔函数，1秒执行
    var curCount;//当前剩余秒数

    function sendMessage() {
     　curCount = count;
    　　//设置button效果，开始计时
       $("#getValidataCode").attr("disabled", "true");
       $("#getValidataCode").text(curCount + "秒后可重新发送");
       InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
    }

    //timer处理函数
    function SetRemainTime() {
        if (curCount == 0) {
            window.clearInterval(InterValObj);//停止计时器
            $("#getValidataCode").removeAttr("disabled");//启用按钮
            $("#getValidataCode").text("重新发送验证码");
        }
        else {
            curCount--;
            $("#getValidataCode").text(curCount + "秒后可重新发送");
        }
    }

    // 获取验证码
    $('#getValidataCode').click(function () {
        $.post("{{url('/api/sendValidateCode')}}", {
            'mobile': $('input[name="mobile"]').val()
        }, function (result) {
            if (result.code == 0) {
                sendMessage();
                $.toptip(result.result, 'success');
            } else {
                $.toptip(result.error, 'error');
            }
        }, 'JSON');
        return false;
    });
</script>
@endsection