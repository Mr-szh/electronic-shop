<?php

// 此方法会将当前请求的路由名称转换为 CSS 类名称，作用是允许我们针对某个页面做页面样式定制
function route_class()
{
    // currentRouteName 方法可以返回处理当前请求用到的路由的名字
    return str_replace('.', '-', Route::currentRouteName());
}