@extends('layouts.app')
@section('title', '首页')

@section('content')
<div class="hg">
  <div class="bottom">
  </div>
  <div class="inner">
    <div class="float-layer">
      <h4>创新 独立 超越</h4>
      <h1 class="edo">ES</h1>
      <h6 id="timer"></h6>
    </div>
    <img class="logo-layer" src="{{ URL::asset('/images/logo.jpg') }}" />
  </div>
</div>

<!-- <div id="demo" class="carousel slide" data-ride="carousel"> -->
  <!-- 指示符 -->
  <!-- <ul class="carousel-indicators">
    <li data-target="#demo" data-slide-to="0" class="active"></li>
    <li data-target="#demo" data-slide-to="1"></li>
    <li data-target="#demo" data-slide-to="2"></li>
  </ul> -->

  <!-- 轮播图片 -->
  <!-- <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="{{ URL::asset('/advertising/bg1.jpg') }}" style="max-width:1000px;max-height:479px;">
      <div class="carousel-caption">
        <h3>第一张图片描述标题</h3>
        <p>描述文字!</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="{{ URL::asset('/advertising/bg2.jpg') }}" style="max-width:1000px;max-height:479px;">
      <div class="carousel-caption">
        <h3>第二张图片描述标题</h3>
        <p>描述文字!</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="{{ URL::asset('/advertising/bg3.jpg') }}" style="max-width:1000px;max-height:479px;">
      <div class="carousel-caption">
        <h3>第三张图片描述标题</h3>
        <p>描述文字!</p>
      </div>
    </div>
  </div> -->

  <!-- 左右切换按钮 -->
  <!-- <a class="carousel-control-prev" href="#demo" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#demo" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>

</div> -->

<!-- 推荐商品栏 开始 -->
@if(count($products) > 0)
<div class="similar-products">
  <div class="show-title">销量最高</div>
  <div class="row products-list">
    @foreach($products as $product)
    <div class="col-3 product-item">
      <div class="product-content">
        <div class="top">
          <div class="img">
            <a href="{{ route('products.show', ['product' => $product->id]) }}">
              <img src="{{ URL::asset('/upload/'.$product->image[0]) }}" alt="">
            </a>
          </div>
          <div class="price">
            <b>￥</b>{{ $product->price }}
          </div>
          <div class="title">
            <a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->title }}</a>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="col-md-3 show-more">
    <a class="btn btn-default btn-lg btn-block btn-more" href="{{ url('/products') }}">查看更多</a>
  </div>
</div>
@endif
<!-- 推荐商品栏 开始 -->

@stop
@section('scriptsAfterJs')
<script>
  let timer = document.getElementById("timer")

  function update_time() {
    timer.innerHTML = "北京时间 " + new Date().toTimeString().split(' ')[0]
  }
  update_time()
  setInterval((e) => {
    update_time()
  }, 1000)
</script>
@endsection