<div class="card">
    <div class="card-body">
        @foreach($products as $product)
        <div class="row products-list">
            <div class="image">
                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                    <img src="{{ URL::asset('/upload/'.$product->image[0]) }}" alt="">
                </a>
            </div>

            <div class="col-7 pro-intro">
                <h3>
                    <a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->title }}</a>
                </h3>
                <div class="item-box" style="border-right: 1px solid #eee;">销量 <span>{{ $product->sold_count }}笔</span></div>
                <div class="item-box">评价 <span>{{ $product->review_count }}</span></div>
            </div>

            <div class="price-box">
                <span class="price"><b>￥</b>{{ $product->price }}</span>
                <a class="insert" href="">
                    <i>+</i>
                    加入配置单
                </a>
            </div>
        </div>
        @endforeach
        <div class="paging float-left">{{ $products->appends($filters)->render() }}</div>
    </div>
</div>