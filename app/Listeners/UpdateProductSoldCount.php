<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\OrderItem;

class UpdateProductSoldCount implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(OrderPaid $event)
    {
        $order = $event->getOrder();
        // 预加载商品数据
        $order->load('items.product');
        
        foreach ($order->items as $item) {
            $product = $item->product;
            
            $soldCount = OrderItem::query()
                ->where('product_id', $product->id)
                ->whereHas('order', function ($query) {
                    $query->whereNotNull('paid_at');
                })->sum('amount');
            
            $product->update([
                'sold_count' => $soldCount,
            ]);
        }
    }
}
