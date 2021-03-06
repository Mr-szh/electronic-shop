@extends('layouts.app')
@section('title', $product->title)

@section('content')
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <div class="card">
            <div class="card-body product-info">
                <div class="row">
                    <div class="col-5">
                        <div id="bigImg" class="active">
                            <img src="{{ URL::asset('/upload/'.$product->image[0]) }}" alt="" width="100%">
                        </div>
                        <div class="slider-1">
                            @foreach($product->image as $image)
                                <div class="li"><img src="{{ URL::asset('/upload/'.$image) }}" alt=""></div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="title">{{ $product->long_title ?: $product->title }}</div>

                        @if($product->type === \App\Models\Product::TYPE_CROWDFUNDING)
                        <div class="crowdfunding-info">
                            <div class="have-text">已筹到</div>
                            <div class="total-amount">
                                <span class="symbol">￥</span>{{ $product->crowdfunding->total_amount }}
                            </div>

                            <div class="progress">
                                <div class="progress-bar progress-bar-success progress-bar-striped"
                                    role="progressbar"
                                    aria-valuenow="{{ $product->crowdfunding->percent }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                    style="min-width: 1em; width: {{ min($product->crowdfunding->percent, 100) }}%">
                                </div>
                            </div>
                            
                            <div class="progress-info">
                                <span class="current-progress">当前进度：{{ $product->crowdfunding->percent }}%</span>
                                <span class="float-right user-count">{{ $product->crowdfunding->user_count }}名支持者</span>
                            </div>

                            @if ($product->crowdfunding->status === \App\Models\CrowdfundingProduct::STATUS_FUNDING)
                            <div>此次众筹必须在
                                <span class="text-red">{{ $product->crowdfunding->end_at->format('Y-m-d H:i:s') }}</span>
                                前得到
                                <span class="text-red">￥{{ $product->crowdfunding->target_amount }}</span>
                                的支持才可成功，
                                <!-- Carbon 对象的 diffForHumans() 方法可以计算出与当前时间的相对时间，更人性化 -->
                                筹款将在<span class="text-red">{{ $product->crowdfunding->end_at->diffForHumans(now()) }}</span>结束！
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="price"><label>价格</label><em>￥</em><span>{{ $product->price }}</span></div>
                        <div class="sales_and_reviews">
                            <div class="sold_count">累计销量 <span class="count">{{ $product->sold_count }}</span></div>
                            <div class="review_count">累计评价 <span class="count">{{ $product->review_count }}</span></div>
                            <div class="rating" title="评分 {{ $product->rating }}">评分 <span class="count">{{ str_repeat('★', floor($product->rating)) }}{{ str_repeat('☆', 5 - floor($product->rating)) }}</span></div>
                        </div>
                        @endif

                        <div class="skus">
                            <label>选择</label>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                @foreach($product->skus as $sku)
                                <label class="btn sku-btn" data-price="{{ $sku->price }}" data-stock="{{ $sku->stock }}" data-toggle="tooltip" title="{{ $sku->description }}" data-placement="bottom">
                                    <input type="radio" name="skus" autocomplete="off" value="{{ $sku->id }}"> {{ $sku->title }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="cart_amount"><label>数量</label><input type="text" class="form-control form-control-sm" value="1"><span>件</span><span class="stock"></span></div>
                        <div class="buttons">
                            @if($favored)
                                <button class="btn btn-danger btn-disfavor">取消收藏</button>
                            @else
                                <button class="btn btn-success btn-favor">❤ 收藏</button>
                            @endif

                            @if($product->type === \App\Models\Product::TYPE_CROWDFUNDING)
                                @if(Auth::check())
                                    @if($product->crowdfunding->status === \App\Models\CrowdfundingProduct::STATUS_FUNDING)
                                    <button class="btn btn-primary btn-crowdfunding">参与众筹</button>
                                    @else
                                    <button class="btn btn-primary disabled">
                                        {{ \App\Models\CrowdfundingProduct::$statusMap[$product->crowdfunding->status] }}
                                    </button>
                                    @endif
                                @else
                                    <a class="btn btn-primary" href="{{ route('login') }}">请先登录</a>
                                @endif
                            @elseif($product->on_sale)
                            <button class="btn btn-primary btn-add-to-cart">加入购物车</button>
                            @else
                            <button class="btn btn-primary btn-error">该商品已下架</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="product-detail">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#product-detail-tab" aria-controls="product-detail-tab" role="tab" data-toggle="tab" aria-selected="true">商品详情</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#product-reviews-tab" aria-controls="product-reviews-tab" role="tab" data-toggle="tab" aria-selected="false">用户评价</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="product-detail-tab"> 

                            <div class="properties-list">
                                <div class="properties-list-title">产品参数：</div>
                                <ul class="properties-list-body">
                                @foreach($product->grouped_properties as $name => $values)
                                    <li>{{ $name }}：{{ join(' ', $values) }}</li>
                                @endforeach
                                </ul>
                            </div>

                            <div class="product-description">
                                {!! $product->description !!}
                            </div>

                            <div>
                                @if($product->images)
                                    @foreach($product->images as $img)
                                    <img class="cover-min" src="{{ URL::asset('/upload/'.$img) }}" alt="">
                                    @endforeach 
                                @endif
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="product-reviews-tab">
                            <table class="tabel table-bordered table-striped" style="width:100%;font-size:15px;">
                                <thead>
                                    <tr>
                                        <td>用户</td>
                                        <td>商品</td>
                                        <td>评分</td>
                                        <td>评价</td>
                                        <td>时间</td>
                                    </tr>
                                </thead>
                                @if($reviews->count())
                                <tbody>
                                @foreach($reviews as $review)
                                <tr>
                                    <td>{{ $review->order->user->name }}</td>
                                    <td>{{ $review->productSku->title }}</td>
                                    <td>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                                    <td>{{ $review->review }}</td>
                                    <td>{{ $review->reviewed_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                                @else
                                <tbody>
                                    <tr class="text-center">
                                        <td class="nonentity" colspan="5" style="color:red;font-size:20px;letter-spacing:8px;">该商品暂无评论!</td>
                                    </tr>
                                </tbody>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                @if(count($similar) > 0)
                <div class="similar-products">
                    <div class="title">猜你喜欢</div>
                    <div class="row products-list">
                        @foreach($similar as $p)
                        <div class="col-3 product-item">
                            <div class="product-content">
                                <div class="top">
                                    <div class="img">
                                        <a href="{{ route('products.show', ['product' => $p->id]) }}">
                                            <img src="{{ URL::asset('/upload/'.$p->image[0]) }}" alt="">
                                        </a>
                                    </div>
                                    <div class="price">
                                        <b>￥</b>{{ $p->price }}
                                    </div>
                                    <div class="title">
                                    <a href="{{ route('products.show', ['product' => $p->id]) }}">{{ $p->title }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
@section('scriptsAfterJs')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover'
        });

        $('.sku-btn').click(function() {
            $('.product-info .price span').text($(this).data('price'));
            $('.product-info .stock').text('库存：' + $(this).data('stock') + '件');
        });

        $('.btn-error').click(function () {
            swal({
                title: "该商品已下架！",
                icon: "error",
            });
        });

        $('.btn-favor').click(function () {
            axios.post('{{ route('products.favor', ['product' => $product->id]) }}').then(function () {
                swal('收藏成功', '', 'success').then(function () {
                    location.reload();
                });
            }, function(error) {
                if (error.response && error.response.status === 401) {
                    swal('请先登录', '', 'error');
                } else if (error.response && (error.response.data.msg || error.response.data.message)) {
                    swal(error.response.data.msg ? error.response.data.msg : error.response.data.message, '', 'error');
                }  else {
                    swal('系统错误', '', 'error');
                }
            });
        });

        $('.btn-disfavor').click(function () {
            axios.delete('{{ route('products.disfavor', ['product' => $product->id]) }}').then(function () {
                swal('取消收藏成功', '', 'success').then(function () {
                    location.reload();
                });
            });
        });

        $('.btn-add-to-cart').click(function () {
            axios.post('{{ route('cart.add') }}', {
                sku_id: $('label.active input[name=skus]').val(),
                amount: $('.cart_amount input').val(),
            }).then(function () {
                swal('加入购物车成功', '', 'success').then(function () {
                    location.reload();
                });
            }, function (error) {
                if (error.response.status === 401) {
                    swal('请先登录', '', 'error');
                } else if (error.response.status === 400) {
                    swal(error.response.data.msg, '', 'error')
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

        var bigImg = document.getElementById('bigImg');
        // 该选择器类似于css选择器
        var ul = document.querySelector('div.slider-1');
        var lis = document.querySelectorAll('div.li');

        var allPage = lis.length;
        var page = 0;

        var timer = null;

        for (var i = 0; i < allPage; i++) {
            lis[i].index = i;

            lis[i].onclick = function () {
                page = this.index;

                slider();
            }
        }

        function slider() {
            bigImg.style.animation = "action 0.9s";

            bigImg.getElementsByTagName('img')[0].src = lis[page].getElementsByTagName('img')[0].src;

            setTimeout(function () {
                bigImg.style.animation = "";
            }, 900);

            for (var i = 0; i < lis.length; i++) {
                lis[i].style.opacity = 0.7;
                if (i == page) {
                    lis[i].style.opacity = 1;
                }
            }
        }

        ul.onmouseover = function () {
            clearInterval(timer);
            timer = null;
        }

        ul.onmouseout = function () {
            lunbo();
        }

        function lunbo() {
            timer = setInterval(function () {
                page++;
                if (page == allPage) {
                    page = 0;
                }
                slider();
            }, 1500);
        }

        lunbo();

        $('.btn-crowdfunding').click(function () {
            if (!$('label.active input[name=skus]').val()) {
                swal('请先选择商品', '', 'error');
                return;
            }

            var addresses = {!! json_encode(Auth::check() ? Auth::user()->addresses : []) !!};
  
            var $form = $('<form></form>');
            $form.append('<div class="form-group row">' +
                '<label class="col-form-label col-sm-3">选择地址</label>' +
                '<div class="col-sm-9">' +
                '<select class="custom-select" name="address_id"></select>' +
                '</div></div>');
            addresses.forEach(function (address) {
                $form.find('select[name=address_id]')
                .append("<option value='" + address.id + "'>" +
                    address.full_address + ' ' + address.contact_name + ' ' + address.contact_phone +
                    '</option>');
            });
            $form.append('<div class="form-group row">' +
                '<label class="col-form-label col-sm-3">购买数量</label>' +
                '<div class="col-sm-9"><input class="form-control" name="amount" autocomplete="off">' +
                '</div></div>');

            swal({
                text: '参与众筹',
                content: $form[0],
                buttons: ['取消', '确定']
            }).then(function (ret) {
                if (!ret) {
                    return;
                }

                var req = {
                    address_id: $form.find('select[name=address_id]').val(),
                    amount: $form.find('input[name=amount]').val(),
                    sku_id: $('label.active input[name=skus]').val()
                };

                axios.post('{{ route('crowdfunding_orders.store') }}', req).then(function (response) {
                    swal('订单提交成功', '', 'success').then(() => {
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
                    } else if (error.response.status === 403) {
                        swal(error.response.data.msg, '', 'error');
                    } else {
                        swal('系统错误', '', 'error');
                    }
                });
            });
        });
    });
</script>
@endsection