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
}