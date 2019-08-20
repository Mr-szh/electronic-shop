<?php

namespace App\Http\Requests;

class UserRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:12',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '用户名不能为空',
            'name.max' => '用户名长度不能超过12个字符',
        ];
    }
}
