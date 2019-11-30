<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Models\ProductSku;

class OrderRequest extends Request
{
    public function rules()
    {
        return [
            'address_id' => [
                'required',
                Rule::exists('user_addresses', 'id')->where('user_id', $this->user()->id),
            ],
            'items' => ['required', 'array'],
            'items.*.sku_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!$sku = ProductSku::find($value)) {
                        return $fail('该商品不存在');
                    }

                    if (!$sku->product->on_sale) {
                        return $fail('该商品未上架');
                    }

                    if ($sku->stock === 0) {
                        return $fail('该商品已售完');
                    }
                    
                    // 获取当前索引 items.0.sku_id
                    preg_match('/items\.(\d+)\.sku_id/', $attribute, $m);
                    $index = $m[1];
                    $amount = $this->input('items')[$index]['amount'];
                    
                    if ($amount > 0 && $amount > $sku->stock) {
                        return $fail('该商品库存不足');
                    }
                },
            ],
            'items.*.amount' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'address_id.required' => '收货地址不能为空',
            'items.required' => '未选择购买的商品',
            'items.*.amount.required' => '商品数量有误',
        ];
    }
}