<?php

namespace App\Http\Requests;

class AvatarRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // 图片比例验证规则 dimensions
        return [
            'avatar' => 'mimes:jpg,jpeg,png,gif|dimensions:min_width=200,min_height=200',
        ];
    }

    public function messages()
    {
        return [
            'avatar.mimes' =>'图片格式必须是 jpg, jpeg, png, gif',
            'avatar.dimensions' => '图片清晰度不够(宽和高需要 200px 以上)',
        ];
    }
}
