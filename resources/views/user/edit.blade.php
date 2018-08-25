@extends('layout.main')

@section('title', '更新个人信息')

@section('contents')

        <form action="{{url('user/edit', [$user->id])}}" method="post">
            {{csrf_field()}}
            <div class="weui-cell">
                <div class="weui-cell__hd">
                  <label class="weui-label">手机号</label>
                </div>
                <div class="weui-cell__bd">
                  <input class="weui-input" type="tel" placeholder="请输入手机号" readonly value="{{$user->mobile}}">
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                  <label class="weui-label">真实姓名</label>
                </div>
                <div class="weui-cell__bd">
                  <input class="weui-input" type="text" name="real_name" placeholder="请输入真实姓名" value="{{$user->real_name}}">
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">性别</label>
                </div>
                <div class="weui-cell__bd ">
                    <input type="hidden" name="sex" value="{{$user->sex}}">
                    <input class="weui-input" id="selectSex" type="text" placeholder="请选择性别" value="{{$user->sex($user->sex)}}" data-values="{{$user->sex}}"/>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                  <label class="weui-label">身份证</label>
                </div>
                <div class="weui-cell__bd">
                  <input class="weui-input" type="text" name="id_card" placeholder="请输入真实身份证号" value="{{$user->id_card}}">
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">城市名</label>
                </div>
                <div class="weui-cell__bd">
                    <input type="hidden" name="city_id" value="{{$user->city_id}}">
                    <input class="weui-input" id="selectCity" name="city_name" type="text" placeholder="请选择城市" value="{{$user->city_name}}" data-values="{{$user->city_id}}"/>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                  <label class="weui-label">所属中介公司</label>
                </div>
                <div class="weui-cell__bd">
                  <input class="weui-input" type="text" name="business_name" placeholder="填写公司名称" value="{{$user->business_name}}">
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd">
                  <label class="weui-label">证书编号（选填）</label>
                </div>
                <div class="weui-cell__bd">
                  <input class="weui-input" type="text" name="user_business_code" placeholder="请输入中介从业资格编号" value="{{old('user_business_code')}}">
                </div>
            </div>

            <!-- <div class="weui-cell">
                <div class="weui-cell__bd">
                    <div class="weui-uploader">
                        <div class="weui-uploader__hd">
                            <p class="weui-uploader__title">身份证正反面上传</p>
                            <div class="weui-uploader__info">0/2</div>
                        </div>

                        <div class="weui-uploader__bd">

                            <div class="weui-uploader__input-box">
                                <input class="weui-uploader__input" type="file" accept="image/*" multiple="">
                            </div>
                            <div class="weui-uploader__input-box">
                                <input class="weui-uploader__input" type="file" accept="image/*" multiple="">
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="page-bd-15">
                <button type="submit" id="user_update_btn" class="weui-btn weui-btn-mini weui-btn_primary">保存</button>
                <a href="{{url('/')}}" class="weui-btn weui-btn-mini weui-btn_default">返回</a>
            </div>
        </form>

@endsection

@section('extend')
<script>
    // 加载城市
    $(function () {
        // 打开加载层
        $.showLoading();
        $.post("{{url('/api/getCity')}}", null, function (obj) {
            var items = [];
            // 城市选择
            $.each(obj.data, function (i, item) {
                items.push({
                    title: item.city_name,
                    value: item.city_id
                });
            });
            $("#selectCity").select({
                title: "选择城市",
                items: items
            });
            // 关闭加载层
            $.hideLoading();
        }, 'JSON');
    });

    // 性别选择
    $("#selectSex").select({
        title: "选择性别",
        items: [
                @foreach($user->sex() as $ind => $sex)
                    @if ($ind === 2)
                        @continue
                    @endif
                    {
                        title: "{{$sex}}",
                        value: "{{$ind}}"
                    },
                @endforeach
            ]
    });

    // 性别动态修改
    $("#selectSex").change(function () {
        $('input[name="sex"]').val($(this).data('values'));
    });
    $("#selectCity").change(function () {
        $('input[name="city_id"]').val($(this).data('values'));
    });

</script>
@endsection