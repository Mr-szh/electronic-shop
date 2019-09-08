@extends('layouts.app')
@section('title', '首页')

@section('content')
<h6 id="timer"></h6>

<div id="demo" class="carousel slide" data-ride="carousel">
  <!-- 指示符 -->
  <ul class="carousel-indicators">
    <li data-target="#demo" data-slide-to="0" class="active"></li>
    <li data-target="#demo" data-slide-to="1"></li>
    <li data-target="#demo" data-slide-to="2"></li>
  </ul>

  <!-- 轮播图片 -->
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="https://static.runoob.com/images/mix/img_fjords_wide.jpg">
      <div class="carousel-caption">
        <h3>第一张图片描述标题</h3>
        <p>描述文字!</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="https://static.runoob.com/images/mix/img_nature_wide.jpg">
      <div class="carousel-caption">
        <h3>第二张图片描述标题</h3>
        <p>描述文字!</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="https://static.runoob.com/images/mix/img_mountains_wide.jpg">
      <div class="carousel-caption">
        <h3>第三张图片描述标题</h3>
        <p>描述文字!</p>
      </div>
    </div>
  </div>

  <!-- 左右切换按钮 -->
  <a class="carousel-control-prev" href="#demo" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#demo" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>

</div>

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