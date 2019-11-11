@extends('layouts.app')
@section('title', '定制主机')

@section('content')
<div class="row mb-5">
    <div class="col-lg-12 col-md-12 topic-list">
        <span class="custom-title">请选择组件</span>
        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="margin-top:10px;">
            <div class="btn-group" role="group">

                @foreach ($categories as $item)
                @if($item->parent_id == '1')
                @if($category)
                <a href="{{ route('custom.index', ['category_id' => $item->id]) }}" class="btn btn-default category-choose category-name @if($item->id == $category->id) selected1 @endif">{{ $item->name }}</a>
                @else
                <a href="{{ route('custom.index', ['category_id' => $item->id]) }}" class="btn btn-default category-choose category-name">{{ $item->name }}</a>
                @endif
                @endif
                @endforeach

                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        配件
                        <span class="caret"></span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-right category-more" aria-labelledby="dropdownMenu1">
                        @foreach ($categories as $item)
                        @if($item->parent_id == '9')
                        <li class="dropdown-header">
                            @if($category)
                            <a href="{{ route('custom.index', ['category_id' => $item->id]) }}" class="category-name @if($item->id == $category->id) selected2 @endif">{{ $item->name }}</a>
                            @else
                            <a href="{{ route('custom.index', ['category_id' => $item->id]) }}" class="category-name">{{ $item->name }}</a>
                            @endif
                        </li>
                        <hr>
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-lg-6 col-md-6 topic-list custom-left">
        <div class="panel panel-primary">
            <div class="panel-heading left-title">
                <span class="panel-title">装机配置单</span>
            </div>
            <div class="panel panel-body">
                <ul class="list-group">
                    @foreach ($categories as $item)
                    @if(isset($item->parent_id))
                    <li class="list-group-item">
                        <h3>
                            {{ $item->name }}
                            @if($item->parent_id == '1')
                            <i class="important">*</i>
                            @endif
                        </h3>

                        @foreach($configItems as $configItem)
                        @if($item->id == $configItem->productSku->product->category_id)
                        <span class="sm-img float-left" data-id="{{ $configItem->productSku->id }}">
                            <img src="{{ URL::asset('/upload/'.$configItem->productSku->product->image[0]) }}" alt="">
                        </span>
                        <div class="config-items float-left">
                            <div class="title float-left">
                                <span>{{ $configItem->productSku->title }}</span>
                            </div>
                            <span class="minus">-</span>
                            <span class="num" rel="{{ $configItem->productSku->price }}">{{ $configItem->amount }}</span>
                            <span class="plus">+</span>
                        </div>
                        <span class="add-price float-right"><b>￥</b>{{ $configItem->productSku->price }}</span>
                        <span class="delete">x</span>
                        @else
                        <div class="col-auto float-left choose-product">请选择商品</div>
                        <div class="left-operation">
                            <a href="{{ route('custom.index', ['category_id' => $item->id]) }}" class="create">添加</a>
                            <span class="delete">x</span>
                        </div>
                        @endif
                        @endforeach
                    </li>
                    @endif
                    @endforeach
                </ul>
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="left-count">共计0项</span>
                        <span class="right-total">
                            合计
                            <span>￥0</span>
                        </span>
                    </li>
                    <li class="list-group-item">
                        <p>
                            <b>备注: </b>
                        </p>
                        <textarea class="form-control" rows="5"></textarea>
                        <div class="buttons">
                            <button class="btn btn-default custom">定制配置单</button>
                            <button class="btn btn-default button-style">预览</button>
                            <button class="btn btn-default button-style">清空</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 topic-list custom-right">
        <div class="panel panel-primary">
            <form action="{{ route('custom.index') }}" class="search-form">
                <input type="hidden" name="filters">
                <div class="panel-heading">
                    @if ($category)
                    <span class="float-left">已选择</span>
                    <font id="change" class="float-left">
                        {{ $category->name }}
                    </font>
                    @else
                    <span class="float-left">以下为所有商品：</span>
                    @endif
                    <span class="products-count float-left">共 <font>{{ count($products) }}</font> 款</span>
                    <div class="col-md-4 float-right" style="margin-top:0.5rem;">
                        <select name="order" class="form-control form-control-sm float-right margin-style">
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

                <div class="panel-choose">
                    <div class="form-row">
                        <div class="col-auto category-breadcrumb">
                            <a class="all-products" href="{{ route('custom.index') }}">全部</a> >
                            @if (isset($category))
                            @foreach($category->ancestors as $ancestor)
                                <span class="category">
                                    <a href="{{ route('custom.index', ['category_id' => $ancestor->id]) }}">{{ $ancestor->name }}</a>
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
                        <div class="col-auto margin-style"><input type="text" class="form-control form-control-sm" name="search" placeholder="搜索" AUTOCOMPLETE="off"></div>
                        <div class="col-auto"><button class="btn btn-primary btn-sm">搜索</button></div>
                    </div>
                </div>
            </form>

            @if ($category && $category->is_directory)
            <div class="category-items">
                <div class="filters">
                    <div class="col-4 filter-key float-left">子类目：</div>
                    <div class="col-9 filter-values float-left">
                        @foreach($category->children as $child)
                        <a href="{{ route('custom.index', ['category_id' => $child->id]) }}">{{ $child->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if($properties)
            <div class="category-items">
                <div class="filters">
                    @foreach($properties as $property)
                    <div class="col-4 filter-key float-left">{{ $property['key'] }}：</div>
                    <div class="col-9 filter-values float-left">
                        @foreach($property['values'] as $value)
                        <a href="javascript: appendFilterToQuery('{{ $property['key'] }}', '{{ $value }}')">{{ $value }}</a>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="panel panel-body">
                @include('custom._product')
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
            var searches = parseSearch();

            if (searches['filters']) {
                $('.search-form input[name=filters]').val(searches['filters']);
            }

            $('.search-form').submit();
        });

        $('.btn-add-to-cart').click(function () {
            axios.post('{{ route('config.add') }}', {
                sku_id: $(this).parent().parent().find('select[name=skus]').val(),
                amount: '1',
            }).then(function() {
                swal('加入配置成功', '', 'success');
            }, function (error) {
                if (error.response.status === 401) {
                    swal('请先登录', '', 'error');
                } else if (error.response.status === 422) {
                    var html = '<div>';
                    _.each(error.response.data.errors, function (errors) {
                        _.each(errors, function (error) {
                            html += error+'<br>';
                        })
                    });
                    html += '</div>';
                    swal({content: $(html)[0], icon: 'error'})
                } else {
                    swal('系统错误', '', 'error');
                }
            })
        });
    })

    function parseSearch() {
        var searches = {};
        location.search.substr(1).split('&').forEach(function (str) {
            var result = str.split('=');
            searches[decodeURIComponent(result[0])] = decodeURIComponent(result[1]);
        });

        return searches;
    }


    function buildSearch(searches) {
        var query = '?';
        _.forEach(searches, function (value, key) {
            query += encodeURIComponent(key) + '=' + encodeURIComponent(value) + '&';
        });

        return query.substr(0, query.length - 1);
    }

    function appendFilterToQuery(name, value) {
        var searches = parseSearch();

        if (searches['filters']) {
            searches['filters'] += '|' + name + ':' + value;
        } else {
            searches['filters'] = name + ':' + value;
        }

        location.search = buildSearch(searches);
    }

    function removeFilterFromQuery(name) {
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
        location.search = buildSearch(searches);
    }
</script>
@endsection