@extends('layouts.app')
@section('title', '我的收藏')

@section('content')
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <div class="card">
            <div class="card-header">
                {{Auth::user ()->name }} 的收藏
                <span class="float-right">共 {{ $products->count () }} 件</span>
            </div>
            <div class="card-body">
                <div class="row products-list">
                    @foreach($products as $product)
                    @if(!$product->on_sale)
                    <div class="col-3 product-item">
                        <div class="product-content">
                            <div class="top">
                                <div class="img">
                                    <img src="{{ URL::asset('/images/product_error.jpg') }}" alt="">
                                </div>
                                <div class="price"><b>￥</b>{{ $product->price }}</div>
                                {{ $product->title }}
                            </div>
                            <div class="bottom">
                                <button class="btn btn-danger btn-disfavor" date="{{ $product->id }}" style="margin:0 auto;">取消收藏</button>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-3 product-item">
                        <div class="product-content">
                            <div class="top">
                                <div class="img">
                                    <a href="{{ route('products.show', ['product' => $product->id]) }}">
                                        <img src="{{ URL::asset('/upload/'.$product->image[0]) }}" alt="">
                                    </a>
                                </div>
                                <div class="price"><b>￥</b>{{ $product->price }}</div>
                                <div class="fav-title">
                                    <a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->title }}</a>
                                </div>
                                
                            </div>
                            <div class="bottom">
                                <div class="sold_count">销量 <span>{{ $product->sold_count }}笔</span></div>
                                <div class="review_count">评价 <span>{{ $product->review_count }}</span></div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                <div class="float-right">{{ $products->render() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scriptsAfterJs')
<script>
    $(document).ready(function() {
        $('.btn-disfavor').click(function () {
            axios.delete('{{ route('products.disfavors') }}', {
                product_id: $(this).attr('date'),
            }).then(function () {
                swal('取消收藏成功', '', 'success').then(function () {
                    location.reload();
                });
            });
        });
    });
</script>
@endsection