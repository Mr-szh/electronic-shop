<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\UserAddress;

class UserAddressPolicy
{
    use HandlesAuthorization;

    public function own(User $user, UserAddress $address)
    {
        // 当 own() 方法返回 true 时代表当前登录用户可以修改对应的地址
        return $address->user_id == $user->id;
    }
}
