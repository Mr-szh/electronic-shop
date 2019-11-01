@extends('layouts.app')
@section('title', '定制主机')

@section('content')
<div class="row mb-5">
    <div class="col-lg-12 col-md-12 topic-list">
        <span class="custom-title">请选择组件</span>
        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="margin-top:10px;">
            <div class="btn-group" role="group">

                @foreach ($categories as $category)
                @if($category->parent_id == '1')
                <button type="button" class="btn btn-default category-choose">{{ $category->name }}</button>
                @endif
                @endforeach

                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        更多
                        <span class="caret"></span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-right category-more" aria-labelledby="dropdownMenu1">
                        @foreach ($categories as $category)
                        @if($category->parent_id == '9')
                        <li class="dropdown-header"><a href="#">{{ $category->name }}</a></li>
                        <hr>
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-lg-6 col-md-6 topic-list custom-left">
        <div class="panel panel-primary">
            <div class="panel-heading left-title">
                <span class="panel-title">装机配置单</span>
            </div>
            <div class="panel panel-body">
                <ul class="list-group">
                    @foreach ($categories as $category)
                    @if(isset($category->parent_id))
                    <li class="list-group-item">
                        <h3>
                            {{ $category->name }}
                            @if($category->parent_id == '1')
                            <i class="important">*</i>
                            @endif
                        </h3>
                        <div class="col-auto float-left choose-product">请选择商品</div>
                        <div class="left-operation">
                            <span class="create">添加</span>
                            <span class="delete">x</span>
                        </div>
                    </li>
                    @endif
                    @endforeach
                </ul>
                <ul class="list-group">
                    <li class="list-group-item">
                        <span class="left-count">共计0项</span>
                        <span class="right-total">
                            合计
                            <span>￥0</span>
                        </span>
                    </li>
                    <li class="list-group-item">
                        <p>
                            <b>备注: </b>
                        </p>
                        <textarea class="form-control" rows="5"></textarea>
                        <div class="buttons">
                            <button class="btn btn-default custom">定制配置单</button>
                            <button class="btn btn-default button-style">预览</button>
                            <button class="btn btn-default button-style">清空</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 topic-list custom-right">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <span class="panel-title float-left">
                    请选择
                    <font>CPU*</font>
                </span>
                <div class="col-auto">
                    <input type="text" name="search" placeholder="请输入商品信息" autocomplete="off">
                    <button class="btn btn-primary btn-sm">搜索</button>
                </div>
            </div>
            <div class="panel panel-body">
                <div style="weight:680px;height:223px;background-color:blue;"></div>
                <div class="list-box" style="wieght:680px;height:1596px;background-color:chartreuse;"></div>
            </div>
        </div>
    </div>
</div>
@endsection