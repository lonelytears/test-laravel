@extends('layout.main')

@section('title', '签约管理')

@section('contents')

    <!-- 头部 -->
{{--    <div class="weui-flex nav_header">
        <div class="weui-flex__item">
                <a href="{{url('look/submit')}}" class="weui-btn weui-btn_plain-primary"><span class="icon icon-search"></span>我要看房</a>
        </div>
        <div class="weui-flex__item">
                <a href="" class="weui-btn weui-btn_plain-primary"><span class="icon icon-friends"></span>身份详情</a>
        </div>
    </div>--}}
    <!-- 卡片 -->
    <div class="weui-panel weui-panel_access">
        <div class="weui-panel__hd build_info_title">签约管理</div>
            <ul>
                @if($user_contract && $user_contract->count())
                    @foreach ($user_contract as $item)
                    <li>
                        <div class="weui-panel__bd">
                            <a href="{{$item->viewpdf_url}}" class="weui-media-box weui-media-box_appmsg">
                                <div class="weui-media-box__bd">
                                    {{--<h4 class="weui-media-box__title">{{$item->community_name}}</h4>--}}
                                    <span class="weui-media-box__desc">{{$item->community_name}} </span>
                                    <span class="weui-media-box__desc" style="float: right;">{{$item->get_status_name($item->status)}}</span>
                                    <span class="weui-media-box__desc">{{$item->home_name}}</span>
                                    {{ csrf_token() }}
                                    {{--<p class="weui-media-box__desc"><span class="icon icon-clock"></span> </p>--}}
                                </div>
                                {{--<span class="weui_cell_ft"><span class="icon icon-right"></span></span>--}}
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