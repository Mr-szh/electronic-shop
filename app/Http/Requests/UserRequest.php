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
            'name' => 'required|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '用户名',
        ];
    }
}
