<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\ProductSku;
use App\Exceptions\InvalidRequestException;
use App\Jobs\CloseOrder;
use Carbon\Carbon;
use App\Models\CouponCode;
use App\Exceptions\CouponCodeUnavailableException;
use App\Exceptions\InternalException;
use App\Exceptions\SystemException;

class OrderService
{
    public function store(User $user, UserAddress $address, $remark, $items, CouponCode $coupon = null, $custom)
    {
        if ($coupon) {
            $coupon->checkAvailable($user);
        }

        $order = \DB::transaction(function () use ($user, $address, $remark, $items, $coupon, $custom) {
            $address->update(['last_used_at' => Carbon::now()]);

            $order = new Order([
                'address' => [
                    'address' => $address->full_address,
                    'zip' => $address->zip,
                    'contact_name' => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark' => $remark,
                'total_amount' => 0,
                'type' => Order::TYPE_NORMAL,
            ]);

            $order->user()->associate($user);
            $order->save();

            $totalAmount = 0;

            foreach ($items as $data) {
                $sku  = ProductSku::find($data['sku_id']);
                // 创建一个 OrderItem 并直接与当前订单关联
                $item = $order->items()->make([
                    'amount' => $data['amount'],
                    'price'  => $sku->price,
                ]);

                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku);
                $item->save();

                $totalAmount += $sku->price * $data['amount'];
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    throw new InvalidRequestException('该商品库存不足');
                }
            }

            if ($coupon) {
                // 检验优惠券
                $coupon->checkAvailable($user, $totalAmount);
                // 修改优惠后的金额
                $totalAmount = $coupon->getAdjustedPrice($totalAmount);
                $order->couponCode()->associate($coupon);

                if ($coupon->changeUsed() <= 0) {
                    throw new CouponCodeUnavailableException('该优惠券已被兑完');
                }
            }

            $order->update(['total_amount' => $totalAmount]);

            // 将下单的商品从购物车中移除
            $skuIds = collect($items)->pluck('sku_id')->all();

            if ($custom == 0) {
                app(CartService::class)->remove($skuIds);
            } elseif ($custom == 1) {
                app(ConfigService::class)->remove($skuIds);
            }
            
            return $order;
        });

        dispatch(new CloseOrder($order, config('app.order_ttl')));

        return $order;
    }

    public function crowdfunding(User $user, UserAddress $address, ProductSku $sku, $amount)
    {
        $order = \DB::transaction(function () use ($amount, $sku, $user, $address) {
            $address->update(['last_used_at' => Carbon::now()]);
            
            $order = new Order([
                'address' => [
                    'address' => $address->full_address,
                    'zip' => $address->zip,
                    'contact_name' => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark' => '',
                'total_amount' => $sku->price * $amount,
                'type' => Order::TYPE_CROWDFUNDING,
            ]);

            $order->user()->associate($user);
            $order->save();

            $item = $order->items()->make([
                'amount' => $amount,
                'price' => $sku->price,
            ]);

            $item->product()->associate($sku->product_id);
            $item->productSku()->associate($sku);
            $item->save();

            if ($sku->decreaseStock($amount) <= 0) {
                throw new InvalidRequestException('该商品库存不足');
            }

            return $order;
        });

        // 众筹结束时间减去当前时间得到剩余秒数作为订单关闭时间
        $crowdfundingTtl = $sku->product->crowdfunding->end_at->getTimestamp() - time();

        dispatch(new CloseOrder($order, min(config('app.order_ttl'), $crowdfundingTtl)));

        return $order;
    }

    public function refundOrder(Order $order)
    {
        switch ($order->payment_method) {
            case 'wechat':
                break;
            case 'alipay':
                $refundNo = Order::getAvailableRefundNo();
                
                $ret = app('alipay')->refund([
                    'out_trade_no' => $order->no,
                    'refund_amount' => $order->total_amount,
                    'out_request_no' => $refundNo,
                ]);

                if ($ret->sub_code) {
                    $extra = $order->extra;
                    $extra['refund_failed_code'] = $ret->sub_code;
                    
                    $order->update([
                        'refund_no' => $refundNo,
                        'refund_status' => Order::REFUND_STATUS_FAILED,
                        'extra' => $extra,
                    ]);
                } else {
                    $order->update([
                        'refund_no' => $refundNo,
                        'refund_status' => Order::REFUND_STATUS_SUCCESS,
                    ]);
                }
                break;
            default:
                throw new SystemException('未知订单支付方式：' . $order->payment_method);
                break;
        }
    }
}