<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'title', 'description', 'image', 'images', 'on_sale',
        'rating', 'sold_count', 'review_count', 'price'
    ];

    protected $casts = [
        // on_sale 是一个布尔类型的字段
        'on_sale' => 'boolean',
    ];

    // 与商品SKU关联
    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 修复页面无法显示商品封面图片的问题
    // public function getImageUrlAttribute()
    // {
    //     // 如果 image 字段本身就已经是完整的 url 就直接返回
    //     if (Str::startsWith($this->attributes['image'], ['http://', 'https://'])) {
    //         return $this->attributes['image'];
    //     }
    //     return \Storage::disk('public')->url($this->attributes['image']);
    // }

    // public function getImagesUrlAttribute()
    // {
    //     // 如果 image 字段本身就已经是完整的 url 就直接返回
    //     if (Str::startsWith($this->attributes['images'], ['http://', 'https://'])) {
    //         return $this->attributes['images'];
    //     }
    //     return \Storage::disk('public')->url($this->attributes['images']);
    // }

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
}
