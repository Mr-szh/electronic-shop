@extends('layouts.app')
@section('title', '历史订单')

@section('content')
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <div class="card">
            <div class="card-header">
                历史订单　　
                <span class="float-right">共 {{ $orders->count() }} 件</span>
            </div>
            <div class="card-body">
                @if ($orders->count() == 0)
                <ul class="list-group text-center">
                    <span class="nonentity">暂无订单</span>
                </ul>
                @else 
                <ul class="list-group">
                    @foreach($orders as $order)
                    <li class="list-group-item">
                        <div class="card">
                            <div class="card-header">
                                订单号：{{ $order->no }}
                                <span class="float-right">{{ $order->created_at->format('Y-m-d H:i:s') }}</span>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>商品信息</th>
                                            <th class="text-center">单价</th>
                                            <th class="text-center">数量</th>
                                            <th class="text-center">订单总价</th>
                                            <th class="text-center">状态</th>
                                            <th class="text-center">操作</th>
                                        </tr>
                                    </thead>
                                    @foreach($order->items as $index => $item)
                                    <tr>
                                        <td class="product-info">
                                            <div class="preview">
                                                <a target="_blank" href="{{ route('products.show', [$item->product_id]) }}">
                                                    <img src="{{ URL::asset('/upload/'.$item->product->image[0]) }}">
                                                </a>
                                            </div>
                                            <div>
                                                <span class="product-title">
                                                    <a target="_blank" href="{{ route('products.show', [$item->product_id]) }}">{{ $item->product->title }}</a>
                                                </span>
                                                <span class="sku-title">{{ $item->productSku->title }}</span>
                                            </div>
                                        </td>
                                        <td class="sku-price text-center">￥{{ $item->price }}</td>
                                        <td class="sku-amount text-center">{{ $item->amount }}</td>
                                        @if($index === 0)
                                        <td rowspan="{{ count($order->items) }}" class="text-center total-amount">￥{{ $order->total_amount }}</td>
                                        <td rowspan="{{ count($order->items) }}" class="text-center">
                                            @if($order->paid_at)
                                                @if($order->ship_status === \App\Models\Order::SHIP_STATUS_RECEIVED)
                                                已收货
                                                @elseif($order->ship_status === \App\Models\Order::SHIP_STATUS_DELIVERED)
                                                已发货
                                                @elseif($order->ship_status === \App\Models\Order::SHIP_STATUS_PENDING)
                                                已支付
                                                @else
                                                {{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}
                                                @endif
                                            @elseif($order->closed)
                                                已关闭
                                            @else
                                                未支付
                                                <br>请于 {{ $order->created_at->addSeconds(config('app.order_ttl'))->format('H:i') }} 前完成支付<br>
                                                否则订单将自动关闭
                                            @endif
                                        </td>
                                        <td rowspan="{{ count($order->items) }}" class="text-center">
                                            <a class="btn btn-primary btn-sm" href="{{ route('orders.show', ['order' => $order->id]) }}">查看订单</a>
                                            @if($order->paid_at && $order->ship_status == 'received')
                                            <a class="btn btn-success btn-sm" href="{{ route('orders.review.show', ['order' => $order->id]) }}">
                                                {{ $order->reviewed ? '查看评价' : '可评价' }}
                                            </a>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                    @if($order->paid_at && !$order->refund_status === \App\Models\Order::REFUND_STATUS_SUCCESS)
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" style="font-size:15px;">
                                                物流状态：
                                                @if($order->ship_status === \App\Models\Order::SHIP_STATUS_PENDING)
                                                    未发货
                                                @elseif($order->ship_status === \App\Models\Order::SHIP_STATUS_DELIVERED)
                                                    已发货
                                                @else
                                                    已收货
                                                @endif
                                            </td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
                <div class="float-right">{{ $orders->render() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection