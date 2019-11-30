<?php

namespace App\Models;

use App\Models\Traits\ActiveUserHelper;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, ActiveUserHelper;

    public function topicNotify($instance)
    {
        if ($this->id == Auth::id()) {
            return;
        }

        $this->increment('notification_count');

        $this->notify($instance);
    }

    protected $fillable = [
        'name', 'email', 'password', 'avatar',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     * 应该转换为本机类型的属性
     * 
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'user_favorite_products')
            ->withTimestamps()
            ->orderBy('user_favorite_products.created_at', 'desc');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function configItems()
    {
        return $this->hasMany(ConfigItem::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function markAsRead()
    {
        $this->notification_count = 0;

        $this->save();
        
        $this->unreadNotifications->markAsRead();
    }
}
