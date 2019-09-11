<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductsRequest;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        // paginate() 分页取出数据
        $products = Product::query()->where('on_sale', true)->paginate(16);

        return view('products.index', ['products' => $products]);
    }
}
