@extends('layouts.app')
@section('title', '关于我们')

@section('content')
<div class="about">
    <div class="container">
        <div class="row">
            <div class="col-md-6 about_grid_left">
                <p>
                    我国的电子兴业起始于20世纪二十年代，距今已经有接近100年的历史，创建阶段后，
                    到了20世纪80年代开始有了长足发展。电子行业广泛渗入国防、经济和社会各个领域
                    ，电子产品已经成为生产环节、科研领域、办公、日常生活不可缺少的东西。目前我
                    国已经成为全球第三大电子信息产品制造国，电子行业已经成为国民经济的支柱型产
                    业。随着信息化的深入发展，电子行业得到飞快发展。
                </p>
                <div class="col-xs-2 about_grid_left1" style="height: 9%;">
                    <i class="fas fa-share-square fa-2x"></i>
                </div>
                <div class="col-xs-10 about_grid_left2">
                    <p>
                        我们一直致力于推动 “货真价实、物美价廉、按需定制”产品的普及，帮助更多的消费者享用海量且丰富的产品，获得更高的生活品质。
                    </p>
                </div>
                <div class="clearfix"> </div>
                <div class="col-xs-2 about_grid_left1" style="height: 13%;">
                    <i class="fas fa-bolt fa-2x"></i>
                </div>
                <div class="col-xs-10 about_grid_left2">
                    <p>
                        为了更好保障产品质量，促进平台产品品质提升，平台联合国家权威质量机构成立标准联盟，
                        用于制定平台产品质量标准。标准按照国家正规质量标准模式进行制订，其中包括判定原则和项目检测方法。
                        平台将严格按照此区域质量标准对商品进行品质管理。
                    </p>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="col-md-6 about_grid_right">
                <img src="{{ URL::asset('/images/about/about_show1.jpg') }}" class="img-responsive" />
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>

<div class="team">
    <div class="container">
        <h3>品牌动态</h3>
        <div class="row team_grids">
            <div class="piclist">
                <ul>
                    <li><img src="{{ URL::asset('/images/about/about1.png') }}" onmouseover="this.src='{{ URL::asset('/images/about/about1_click.png') }}';" onmouseout="this.src='{{ URL::asset('/images/about/about1.png') }}';" /></li>
                    <li><img src="{{ URL::asset('/images/about/about2.png') }}" onmouseover="this.src='{{ URL::asset('/images/about/about2_click.png') }}';" onmouseout="this.src='{{ URL::asset('/images/about/about2.png') }}';" /></li>
                    <li><img src="{{ URL::asset('/images/about/about3.png') }}" onmouseover="this.src='{{ URL::asset('/images/about/about3_click.png') }}';" onmouseout="this.src='{{ URL::asset('/images/about/about3.png') }}';" /></li>
                    <li><img src="{{ URL::asset('/images/about/about4.png') }}" onmouseover="this.src='{{ URL::asset('/images/about/about4_click.png') }}';" onmouseout="this.src='{{ URL::asset('/images/about/about4.png') }}';" /></li>
                    <li><img src="{{ URL::asset('/images/about/about5.png') }}" onmouseover="this.src='{{ URL::asset('/images/about/about5_click.png') }}';" onmouseout="this.src='{{ URL::asset('/images/about/about5.png') }}';" /></li>
                    <li><img src="{{ URL::asset('/images/about/about6.png') }}" onmouseover="this.src='{{ URL::asset('/images/about/about6_click.png') }}';" onmouseout="this.src='{{ URL::asset('/images/about/about6.png') }}';" /></li>
                </ul>
            </div>
            <p>
                无论我们走得多远 都只想回到原点 已最初的那份虔诚的心做每一件事
                <br />
                为“给每一位顾客献上优质产品”的心愿而努力
                <br />
                为“让 CENDO 产品走进千家万户”的目标而奋斗
            </p>
        </div>
    </div>
</div>

<div class="team-bottom">
    <div class="container">
        <h3>你准备好购物了吗？</h3>
        <a href="{{ url('/products') }}">点击立即查看</a>
        <img src="{{ URL::asset('/images/about/about_show2.png') }}" />
    </div>
</div>
@endsection