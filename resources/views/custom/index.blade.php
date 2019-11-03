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
                <button type="button" class="btn btn-default category-choose category">{{ $category->name }}</button>
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
                        <li class="dropdown-header"><a href="#" class="category">{{ $category->name }}</a></li>
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
            @if(isset($category_name))
            <div class="panel-heading">
                请选择
                <font id="change">{{ $category_name }}</font>
            </div>
            @endif
            <div class="panel-choose">
                <form action="{{ route('custom.index') }}" class="search-form">
                    <div class="form-row">
                        <div class="col-md-9">
                            <div class="form-row">
                                <div class="col-auto margin-style"><input type="text" class="form-control form-control-sm" name="search" placeholder="搜索"></div>
                                <div class="col-auto"><button class="btn btn-primary btn-sm">搜索</button></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="order" class="form-control form-control-sm float-right margin-style">
                                <option value="">排序方式</option>
                                <option value="price_asc">价格从低到高</option>
                                <option value="price_desc">价格从高到低</option>
                                <option value="sold_count_desc">销量从高到低</option>
                                <option value="sold_count_asc">销量从低到高</option>
                                <option value="rating_desc">评价从高到低</option>
                                <option value="rating_asc">评价从低到高</option>
                            </select>
                        </div>
                        <input type="hidden" name="category" value="">
                    </div>
                </form>
            </div>
            <div class="panel panel-body">
                @include('custom._product')
            </div>
        </div>
    </div>
</div>
@endsection
@section('scriptsAfterJs')
<script>
    var filters = {!!json_encode($filters) !!};

    $(document).ready(function() {
        $('.search-form input[name=search]').val(filters.search);
        $('.search-form select[name=order]').val(filters.order);
        $("input[name=category]").val(filters.category);
        $("#change").text(filters.category);

        $('.search-form select[name=order]').on('change', function() {
            $('.search-form').submit();
        });

        $(".category").click(function() {
            $("input[name=category]").val(this.innerHTML);
            $('.search-form').submit();
        });
    })
</script>
@endsection