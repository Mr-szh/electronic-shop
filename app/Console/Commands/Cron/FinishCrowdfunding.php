<?php
namespace App\Console\Commands\Cron;

use App\Jobs\RefundCrowdfundingOrders;
use App\Models\CrowdfundingProduct;
use App\Services\OrderService;
use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class FinishCrowdfunding extends Command
{
    // 可供调用命令
    protected $signature = 'cron:finish-crowdfunding';

    protected $description = '结束众筹';

    public function handle()
    {
        CrowdfundingProduct::query()
            ->with(['product'])
            ->where('end_at', '<=', Carbon::now())
            ->where('status', CrowdfundingProduct::STATUS_FUNDING)
            ->get()
            ->each(function (CrowdfundingProduct $crowdfunding) {
                if ($crowdfunding->target_amount > $crowdfunding->total_amount) {
                    $this->crowdfundingFailed($crowdfunding);
                } else {
                    $this->crowdfundingSucceed($crowdfunding);
                }
            });
    }

    protected function crowdfundingSucceed(CrowdfundingProduct $crowdfunding)
    {
        $crowdfunding->update([
            'status' => CrowdfundingProduct::STATUS_SUCCESS,
        ]);
    }

    protected function crowdfundingFailed(CrowdfundingProduct $crowdfunding)
    {
        $crowdfunding->update([
            'status' => CrowdfundingProduct::STATUS_FAIL,
        ]);
        
        dispatch(new RefundCrowdfundingOrders($crowdfunding));
    }
}