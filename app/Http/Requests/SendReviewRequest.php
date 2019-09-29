<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendReviewRequest extends Request
{
    public function rules()
    {
        return [
            'reviews' => ['required', 'array'],
            'reviews.*.id' => [
                'required',
                // $this->route('order'): 获得当前路由对应的订单对象
                Rule::exists('order_items', 'id')->where('order_id', $this->route('order')->id)
            ],
            'reviews.*.rating' => ['required', 'integer', 'between:1,5'],
            'reviews.*.review' => ['required'],
        ];
    }

    public function attributes()
    {
        return [
            'reviews.*.rating' => '评分',
            'reviews.*.review' => '评价',
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }
}
