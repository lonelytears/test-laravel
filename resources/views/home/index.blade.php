@extends('layout.main')

@section('title', '我的约看')

@section('contents')

    <!-- 头部 -->
    <div class="weui-flex nav_header">
        <div class="weui-flex__item">
            @if ($user)
                <a href="{{url('look/submit')}}" class="weui-btn weui-btn_plain-primary"><span class="icon icon-search"></span>我要看房</a>
            @elseif ($auth::hasBindingMobile())
                <a href="{{url('user/create', [session('auth')['id']])}}" class="weui-btn weui-btn_plain-primary"><span class="icon icon-search"></span>我要看房</a>
            @else
                <a href="{{url('user/binding', [session('auth')['id']])}}" class="weui-btn weui-btn_plain-primary"><span class="icon icon-search"></span>我要看房</a>
            @endif
        </div>
        <div class="weui-flex__item">
            @if ($user)
                <a href="{{url('user/detail', [$user->id])}}" class="weui-btn weui-btn_plain-primary"><span class="icon icon-friends"></span>身份详情</a>
            @elseif ($auth::hasBindingMobile())
                <a href="{{url('user/create', [session('auth')['id']])}}" class="weui-btn weui-btn_plain-primary"><span class="icon icon-friends"></span>身份详情</a>
            @else
                <a href="{{url('user/binding', [session('auth')['id']])}}" class="weui-btn weui-btn_plain-primary"><span class="icon icon-friends"></span>身份详情</a>
            @endif
        </div>
    </div>
    <!-- 卡片 -->
    <div class="weui-panel weui-panel_access">
        <div class="weui-panel__hd build_info_title">约看记录</div>
            <ul>
                @if($looks && $looks->count())
                    @foreach ($looks as $look)
                    <li>
                        <div class="weui-panel__bd">
                            <a href="{{url('look/detail', [$look->id])}}" class="weui-media-box weui-media-box_appmsg">
                                <div class="weui-media-box__hd">
                                    <img class="weui-media-box__thumb community_img" data-city_id="{{$look->city_id}}" data-community_code="{{$look->community_code}}" src="https://plcdn.vankeservice.com/bank-sale/image/default/default_community.jpg">
                                </div>
                                <div class="weui-media-box__bd">
                                    <h4 class="weui-media-box__title">{{$look->community_name}}（{{$look->look_houses()->count()}}套房）</h4>
                                    <p class="weui-media-box__desc"><span class="icon icon-emoji"></span> {{$look->status($look->look_at, $look->status)}}</p>
                                    <p class="weui-media-box__desc"><span class="icon icon-code"></span> {{$look->code}}</p>
                                    <p class="weui-media-box__desc"><span class="icon icon-clock"></span> {{$look->look_at->toDateTimeString()}}</p>
                                </div>
                                <span class="weui_cell_ft"><span class="icon icon-right"></span></span>
                            </a>
                        </div>
                    </li>
                    @endforeach
                @else
                <li>
                    <div class="weui-panel__bd">
                    <div class="weui-loadmore weui-loadmore_line">
                        <span class="weui-loadmore__tips">暂无数据</span>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>

@endsection

@section('extend')
    <script>
        // 加载小区图片
        $('.community_img').each(function (i, item) {
            var city_id = $(item).data('city_id');
            var community_code = $(item).data('community_code');
            $.post("{{url('/api/getCommunityInfo')}}", {
                city_id: city_id,
                community_code: community_code
            }, function (res) {
                if (res.data.basic[0].cover_uri !== "") {
                    $(item).attr('src', 'https://bank-sale.oss-cn-shenzhen.aliyuncs.com/' + res.data.basic[0].cover_uri + '?x-oss-process=image/resize,w_120');
                }
            }, 'JSON');
        });
    </script>
@endsection