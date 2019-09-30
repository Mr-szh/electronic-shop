<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;
use App\Models\User;

class ReportsController extends Controller
{
    use HasResourceActions;

    public function index(Content $content)
    {
        // 获取每天新注册量
        $users = User::query()->where('created_at', '>', Carbon::today())->count();
        $orders = Order::query()->where('created_at', '>', Carbon::today())
            ->where('closed', false)
            ->get();

        // 获取每天销量
        $sales = 0;
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $sales += $item->amount;
            }
        }
        
        return $content
            ->header('数据报表')
            ->body(view('admin.reports.index', ['users' => $users, 'sales' => $sales]));
    }
} 