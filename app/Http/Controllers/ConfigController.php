<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddConfigRequest;
use App\Models\ConfigItem;
use App\Models\ProductSku;
use App\Services\ConfigService;

class ConfigController extends Controller
{
    protected $configService;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }

    public function add(AddConfigRequest $request)
    {
        $this->configService->add($request->input('sku_id'), $request->input('category_id'), $request->input('amount'));

        return [];
    }

    public function remove(ProductSku $sku, Request $request) {
        $this->configService->remove($sku->id);

        return [];
    }

    public function removeAll(Request $request) {
        $this->configService->removeAll();

        return [];
    }
}
