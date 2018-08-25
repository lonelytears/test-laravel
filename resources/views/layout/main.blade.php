<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{url('/css/weui.min.css')}}">
    <link rel="stylesheet" href="{{url('/css/jquery-weui.min.css')}}">
    <link rel="stylesheet" href="{{url('/css/icon.css')}}">
    <link rel="stylesheet" href="{{url('/css/app.css')}}">
</head>
<body>
<div class="container">
    @yield('contents')
</div>
<div class="weui-footer">
    <p class="weui-footer__text">Copyright © 2018 Vanke Service</p>
</div>
<script src="{{url('/js/jquery.min.js')}}"></script>
<script src="{{url('/js/jquery-weui.min.js')}}"></script>
<script src="{{url('/js/swiper.min.js')}}"></script>
@include('layout.message')
@include('layout.error')
@yield('extend')
</body>
</html>