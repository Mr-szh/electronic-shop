<?php

// 此方法会将当前请求的路由名称转换为 CSS 类名称，作用是允许我们针对某个页面做页面样式定制
function route_class()
{
    // currentRouteName 方法可以返回处理当前请求用到的路由的名字
    return str_replace('.', '-', Route::currentRouteName());
}

// 定制此观察器，在 Topic 模型保存时触发的 saving 事件中，对 excerpt 字段进行赋值
function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return Str::limit($excerpt, $length);
}
