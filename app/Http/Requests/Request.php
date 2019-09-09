<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    // 授权
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * 获取应用于请求的验证规则
     * 
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}