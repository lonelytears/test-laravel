@extends('layout.main')

@section('title', '个人信息')

@section('contents')

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">手机号</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$user->mobile}}</p>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">真实姓名</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$user->real_name}}</p>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">性别</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$user->sex($user->sex)}}</p>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">身份证</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$user->id_card}}</p>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">城市名</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$user->city_name}}</p>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">所属中介公司</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$user->business_name}}</p>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">证书编号</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$user->user_business_code or '未填写'}}</p>
                </div>
            </div>

            <div class="page-bd-15">
                <a href="{{url('/user/edit', [$user->id])}}" class="weui-btn weui-btn-mini weui-btn_primary">修改</a>
                <a href="{{url('/')}}" class="weui-btn weui-btn-mini weui-btn_default">返回</a>
            </div>

@endsection