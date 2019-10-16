@extends('layouts.app')

@section('title', '社区个人中心')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs user-info">
        <div class="card ">
            <img class="card-img-top" src="{{ URL::asset($user->avatar) }}" alt="{{ $user->name }}" style="border: 2px solid black;">
            <div class="card-body">
                <h5><strong>{{ $user->name }}</strong></h5>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                <hr>
                <h5><strong>注册于</strong></h5>
                <p>{{ $user->created_at->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="card ">
            <div class="card-body">
                <h1 class="mb-0" style="font-size:22px;">{{ $user->name }} <small>{{ $user->email }}</small></h1>
            </div>
        </div>
        <hr>

        {{-- 用户发布的内容 --}}
        <div class="card ">
            <div class="card-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active bg-transparent" href="#">Ta 的话题</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Ta 的回复</a></li>
                </ul>
                @include('users._topics', ['topics' => $user->topics()->recent()->paginate(5)])
            </div>
        </div>

    </div>
</div>
@stop