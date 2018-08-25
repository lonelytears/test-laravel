@extends('layout.main')

@section('title', '看单详情')

@section('contents')

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">编号</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$look->code}}</p>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">状态</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$look->status($look->look_at, $look->status)}}</p>
                </div>
            </div>

            @if ($look->status == 1)
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">确认时间</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$look->validate_at->toDateTimeString()}}</p>
                </div>
            </div>
            @endif

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">城市</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$look->city_name}}</p>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">约看人</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$user->real_name}} ({{$user->business_name}})</p>
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
                    <p class="weui-label">电话</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$user->mobile}}</p>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">约看时间</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$look->look_at->toDateTimeString()}}</p>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <p class="weui-label">楼盘</p>
                </div>
                <div class="weui-cell__bd">
                    <p>{{$look->community_name}}</p>
                </div>
            </div>

            @foreach ($look_houses as $look_house)
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <p class="weui-label">房间 {{$loop->iteration}}</p>
                    </div>
                    <div class="weui-cell__bd">
                        <p>{{$look_house->building_name}} {{$look_house->unit}} 单元 {{$look_house->house_name}} 号</p>
                    </div>
                </div>
            @endforeach

            <div class="weui-cell">
                <div class="weui-cell__bd my_info_img_box">
                    <img src="{{url('lb/qrcode', [$look->code])}}" alt="">
                </div>
            </div>

@endsection