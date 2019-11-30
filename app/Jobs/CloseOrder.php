<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Order;

class CloseOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    
    public function __construct(Order $order, $delay)
    {
        $this->order = $order;

        // 设置延迟的时间，delay() 方法的参数代表多少秒之后执行
        $this->delay($delay);
    }

    public function handle()
    {
        if ($this->order->paid_at) {
            return;
        }

        \DB::transaction(function() {
            $this->order->update(['closed' => true]);

            foreach ($this->order->items as $item) {
                $item->productSku->addStock($item->amount);
            }

            // 关闭订单减少优惠券用量
            if ($this->order->couponCode) {
                $this->order->couponCode->changeUsed(false);
            }
        });
    }
}
