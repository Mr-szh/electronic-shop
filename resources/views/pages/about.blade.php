@extends('layouts.app')
@section('title', '关于我们')

@section('content')
<div class="about">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p>
                    我国的电子兴业起始于20世纪二十年代，距今已经有接近100年的历史，创建阶段后，
                    到了20世纪80年代开始有了长足发展。电子行业广泛渗入国防、经济和社会各个领域
                    ，电子产品已经成为生产环节、科研领域、办公、日常生活不可缺少的东西。目前我
                    国已经成为全球第三大电子信息产品制造国，电子行业已经成为国民经济的支柱型产
                    业。随着信息化的深入发展，电子行业得到飞快发展。
                </p>
                <div class="col-xs-2">
                    <i class="fas fa-share-square fa-2x"></i>
                </div>
                <div class="col-xs-10">
                    <p>
                        我们一直致力于推动 “货真价实、物美价廉、按需定制”产品的普及，帮助更多的消费者享用海量且丰富的产品，获得更高的生活品质。
                    </p>
                </div>
                <div class="clearfix"> </div>
                <div class="col-xs-2">
                    <i class="fas fa-bolt fa-2x"></i>
                </div>
                <div class="col-xs-10">
                    <p>
                        为了更好保障产品质量，促进平台产品品质提升，平台联合国家权威质量机构成立标准联盟，
                        用于制定平台产品质量标准。标准按照国家正规质量标准模式进行制订，其中包括判定原则和项目检测方法。
                        平台将严格按照此区域质量标准对商品进行品质管理。
                    </p>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="col-md-6">
                <img src="{{ URL::asset('/images/about/about_show1.jpg') }}" class="img-responsive" />
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>

<div class="team">
    <div class="container">
        <h3>品牌动态</h3>
        <div class="row">
            
        </div>
    </div>
</div>
@endsection