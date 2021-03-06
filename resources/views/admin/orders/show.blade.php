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
        <div style="border:1px solid black;">
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
                </tbody>
            </table>
        </div>
        <div style="border:1px solid black;">
            <table class="table table-bordered table-condensed">
                <tbody>
                    <tr>
                        <td style="border:1px solid black;font-size:16px;">商品列表</td>
                        <td style="border:1px solid black;font-size:16px;">商品名称</td>
                        <td style="border:1px solid black;font-size:16px;">单价</td>
                        <td style="border:1px solid black;font-size:16px;">数量</td>
                    </tr>
                    @foreach($order->items as $item)
                    <tr>
                        <td style="border:1px solid black;"><img src="{{ URL::asset('/upload/'.$item->product->image[0]) }}" style="max-height:28%;max-width:28%;"></td>
                        <td style="border:1px solid black;">
                            <div style="font-size:16px;font-weight:bolder;">{{ $item->product->title }}</div>
                            <p style="margin-bottom:15px;"></p>
                            <div style="font-size:15px;">{{ $item->productSku->title }}</div>
                        </td>
                        <td style="border:1px solid black;font-size:16px;color:red;font-weight:bolder;">￥{{ $item->price }}</td>
                        <td style="border:1px solid black;font-size:16px;">{{ $item->amount }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="border:1px solid black;">
            <table class="table table-bordered table-condensed">
                <tbody>
                    <tr>
                        <td>订单金额：</td>
                        <!-- <td colspan="3" style="color:red;font-weight:bolder;font-size:20px;">￥{{ $order->total_amount }}</td> -->
                        <td style="color:red;font-weight:bolder;font-size:20px;">￥{{ $order->total_amount }}</td>
                        <td>发货状态：</td>
                        <td>{{ \App\Models\Order::$shipStatusMap[$order->ship_status] }}</td>
                    </tr>
                    @if($order->ship_status === \App\Models\Order::SHIP_STATUS_PENDING)
                    @if($order->refund_status !== \App\Models\Order::REFUND_STATUS_SUCCESS &&
                        ($order->type !== \App\Models\Order::TYPE_CROWDFUNDING || 
                            $order->items[0]->product->crowdfunding->status === \App\Models\CrowdfundingProduct::STATUS_SUCCESS))
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
                        @endif
                    @else
                    <tr>
                        <td>物流公司：</td>
                        <td>{{ $order->ship_data['express_company'] }}</td>
                        <td>物流单号：</td>
                        <td>{{ $order->ship_data['express_no'] }}</td>
                    </tr>
                    @endif

                    @if($order->refund_status !== \App\Models\Order::REFUND_STATUS_PENDING)
                    <tr>
                        <td>退款状态：</td>
                        <td colspan="2">{{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}， 理由：{{ $order->extra['refund_reason'] }}</td>
                        <td>
                            @if($order->refund_status === \App\Models\Order::REFUND_STATUS_APPLIED)
                            <button class="btn btn-sm btn-success" id="btn-refund-agree">同意</button>
                            <button class="btn btn-sm btn-danger" id="btn-refund-disagree">不同意</button>
                            @endif
                        </td>
                    </tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#btn-refund-disagree').click(function() {
            swal({
                title: '输入拒绝退款理由',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: "确认",
                cancelButtonText: "取消",
                showLoaderOnConfirm: true,
                preConfirm: function(inputValue) {
                    if (!inputValue) {
                        swal('理由不能为空', '', 'error')
                        return false;
                    }

                    return $.ajax({
                        url: '{{ route('admin.orders.handle_refund', [$order->id]) }}',
                        type: 'POST',
                        data: JSON.stringify({
                            agree: false,
                            reason: inputValue,
                            _token: LA.token,
                        }),
                        contentType: 'application/json',
                    });
                },
                allowOutsideClick: false
            }).then(function (ret) {
                if (ret.dismiss === 'cancel') {
                    return;
                }
                swal({
                    title: '操作成功',
                    type: 'success'
                }).then(function() {
                    location.reload();
                });
            });
        });

        $('#btn-refund-agree').click(function() {
            swal({
                title: '确认要将款项退还给用户？',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: "确认",
                cancelButtonText: "取消",
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return $.ajax({
                        url: '{{ route('admin.orders.handle_refund', [$order->id]) }}',
                        type: 'POST',
                        data: JSON.stringify({
                            agree: true,
                            _token: LA.token,
                        }),
                        contentType: 'application/json',
                    });
                },
                allowOutsideClick: false
            }).then(function (ret) {
                if (ret.dismiss === 'cancel') {
                    return;
                }
                swal({
                    title: '操作成功',
                    type: 'success'
                }).then(function() {
                    location.reload();
                });
            });
        });
    });
</script>