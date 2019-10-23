<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;

class Admin extends Administrator
{
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}