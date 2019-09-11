<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token 避免应用遭到跨站请求伪造攻击 -->
    <!-- 该令牌用于验证授权用户和发起请求者是否是同一个人 -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '可定制电脑商城')</title>
    
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ URL::asset('/images/favicon.ico') }}" />
    <!--<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap.min.css') }}">
  
    <script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('/js/popper.min.js') }}"></script>
    <script src="{{ URL::asset('/js/bootstrap.min.js') }}"></script> -->
    
</head>

<body>
    <!-- 自定义的辅助方法 -->
    <div id="app" class="{{ route_class() }}-page">
        @include('layouts._header')
        <div class="container">
            @yield('content')
        </div>
        @include('layouts._footer') 
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
    @yield('scriptsAfterJs')
</body>

</html>