<?php

namespace App\Services;

use App\Models\Product;
use App\SearchBuilders\ProductSearchBuilder;

class ProductService
{
    public function getSimilarProductIds(Product $product, $amount) {
        if (count($product->properties) === 0) {
            return [];
        }

        // 创建一个查询构造器，取上架的商品的前 4 个商品
        $builder = (new ProductSearchBuilder())->onSale()->paginate($amount, 1);
        foreach ($product->properties as $property) {
            // 添加到 should 条件中
            $builder->propertyFilter($property->name, $property->value, 'should');
        }

        // 设置最少匹配一半的属性
        $builder->minShouldMatch(ceil(count($product->properties) / 2));
        $params = $builder->getParams();
        // 同时将当前商品的 ID 排除
        $params['body']['query']['bool']['must_not'] = [['term' => ['_id' => $product->id]]];
        // 实现搜索
        $result = app('es')->search($params);

        return collect($result['hits']['hits'])->pluck('_id')->all();
    }
}