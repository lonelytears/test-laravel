<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>精英招募令</title>
</head>
<style>
    body{
        margin-top:-14px;
        margin-bottom: 0;
        margin-left: 8px;
        margin-right: 8px;
    }
    container{
        max-width: 768px;
        display: block;
        margin: 0 auto;
    }
    header, footer {
        text-align: center;
        padding:0;
    }

    main {
        color: #333;
        text-align: center;
        margin-left:8px;
        margin-right:8px;
        line-height: 25px;
    }

    header    img{
        width:100%;
        height:100%;
    }
    footer    img{
        width:100%;
        height:100%;
    }
    h3{
        text-align: center;
        font-family:Microsoft YaHei;
        color:#EA9E2D;
        margin-top:4px;
        margin-bottom:5px;
        font-size:14px;
    }
    main .text_box p{
        text-align: left;
        margin-top:5px;
        margin-bottom:5px;
        font-family: Microsoft YaHei;
        font-size: 13px;
    }
    input{
        /* border-radius: 5px !important; */
        /* width:50%; */
        width:195px;
        height:21px;
        background: none;
        outline: none;
        padding:0;
        border:1px solid #ddd;
    }
    form{
        border:2px solid #EA9E2D;
        padding-top:3px;
        padding-bottom: 5px;
        margin-left:20px;
        margin-right:20px;
    }
    .form_box p{
        text-align: center !important;
        text-align: left;
        margin-top:5px;
        margin-bottom:5px;
        font-family: Microsoft YaHei;
        font-size: 13px;
    }
    .form_box select{
        /* width:50%; */
        width:197px;
        height:23px;
        border:1px solid #ddd;
    }
    .submit{
        width:55px;
        height: 25px;
        color:#EA9E2D;
        background-color: #E9EEF3;
    }
</style>
<body>
    <container>
        <header>
            <div>
                <img src="{{url('/image/recruit-top.png')}}" alt="">
            </div>

        </header>
        <main>
            <div class="text_box">
                <h3>春风十里不如朴邻有你</h3>
                <p>
                    朴邻·万科物业二手房专营店(原万科租售中心)成立于2001年，是万科物业旗下房屋资产专业代理机构，主营二手房租赁，买卖，包含权证代办，新房及工商铺代理等房产经纪服务，业务覆盖北京、上海、广州、深圳、武汉、成都、杭州等全国46个大中城市。
                </p>

                <p>
                    在这里，我们希望看到人尽其才，让每个人的闪光点，照亮朴邻的美好，朴邻期待与你同行。
                </p>
            </div>
                <div class="form_box">
                    <form action="{{url()->current()}}" method="post">
                        {{csrf_field()}}
                        <p>您的姓名：
                            <input type="text" name="username" value="{{old('username')}}"></p>
                        <p>意向城市：
                            <select name="city_name" value="{{old('city_name')}}">
                                <option value="成都">成都</option>
                                <option value="昆明">昆明</option>
                                <option value="贵阳">贵阳</option>
                                <option value="重庆">重庆</option>
                                <option value="佛山">佛山</option>
                                <option value="武汉">武汉</option>
                                <option value="郑州">郑州</option>
                                <option value="西安">西安</option>
                                <option value="长春">长春</option>
                                <option value="沈阳">沈阳</option>
                                <option value="大连">大连</option>
                                <option value="广州">广州</option>
                                <option value="长沙">长沙</option>
                                <option value="南宁">南宁</option>
                                <option value="苏州">苏州</option>
                                <option value="无锡">无锡</option>
                                <option value="厦门">厦门</option>
                                <option value="福州">福州</option>
                                <option value="上海">上海</option>
                                <option value="青岛">青岛</option>
                                <option value="济南">济南</option>
                                <option value="南京">南京</option>
                                <option value="合肥">合肥</option>
                                <option value="深圳">深圳</option>
                                <option value="东莞">东莞</option>
                                <option value="杭州">杭州</option>
                                <option value="宁波">宁波</option>
                                <option value="南昌">南昌</option>
                                <option value="北京">北京</option>
                                <option value="天津">天津</option>
                                <option value="中山">中山</option>
                                <option value="南宁">南宁</option>
                                <option value="珠海">珠海</option>
                                <option value="徐州">徐州</option>
                                <option value="扬州">扬州</option>
                            </select>
                        </p>
                        <p>意向岗位：
                            <select name="job_name" value="{{old('job_name')}}">
                                <option value="人力资源类">人力资源类</option>
                                <option value="行政管理类">行政管理类</option>
                                <option value="财务管理类">财务管理类</option>
                                <option value="IT运维类">IT运维类</option>
                                <option value="置业顾问">置业顾问</option>
                                <option value="门店经理">门店经理</option>
                                <option value="片区经理">片区经理</option>
                                <option value="按揭专员">按揭专员</option>
                            </select>
                        </p>
                        <p>出生年月：
                            <input type="date" name="birthday" value="{{old('birthday')}}">
                        </p>
                        <p>电话号码：
                            <input type="text" name="mobile" value="{{old('mobile')}}">
                        </p>
                        <p>最高学历：
                            <select name="education" value="{{old('education')}}">
                                <option value="初中">初中</option>
                                <option value="高中">高中</option>
                                <option value="专科">专科</option>
                                <option value="本科">本科</option>
                                <option value="硕士">硕士</option>
                                <option value="其他">其他</option>
                            </select>
                        </p>
                        <input class="submit" type="submit" value="提交">
                    </form>
                    <p>
                        简历请投递邮箱：zhangli06@vanke.com    <br>
                        欢迎投递简历，朴邻人力资源将尽快与您联系！
                    </p>
                </div>
        </main>
        <footer>
            <div>
                <img src="{{url('/image/recruit-foot.png')}}" alt="">
            </div>
        </footer>
    </container>
    @if(session('messages'))
        <script>
            alert('{{session('messages')}}', 'success');
        </script>
    @endif
    @if(count($errors))
        <script>
            alert('{{$errors->first()}}', 'error');
        </script>
    @endif
</body>
</html>