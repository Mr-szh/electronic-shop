<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PasswordRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $this->newValidator();

        return [
            'oldPassword' => 'required|check_pwd',
            'password' => 'required|min:8|confirmed',
            'password_confirmation'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'oldPassword.required' => '原始密码不能为空',
            'password.required' => '新密码不能为空',
            'password_confirmation.required' => '确认密码不能为空',
            'password.min' => '新密码不能少于8个字符',
            'password.confirmed' => '两次密码不一致', 
            'oldPassword.check_pwd' => '原始密码输入有误',
        ];
    }

    public function newValidator()
    {
        // 定义验证类，用来验证旧密码是否正确
        Validator::extend('check_pwd', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, Auth::user()->password);
        });
    }
}
