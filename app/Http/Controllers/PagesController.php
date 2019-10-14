<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class PagesController extends Controller
{
    public function root()
    {
        $products = Product::query()
            ->where('on_sale', true)
            ->orderBy('on_sale', 'desc')
            ->paginate(4);
        // dd($products);
        return view('pages.root', ['products' => $products]);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function mail()
    {
        return view('pages.mail');
    }
}
