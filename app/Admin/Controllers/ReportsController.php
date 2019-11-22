<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Carbon\Carbon;
use App\Models\User;
use Encore\Admin\Widgets\Box;

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

        $order_count = Order::query()->where('closed', '0')->where('refund_status', 'pending')->where('ship_status', 'pending')->count();
        $categorys = Category::query()->where('level', '1')->get();
        $products = Product::query()->get();

        $category_key = collect();
        $category_value = collect();

        $sount_count = 0;

        foreach ($categorys as $category) {
            foreach ($products as $product) {
                if ($category->id == $product->category_id) {
                    $sount_count += $product->sold_count;
                }
            }
            $category_key[] = $category->name;
            $category_value[] = $sount_count;
            $sount_count = 0;
        }

        $seven_sales = collect();

        for ($i = 6; $i >= 0; $i--) {
            $seven_sales[] = Order::query()->where('created_at', '<', Carbon::today()->subDays($i))->count(); 
        }      
        
        $seven_users = collect();

        for ($i = 6; $i >= 0; $i--) {
            $seven_users[] = User::query()->where('created_at', '<', Carbon::today()->subDays($i))->count(); 
        }    

        return $content
            ->header('数据报表')
            ->body(view('admin.reports.index', [
                'users' => $users, 
                'sales' => $sales, 
                'category_key'=> $category_key, 
                'category_value' => $category_value,
                'order_count' => $order_count,
                'seven_sales' => $seven_sales,
                'seven_users' => $seven_users,
            ]
        ));
        // ->body(new Box('Bar chart', view('admin.chartjs')));
        // ->body(new Box('Bar chart', view('admin.chartjs')));
    }
}
