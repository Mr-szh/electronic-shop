@extends('layouts.app')
@section('title', '联系我们')

@section('content')
<div class="mail">
    <div class="container">
        <h3>联系我们</h3>
        <div class="row mail_grids">
            <div class="col-md-3 contact-left">
                <h4>地址</h4>
                <p>
                    福建省 福州市 铜盘路, <br />
                    <span>软件大道89号软件园C区</span>
                </p>
                <ul>
                    <li>
                        <i class="fas fa-mobile-alt"></i>
                        <span class="label label-info">联系电话</span> +130-7580-5192
                    </li>
                    <li>
                        <i class="fas fa-envelope-open-text"></i>
                        <span class="label label-info">电子邮箱</span> <a href="mailto:2602589285@qq.com">2602589285@qq.com</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9 contact-right">
                <iframe src="https://map.baidu.com/" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
@endsection