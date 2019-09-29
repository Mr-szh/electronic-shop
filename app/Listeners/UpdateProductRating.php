<?php

namespace App\Listeners;

use DB;
use App\Models\OrderItem;
use App\Events\OrderReviewed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

// 更新评论的事件
class UpdateProductRating implements ShouldQueue
{
    public function handle(OrderReviewed $event)
    {
        $items = $event->getOrder()->items()->with(['product'])->get();

        foreach ($items as $item) {
            // first() 方法接受一个数组作为参数
            // 把 DB::raw() 的参数原样拼接到 SQL 里
            // select `name`, `email` from xxx
            $result = OrderItem::query()
                ->where('order_id', $item->order_id)
                ->where('product_id', $item->product_id)
                ->whereHas('order', function ($query) {
                    $query->where('reviewed', 1)->whereNotNull('paid_at');
                })
                ->first([
                    DB::raw('count(*) as review_count'),
                    DB::raw('avg(rating) as rating')
                ]);

            $item->product->update([
                'rating' => $result->rating,
                'review_count' => $result->review_count,
            ]);
        }
    }
}
