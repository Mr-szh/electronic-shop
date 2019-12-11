<?php

namespace App\Http\Requests;

class UserAddressRequest extends Request
{
    // 获取校验规则来对用户提交的数据进行校验
    public function rules()
    {
        return [
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'address' => 'required',
            'zip' => 'required|digits:6',
            'contact_name' => 'required',
            'contact_phone' => 'required|regex:/^1[345789][0-9]{9}$/',
        ];
    }

    public function attributes()
    {
        return [
            'province' => '省',
            'city' => '城市',
            'district' => '地区',
            'address' => '详细地址',
            'zip' => '邮编',
            'contact_name' => '收货人',
            'contact_phone' => '联系电话',
        ];
    }
}