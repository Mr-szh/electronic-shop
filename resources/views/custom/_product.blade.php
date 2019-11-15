<div class="card">
    <div class="card-body">
        @foreach($products as $product)
        <div class="row products-list">
            <div class="image">
                <a target="_blank" href="{{ route('products.show', ['product' => $product->id]) }}">
                    <img src="{{ URL::asset('/upload/'.$product->image[0]) }}" alt="">
                </a>
            </div>

            <div class="col-7 pro-intro">
                <h3>
                    <a target="_blank" href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->title }}</a>
                </h3>

                <select class="sku-select" name="skus">
                    @foreach($product->skus as $sku)
                    <option class="sku-option" value="{{ $sku->id }}">{{ $sku->title }}</option>
                    @endforeach
                </select>
                <input type="hidden" class="category_id" value="{{ $product->category_id }}">

                <div class="item-box" style="border-right: 1px solid #eee;">销量 <span>{{ $product->sold_count }}笔</span></div>
                <div class="item-box">评价 <span>{{ $product->review_count }}</span></div>
            </div>

            <div class="price-box">
                <span class="price"><b>￥</b>{{ $product->price }}</span>
                @php
                    $i = 0;
                    $status = "";

                    foreach ($configItems as $configItem) {
                        if (Auth::user()->id == $configItem->user_id && $configItem->productSku->product->id == $product->id) {
                            $i = 1;
                            break;
                        }
                    }
                    if ($i == 1) $status = "disabled='disabled'";
                @endphp
                <!-- <button class="btn-add-to-cart">
                    <i>+</i>
                    加入配置单
                </button> -->
                <input type="button" @if($status != '') class="btn-disabled-to-cart" @else class="btn-add-to-cart" @endif  <?php echo $status; ?> value="+ 加入配置单">
            </div>
        </div>
        @endforeach
        <div class="paging float-left">{{ $products->appends($filters)->render() }}</div>
    </div>
</div>