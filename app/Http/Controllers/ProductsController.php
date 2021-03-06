<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductsRequest;
use App\Exceptions\InvalidRequestException;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\ConfigItem;
use App\Services\CategoryService;
use Illuminate\Pagination\LengthAwarePaginator;
use App\SearchBuilders\ProductSearchBuilder;
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        // 创建一个查询构造器
        // $builder = Product::query()->where('on_sale', true);

        $page = $request->input('page', 1);
        $perPage = 12;

        // 新建查询构造器对象，设置只搜索上架商品，设置分页
        $builder = (new ProductSearchBuilder())->onSale()->paginate($perPage, $page);

        // 构建查询
        // $params = [
        //     'index' => 'products',
        //     'type' => '_doc',
        //     'body'  => [
        //         'from' => ($page - 1) * $perPage,
        //         'size' => $perPage,
        //         'query' => [
        //             'bool' => [
        //                 'filter' => [
        //                     ['term' => ['on_sale' => true]],
        //                 ],
        //             ],
        //         ],
        //     ],
        // ];

        if ($request->input('category_id') && $category = Category::find($request->input('category_id'))) {
            // if ($category->is_directory) {
            //     // 筛选出该父类目下所有子类目的商品
            //     // $builder->whereHas('category', function ($query) use ($category) {
            //     //     $query->where('path', 'like', $category->path.$category->id.'-%');
            //     // });
            //     $params['body']['query']['bool']['filter'][] = [
            //         // prefix 类似 MySQL 中的 like
            //         'prefix' => ['category_path' => $category->path.$category->id.'-'],
            //     ];
            // } else {
            //     // $builder->where('category_id', $category->id);
            //     // term 为精确匹配
            //     $params['body']['query']['bool']['filter'][] = ['term' => ['category_id' => $category->id]];
            // }

            // 调用查询构造器的类目筛选
            $builder->category($category);
        }

        if ($search = $request->input('search', '')) {
            // $like = '%'.$search.'%';
            // 模糊搜索商品标题、商品详情、SKU 标题、SKU描述
            // $builder->where(function ($query) use ($like) {
            //     $query->where('title', 'like', $like)
            //         ->orWhere('description', 'like', $like)
            //         ->orWhereHas('skus', function ($query) use ($like) {
            //             $query->where('title', 'like', $like)
            //                 ->orWhere('description', 'like', $like);
            //         });
            // });

            // 将搜索词根据空格拆分成数组，并过滤掉空项
            $keywords = array_filter(explode(' ', $search));

            // $params['body']['query']['bool']['must'] = [];

            // 遍历搜索词数组，分别添加到 must 查询中
            // foreach ($keywords as $keyword) {
            //     $params['body']['query']['bool']['must'][] = [
            //         'multi_match' => [
            //             'query' => $keyword,
            //             'fields' => [
            //                 'title^3',
            //                 'long_title^2',
            //                 'category^2',
            //                 'description',
            //                 'skus_title',
            //                 'skus_description',
            //                 'properties_value',
            //             ],
            //         ],
            //     ];
            // }

            $builder->keywords($keywords);
        }

        // 只有当用户有输入搜索词或者使用了类目筛选的时候才会做聚合
        if ($search || isset($category)) {
            // $params['body']['aggs'] = [
            //     'properties' => [
            //         'nested' => [
            //             'path' => 'properties',
            //         ],
            //         'aggs' => [
            //             'properties' => [
            //                 'terms' => [
            //                     'field' => 'properties.name',
            //                 ],
            //                 'aggs'  => [
            //                     'value' => [
            //                         'terms' => [
            //                             'field' => 'properties.value',
            //                         ],
            //                     ],
            //                 ],
            //             ],
            //         ],
            //     ],
            // ];

            $builder->aggregateProperties();
        }

        $propertyFilters = [];

        if ($filterString = $request->input('filters')) {
            $filterArray = explode('|', $filterString);
            foreach ($filterArray as $filter) {
                list($name, $value) = explode(':', $filter);
                $propertyFilters[$name] = $value;

                // $params['body']['query']['bool']['filter'][] = [
                //     'nested' => [
                //         'path' => 'properties',
                //         'query' => [
                //             // ['term' => ['properties.name' => $name]],
                //             // ['term' => ['properties.value' => $value]],
                //             ['term' => ['properties.search_value' => $filter]], 
                //         ],
                //     ],
                // ];

                // 调用查询构造器的属性筛选
                $builder->propertyFilter($name, $value);
            }
        }

        if ($order = $request->input('order', '')) {
            // 是否是以 _asc 或者 _desc 结尾
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                // 如果字符串的开头是这 3 个字符串之一，说明合法
                if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    // 根据传入的排序值来构造排序参数
                    // $builder->orderBy($m[1], $m[2]);
                    // $params['body']['sort'] = [[$m[1] => $m[2]]];

                    // 调用查询构造器的排序
                    $builder->orderBy($m[1], $m[2]);
                }
            }
        }

        // $result = app('es')->search($params);

        // 通过 getParams() 方法取回构造好的查询参数
        $result = app('es')->search($builder->getParams());

        // 通过 collect 函数将返回结果转为集合，并通过集合的 pluck 方法取到返回的商品 ID 数组
        $productIds = collect($result['hits']['hits'])->pluck('_id')->all();
        // $products = Product::query()
        //     ->whereIn('id', $productIds)
        //     // orderByRaw 可以让我们用原生的 SQL 来给查询结果排序
        //     ->orderByRaw(sprintf("FIND_IN_SET(id, '%s')", join(',', $productIds)))
        //     ->get();

        $products = Product::query()->byIds($productIds)->get();

        // 返回一个 LengthAwarePaginator 对象
        $pager = new LengthAwarePaginator($products, $result['hits']['total'], $perPage, $page, [
            'path' => route('products.index', false),
        ]);

        $properties = [];

        if (isset($result['aggregations'])) {
            $properties = collect($result['aggregations']['properties']['properties']['buckets'])
                ->map(function ($bucket) {
                    return [
                        'key' => $bucket['key'],
                        'values' => collect($bucket['value']['buckets'])->pluck('key')->all(),
                    ];
                })
                ->filter(function ($property) use ($propertyFilters) {
                    // 过滤掉只剩下一个值 或者 已经在筛选条件里的属性
                    return count($property['values']) > 1 && !isset($propertyFilters[$property['key']]);
                });
        }

        return view('products.index', [
            'products' => $pager,
            'filters'  => [
                'search' => $search,
                'order'  => $order,
            ],
            'category' => $category ?? null,
            'properties' => $properties,
            'propertyFilters' => $propertyFilters,
        ]);
    }

    public function show(Product $product, Request $request, ProductService $service)
    {
        // if (!$product->on_sale) {
        //     throw new InvalidRequestException('商品未上架');
        // }

        $favored = false;

        // 用户未登录时返回的是 null，已登录时返回的是对应的用户对象
        if ($user = $request->user()) {
            // 从当前用户已收藏的商品中搜索 id 为当前商品 id 的商品
            // boolval() 函数用于把值转为布尔值
            $favored = boolval($user->favoriteProducts()->find($product->id));
        }

        $reviews = OrderItem::query()
            ->with(['order.user', 'productSku'])
            ->where('product_id', $product->id)
            ->whereNotNull('reviewed_at')
            ->orderBy('reviewed_at', 'desc')
            ->limit(10)
            ->get();

        // 推荐相似商品
        $similarProductIds = $service->getSimilarProductIds($product, 4);

        // 根据 Elasticsearch 搜索出来的商品 ID 从数据库中读取商品数据
        // $similarProducts = Product::query()
        //     ->whereIn('id', $similarProductIds)
        //     ->orderByRaw(sprintf("FIND_IN_SET(id, '%s')", join(',', $similarProductIds)))
        //     ->get();

        $similarProducts = Product::query()->byIds($similarProductIds)->get();

        return view('products.show', [
            'product' => $product,
            'favored' => $favored,
            // 'description' => $description,
            'reviews' => $reviews,
            'similar' => $similarProducts,
        ]);
    }

    public function favor(Product $product, Request $request)
    {
        $user = $request->user();

        if ($user->favoriteProducts()->find($product->id)) {
            return [];
        }

        $user->favoriteProducts()->attach($product);

        return [];
    }

    public function disfavor(Product $product, Request $request)
    {
        $user = $request->user();
        // detach() 方法用于取消多对多的关联
        $user->favoriteProducts()->detach($product);

        return [];
    }

    public function favorites(Request $request)
    {
        $products = $request->user()->favoriteProducts()->paginate(12);

        return view('products.favorites', ['products' => $products]);
    }

    public function disfavors(Request $request, Product $product)
    {
        $user = $request->user();
        $product_id = $request->input('product_id');
        $product = Product::query()->where('product_id', $product_id)->get();

        $user->favoriteProducts()->detach($product);

        return [];
    }

    public function custom(Request $request)
    {
        $categories = Category::query()->get();
        $configItems = ConfigItem::query()->where('user_id', Auth::id())->get();
        $addresses = $request->user()->addresses()->orderBy('last_used_at', 'desc')->get();
        
        $page = $request->input('page', 1);
        $perPage = 12;

        $builder = (new ProductSearchBuilder())->onSale()->paginate($perPage, $page);

        if ($request->input('category_id') && $category = Category::find($request->input('category_id'))) {
            $builder->category($category);
        }

        if ($search = $request->input('search', '')) {
            $keywords = array_filter(explode(' ', $search));

            $builder->keywords($keywords);
        }

        if ($search || isset($category)) {
            $builder->aggregateProperties();
        }

        $propertyFilters = [];
        if ($filterString = $request->input('filters')) {
            $filterArray = explode('|', $filterString);
            
            foreach ($filterArray as $filter) {
                list($name, $value) = explode(':', $filter);
                $propertyFilters[$name] = $value;

                $builder->propertyFilter($name, $value);
            }
        }

        if ($order = $request->input('order', '')) {
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    $builder->orderBy($m[1], $m[2]);
                }
            }
        }

        $result = app('es')->search($builder->getParams());

        $productIds = collect($result['hits']['hits'])->pluck('_id')->all();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->where('type', 'normal')
            ->orderByRaw(sprintf("FIND_IN_SET(id, '%s')", join(',', $productIds)))
            ->get();

        $pager = new LengthAwarePaginator($products, $result['hits']['total'], $perPage, $page, [
            'path' => route('custom.index', false),
        ]);

        $properties = [];

        if (isset($result['aggregations'])) {
            $properties = collect($result['aggregations']['properties']['properties']['buckets'])
                ->map(function ($bucket) {
                    return [
                        'key' => $bucket['key'],
                        'values' => collect($bucket['value']['buckets'])->pluck('key')->all(),
                    ];
                })->filter(function ($property) use ($propertyFilters) {
                    return count($property['values']) > 1 && !isset($propertyFilters[$property['key']]) ;
                });
        }

        return view('custom.index', [
            'categories' => $categories,
            'products' => $pager,
            'filters'  => [
                'search' => $search,
                'order'  => $order,
            ],
            'category' => $category ?? null,
            'properties' => $properties,
            'propertyFilters' => $propertyFilters,
            'configItems' => $configItems,
            'addresses' => $addresses,
        ]);
    }
}
