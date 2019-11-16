@extends('layouts.app')
@section('title', '定制主机')

@section('content')
<div class="row mb-5">
    <div class="col-lg-12 col-md-12 topic-list">
        <span class="custom-title">请选择组件</span>
        <span class="sign"><i class="important">*</i> 号为必选项</span>
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
                        @if($item->parent_id == '11')
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

                    @if(isset($item->parent_id) && $item->parent_id == '1' || $item->parent_id == '11')
                    <li class="list-group-item">
                        <h3>
                            {{ $item->name }}
                            @if($item->parent_id == '1')
                            <i class="important">*</i>
                            @endif
                        </h3>

                        @foreach($configItems as $configItem)
                        @if($equal = $item->id == $configItem->productSku->product->category_id)
                        <span class="sm-img float-left" data-id="{{ $configItem->productSku->id }}">
                            <img src="{{ URL::asset('/upload/'.$configItem->productSku->product->image[0]) }}" alt="">
                        </span>
                        <div class="config-items float-left">
                            @if(!$configItem->productSku->product->on_sale)
                            <span class="warning">该商品已下架</span>
                            @else
                            <div class="title float-left">
                                <a target="_blank" href="{{ route('products.show', ['product' => $configItem->productSku->product->id]) }}" class="title-style">{{ $configItem->productSku->product->title }}</a>
                            </div>
                            <span class="setNum">-</span>
                            <!-- <span class="num" rel="{{ $configItem->productSku->price }}">{{ $configItem->amount }}</span> -->
                            <input class="num" type="text" stock="{{ $configItem->productSku->stock }}" value="{{ $configItem->amount }}" @if($item->id == '3' || $item->id == '8' || $item->id == '9' || $item->id == '10' || $item->id == '12' || $item->id == '13' || $item->id == '14' || $item->id == '15') max = "1" @elseif($item->id == '2' || $item->id == '6') max = "2" @elseif($item->id == '5' || $item->id == '7') max = "4" @elseif($item->id == '4') max="8" @endif>
                            <span class="setNum">+</span>
                            @endif
                        </div>
                        <span class="add-price float-right" rel="{{ $configItem->productSku->price }}"><b>￥</b><span class="singular">{{ $configItem->productSku->price }}</span></span>
                        <span class="btn-remove">x</span>
                        @break
                        @endif
                        @endforeach
                        
                        @if(isset($equal))
                        @if($equal != 1)
                        <div class="col-auto float-left choose-product">请选择商品</div>
                        <div class="left-operation">
                            <a href="{{ route('custom.index', ['category_id' => $item->id]) }}" class="create">添加</a>
                            <span class="delete">x</span>
                        </div>
                        @endif
                        @else
                        <div class="col-auto float-left choose-product">请选择商品</div>
                        <div class="left-operation">
                            <a href="{{ route('custom.index', ['category_id' => $item->id]) }}" class="create">添加</a>
                            <span class="delete">x</span>
                        </div>
                        @endif

                    </li>
                    @endif
                @endforeach
                </ul>
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="left-count">共计<span class="total-number">0</span>项</span>
                        <span class="right-total">
                            合计
                            <span>￥<span class="total-price">0</span></span>
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span class="address-title">选择收货地址:</span>
                        <div class="address-select">
                            <select class="form-control" name="address">
                                @foreach($addresses as $address)
                                <option value="{{ $address->id }}">{{ $address->full_address }} {{ $address->contact_name }} {{ $address->contact_phone }}</option>
                                @endforeach
                            </select>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <p>
                            <b>备注: </b>
                        </p>
                        <textarea name="remark" class="form-control" rows="5"></textarea>
                        <div class="buttons">
                            @php
                                $i = 1;
                                $error = '0';
                                $status = "";

                                if (count($configItems) != 0) {
                                    for ($i = 2; $i <= 10; $i++) {
                                        foreach ($configItems as $configItem) {
                                            if ($configItem->productSku->product->category_id == $i) {
                                                $error = '0';
                                                break;
                                            } else {
                                                $error = '1';
                                            }
                                        }
                                        
                                        if ($error == '1') {
                                            break;
                                        }
                                    }

                                    if ($error == '1') {
                                        $status = "disabled='disabled'";
                                    }
                                } else {
                                    $status = "disabled='disabled'";
                                }
                                
                            @endphp
                            <!-- <button class="btn btn-default custom @php echo $i; @endphp">定制配置单</button> -->
                            <input type="button" class="btn btn-default custom btn-create-order" @php echo $status; @endphp value="定制配置单">
                            <!-- <button class="btn btn-default button-style">暂存</button> -->
                            <button class="btn btn-default button-style btn-removeAll">清空</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 topic-list custom-right">
        <div class="panel panel-primary">
            <form action="{{ route('custom.index') }}" class="search-form">
                {{ csrf_field() }}
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
        changeNumber();
        
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
                category_id: $(this).parent().parent().find('.category_id').val(),
                amount: '1',
            }).then(function() {
                swal('加入配置成功', '', 'success').then(function () {
                    location.reload();
                });
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

        $('.btn-remove').click(function () {
            var id = $(this).parent().find('.sm-img').data('id');
            swal({
                title: "确认要将该商品移除？",
                icon: "warning",
                buttons: ['取消', '确定'],
                dangerMode: true,
            }).then(function(willDelete) {
                if (!willDelete) {
                    return;
                }
                axios.delete('/config/' + id).then(function () {
                    swal('移除成功', '', 'success').then(function () {
                        location.reload();
                    });
                })
            });
        });

        $('.btn-removeAll').click(function () {
            axios.delete('{{ route('config.removeAll') }}').then(function () {
                swal('清空成功', '', 'success').then(function () {
                    location.reload();
                });
            });
        });

        $(".setNum").click(function () {
            var price = $(this).closest('li').find('.add-price').attr("rel");

            if ($(this).text() === '-') {
                if ($(this).next().val() > 1) {
                    var num = parseInt($(this).next().val()) - parseInt(1);
                    $(this).next().attr("value", num);
                    $(this).next().val(num);
                    $(this).parent().next().find('.singular').text(((parseInt(price)).toFixed(2) * (parseInt(num)).toFixed(2)).toFixed(2));
                }
            } else if ($(this).text() === '+') {
                var stock = $(this).prev().attr('stock');
                var max = $(this).prev().attr('max');

                if ($(this).prev().val() !== max && $(this).prev().val() < parseInt(stock)) {
                    var num = parseInt($(this).prev().val()) + parseInt(1);
                    $(this).prev().attr("value", num);
                    $(this).prev().val(num);
                    $(this).parent().next().find('.singular').text(((parseInt(price)).toFixed(2) * (parseInt(num)).toFixed(2)).toFixed(2));
                }
            }

            changeNumber();
        });

        $('.btn-create-order').click(function () {
            var req = {
                address_id: $('.address-select').find('select[name=address]').val(),
                items: [],
                remark: $('.list-group-item').find('textarea[name=remark]').val(),
                custom: 1,
            };

            $('.list-group').find('.list-group-item').each(function () {
                var $input = $(this).find('div.config-items').find('.num');

                if ($input.val() == 0 || isNaN($input.val())) {
                    return;
                }

                req.items.push({
                    sku_id: $(this).find('span.sm-img').data('id'),
                    amount: $input.val(),
                })
            });
            
            axios.post('{{ route('orders.store') }}', req).then(function (response) {
                swal('订单提交成功', '', 'success').then(function () {
                    location.href = '/orders/' + response.data.id;
                });
            }, function (error) {
                if (error.response.status === 422) {
                    var html = '<div>';
                    _.each(error.response.data.errors, function (errors) {
                        _.each(errors, function (error) {
                            html += error+'<br>';
                        })
                    });
                    html += '</div>';
                    swal({content: $(html)[0], icon: 'error'})
                } else if (error.response.status == 403) {
                    swal(error.response.data.msg, '', 'error');
                } else {
                    swal('系统错误', '', 'error');
                }
            });
        });

        function changeNumber() {
            var price = 0;
            var num = 0;
            var totalnum = 0;
            var p = 0;

            $('.list-group').find('.list-group-item').each(function () {
                num = parseInt($(this).find('div.config-items').find('.num').val());
                p = Number($(this).find('span.add-price').attr("rel"));

                if (!isNaN(num) && !isNaN(p)) {
                    totalnum += num;     
                    price += parseInt(p) * num;     
                }     
            });
            $('.total-number').text(totalnum);
            $('.total-price').text(price);
        }

    });

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