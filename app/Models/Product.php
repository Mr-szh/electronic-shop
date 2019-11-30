<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Product extends Model
{
    const TYPE_NORMAL = 'normal';
    const TYPE_CROWDFUNDING = 'crowdfunding';

    public static $typeMap = [
        self::TYPE_NORMAL => '普通商品',
        self::TYPE_CROWDFUNDING => '众筹商品',
    ];

    protected $fillable = [
        'type', 'title', 'long_title', 'description', 'image', 'images', 'on_sale',
        'rating', 'sold_count', 'review_count', 'price'
    ];

    protected $casts = [
        'on_sale' => 'boolean',
    ];

    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function crowdfunding()
    {
        return $this->hasOne(CrowdfundingProduct::class);
    }

    // 访问器
    public function getImageAttribute($value)
    {
        return explode(',', $value);
    }

    public function setImageAttribute($value)
    {
        $this->attributes['image'] = implode(',', $value);
    }

    public function getImagesAttribute($value)
    {
        return explode(',', $value);
    }

    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = implode(',', $value);
    }

    public function properties()
    {
        return $this->hasMany(ProductProperty::class);
    }

    public function getGroupedPropertiesAttribute()
    {
        return $this->properties
            ->groupBy('name')
            ->map(function ($properties) {
                // 使用 map 方法将属性集合变为属性值集合
                return $properties->pluck('value')->all();
        });
    }

    public function toESArray()
    {
        $arr = Arr::only($this->toArray(), [
            'id',
            'type',
            'title',
            'category_id',
            'long_title',
            'on_sale',
            'rating',
            'sold_count',
            'review_count',
            'price',
        ]);

        // 有类目则 category 字段为类目名数组，否则为空字符串
        $arr['category'] = $this->category ? explode(' - ', $this->category->full_name) : '';
        $arr['category_path'] = $this->category ? $this->category->path : '';
        // strip_tags 函数可将 html 标签去除
        $arr['description'] = strip_tags($this->description);
        // 只取出需要的 SKU 字段
        $arr['skus'] = $this->skus->map(function (ProductSku $sku) {
            return Arr::only($sku->toArray(), ['title', 'description', 'price']);
        });
        // 只取出需要的商品属性字段
        $arr['properties'] = $this->properties->map(function (ProductProperty $property) {
            // return Arr::only($property->toArray(), ['name', 'value']);
            // 增加一个 search_value 字段，用符号 : 将属性名和属性值拼接起来
            return array_merge(array_only($property->toArray(), ['name', 'value']), [
                'search_value' => $property->name.':'.$property->value,
            ]);
        });

        return $arr;
    }

    // 根据ID查询对应的商品并保持 ID 的次序
    public function scopeByIds($query, $ids)
    {
        return $query->whereIn('id', $ids)->orderByRaw(sprintf("FIND_IN_SET(id, '%s')", join(',', $ids)));
    }
}
