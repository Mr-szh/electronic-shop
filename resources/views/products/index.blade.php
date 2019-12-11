@extends('layouts.app')
@section('title', '商品列表')

@section('content')
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <div class="card">
            <div class="card-body">

                <form action="{{ route('products.index') }}" class="search-form">
                    <input type="hidden" name="filters">
                    <div class="form-row">
                        <div class="col-md-9">
                            <div class="form-row">

                                <div class="col-auto category-breadcrumb">
                                    <a href="{{ route('products.index') }}" class="all-products">全部</a> >
                                    @if($category)
                                        @foreach($category->ancestors as $ancestor)
                                            <span class="category">
                                                <a href="{{ route('products.index', ['category_id' => $ancestor->id]) }}">{{ $ancestor->name }}</a>
                                            </span>
                                            <span>&gt;</span>
                                        @endforeach
                                        <span class="category">{{ $category->name }}</span>
                                        <span> ></span>
                                        
                                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                                    @endif

                                    @foreach($propertyFilters as $name => $value)
                                    <span class="filter">{{ $name }}:
                                        <span class="filter-value">{{ $value }}</span>

                                        <a class="remove-filter" href="javascript: removeFilterFromQuery('{{ $name }}')">×</a>
                                    </span>
                                    @endforeach
                                </div>
                                <div class="col-auto"><input type="text" class="form-control form-control-sm" name="search" placeholder="请输入商品信息" autocomplete="off"></div>
                                <div class="col-auto"><button class="btn btn-primary btn-sm">搜索</button></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="order" class="form-control form-control-sm float-right">
                                <option value="">排序方式</option>
                                <option value="price_asc">价格从低到高</option>
                                <option value="price_desc">价格从高到低</option>
                                <option value="sold_count_desc">销量从高到低</option>
                                <option value="sold_count_asc">销量从低到高</option>
                                <option value="rating_desc">评价从高到低</option>
                                <option value="rating_asc">评价从低到高</option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="filters">
                    @if($category && $category->is_directory)
                    <div class="row">
                        <div class="col-3 filter-key">子类目：</div>
                        <div class="col-9 filter-values">
                        @foreach($category->children as $child)
                            <a href="{{ route('products.index', ['category_id' => $child->id]) }}">{{ $child->name }}</a>
                        @endforeach
                        </div>
                    </div>
                    @endif

                    @foreach($properties as $property)
                        <div class="row">
                            <div class="col-3 filter-key">{{ $property['key'] }}：</div>
                            <div class="col-9 filter-values">
                            @foreach($property['values'] as $value)

                                <a href="javascript: appendFilterToQuery('{{ $property['key'] }}', '{{ $value }}')">{{ $value }}</a>
                            @endforeach
                            </div>
                        </div>
                    @endforeach

                </div>

                @if ($products->count() == 0)
                <div class="card">
                    <div class="card-body">
                        <div class="list-group text-center">
                            <span class="nonentity">暂无商品</span>
                        </div>
                    </div>
                </div>
                @else
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
                                <div class="price"><b>￥</b>{{ $product->price }}</div>
                                <div class="title">
                                    <a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->title }}</a>
                                </div>
                            </div>
                            <div class="bottom">
                                <div class="sold_count">销量 <span>{{ $product->sold_count }}笔</span></div>
                                <div class="review_count">评价 <span>{{ $product->review_count }}</span></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="button-link float-right" style="margin-top:20px;">{{ $products->appends($filters)->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scriptsAfterJs')
<script>
    var filters = {!! json_encode($filters) !!};
    
    $(document).ready(function() {
        $('.search-form input[name=search]').val(filters.search);
        $('.search-form select[name=order]').val(filters.order);

        $('.search-form select[name=order]').on('change', function() {
            // 解析当前查询参数
            var searches = parseSearch();

            if (searches['filters']) {
                $('.search-form input[name=filters]').val(searches['filters']);
            }
            $('.search-form').submit();
        });
    })

    // 解析当前 Url 里的参数，并以 Key-Value 对象形式返回
    function parseSearch() {
        // 初始化一个空对象
        var searches = {};
        // location.search 会返回 Url 中 ? 以及后面的查询参数
        // substr(1) 将 ? 去除，然后以符号 & 分割成数组，然后遍历这个数组
        location.search.substr(1).split('&').forEach(function (str) {
            var result = str.split('=');
            // 将数组的第一个值解码之后作为 Key，第二个值解码后作为 Value 放到之前初始化的对象中
            searches[decodeURIComponent(result[0])] = decodeURIComponent(result[1]);
        });

        return searches;
    }

    // 根据 Key-Value 对象构建查询参数
    function buildSearch(searches) {
        var query = '?';

        _.forEach(searches, function (value, key) {
            query += encodeURIComponent(key) + '=' + encodeURIComponent(value) + '&';
        });

        // 去除最末尾的 & 符号
        return query.substr(0, query.length - 1);
    }

    function appendFilterToQuery(name, value) {
        // 解析当前 Url 的查询参数
        var searches = parseSearch();

        if (searches['filters']) {
            searches['filters'] += '|' + name + ':' + value;
        } else {
            searches['filters'] = name + ':' + value;
        }

        // 重新构建查询参数，并触发浏览器跳转
        location.search = buildSearch(searches);
    }

    function removeFilterFromQuery(name) {
        // 解析当前 Url 的查询参数
        var searches = parseSearch();
        
        if(!searches['filters']) {
            return;
        }

        var filters = [];
        searches['filters'].split('|').forEach(function (filter) {
            var result = filter.split(':');

            if (result[0] === name) {
                return;
            }

            filters.push(filter);
        });

        searches['filters'] = filters.join('|');
        // 重新构建查询参数，并触发浏览器跳转
        location.search = buildSearch(searches);
    }
  </script>
@endsection