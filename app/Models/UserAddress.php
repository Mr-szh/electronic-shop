<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $appends = ['full_address'];
    
    protected $fillable = [
        'province',
        'city',
        'district',
        'address',
        'zip',
        'contact_name',
        'contact_phone',
        'last_used_at',
    ];
    // Carbon 对象，Carbon 是 Laravel 默认使用的时间日期处理类，继承自 PHP DateTime 类的 API 扩展
    protected $dates = ['last_used_at'];

    // 与 User 模型关联
    public function user()
    {
        // 关联关系是一对多，belongsTo：属于
        return $this->belongsTo(User::class);
    }

    // 创建一个访问器来获取完整的地址
    public function getFullAddressAttribute()
    {
        return "{$this->province}{$this->city}{$this->district}{$this->address}";
    }
}
