<?php

namespace App\Services;

use Auth;
use App\Models\ConfigItem;

class ConfigService
{
    public function add($skuId, $categoryId, $amount)
    {
        $user = Auth::user();

        if ($user->configItems()->where('category_id', $categoryId)->first()) {
            $user->configItems()->where('category_id', $categoryId)->delete();
        }

        if ($config = $user->configItems()->where('product_sku_id', $skuId)->first()) {
            $config->update([
                'amount' => $config->amount + $amount,
            ]);
        } else {
            $config = new ConfigItem(['amount' => $amount]);
            
            $config->user()->associate($user);
            $config->productSku()->associate($skuId);
            $config->category()->associate($categoryId);
            
            $config->save();
        }

        return $config;
    }

    public function remove($skuIds)
    {
        if (!is_array($skuIds)) {
            $skuIds = [$skuIds];
        }

        Auth::user()->configItems()->wherein('product_sku_id', $skuIds)->delete();
    }

    public function removeAll()
    {
        Auth::user()->configItems()->delete();
    }
}