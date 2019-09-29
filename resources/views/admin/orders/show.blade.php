<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">订单流水号：{{ $order->no }}</h3>
        <div class="box-tools">
            <div class="btn-group float-right" style="margin-right: 10px">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-default">
                    <i class="fa fa-list">列表</i>
                </a>
            </div>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-condensed">
            <tbody>
                <tr>
                    <td>买家：</td>
                    <td>{{ $order->user->name }}</td>
                    <td>支付时间：</td>
                    <td>{{ $order->paid_at->format('Y-m-d H:i:s') }}</td>
                </tr>
                <tr>
                    <td>支付方式：</td>
                    <td>{{ $order->payment_method }}</td>
                    <td>支付渠道单号：</td>
                    <td>{{ $order->payment_no }}</td>
                </tr>
                <tr>
                    <td>收货地址</td>
                    <td colspan="3">{{ $order->address['address'] }} {{ $order->address['zip'] }} {{ $order->address['contact_name'] }} {{ $order->address['contact_phone'] }}</td>
                </tr>
                <tr>
                    <!-- <td rowspan="{{ $order->items->count() + 1 }}">商品列表</td> -->
                    <td>商品列表</td>
                    <td>商品名称</td>
                    <td>单价</td>
                    <td>数量</td>
                </tr>
                @foreach($order->items as $item)
                <tr>
                    <td><img src="{{ URL::asset('/upload/'.$item->product->image[0]) }}" style="max-height:28%;max-width:28%;"></td>
                    <td>
                        <div>{{ $item->product->title }}</div>
                        <p style="margin-bottom:15px;"></p>
                        <div>{{ $item->productSku->title }}</div>
                    </td>
                    <td>￥{{ $item->price }}</td>
                    <td>{{ $item->amount }}</td>
                </tr>
                @endforeach
                <tr>
                    <td>订单金额：</td>
                    <!-- <td colspan="3" style="color:red;font-weight:bolder;font-size:20px;">￥{{ $order->total_amount }}</td> -->
                    <td style="color:red;font-weight:bolder;font-size:20px;">￥{{ $order->total_amount }}</td>
                    <td>发货状态：</td>
                    <td>{{ \App\Models\Order::$shipStatusMap[$order->ship_status] }}</td>
                </tr>
                @if($order->ship_status === \App\Models\Order::SHIP_STATUS_PENDING)
                <tr>
                    <td colspan="4">
                        <form action="{{ route('admin.orders.ship', [$order->id]) }}" method="post" class="form-inline">
                            {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('express_company') ? 'has-error' : '' }}" style="margin-right:20px;">
                                <label for="express_company" class="control-label" style="margin-right:20px;">物流公司</label>
                                <input type="text" id="express_company" name="express_company" value="" class="form-control" placeholder="输入物流公司" autocomplete="off">
                                @if($errors->has('express_company'))
                                    @foreach($errors->get('express_company') as $msg)
                                    <span class="help-block">{{ $msg }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('express_no') ? 'has-error' : '' }}" style="margin-right:20px;">
                                <label for="express_no" class="control-label" style="margin-right:20px;">物流单号</label>
                                <input type="text" id="express_no" name="express_no" value="" class="form-control" placeholder="输入物流单号" autocomplete="off">
                                @if($errors->has('express_no'))
                                    @foreach($errors->get('express_no') as $msg)
                                    <span class="help-block">{{ $msg }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <button type="submit" class="btn btn-success" id="ship-btn">发货</button>
                        </form>
                    </td>
                </tr>
                @else
                <tr>
                    <td>物流公司：</td>
                    <td>{{ $order->ship_data['express_company'] }}</td>
                    <td>物流单号：</td>
                    <td>{{ $order->ship_data['express_no'] }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>