<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigItem extends Model
{
    protected $fillable = ['amount'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class);
    }
}
