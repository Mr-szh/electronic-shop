@extends('layouts.app')

@section('title', $topic->title)
@section('description', $topic->excerpt)

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs author-info">
        <div class="card ">
            <div class="card-body" style="{{ isset($topic->admin_id) ? 'border:3px dotted orange' : '' }};">
                @if(isset($topic->user_id))
                <div class="text-center">
                    楼主：{{ $topic->user->name }}
                </div>
                @else
                <div class="text-center">
                    楼主：{{ $topic->admin['name'] }}
                </div>

                <div class="badge badge-danger" style="margin-left:40%;">管理员</div>
                @endif

                <hr>

                <div class="media">
                    <div align="center">
                        @if(isset($topic->user_id))
                        <a href="{{ route('users.show', $topic->user->id) }}">
                            <img class="thumbnail img-fluid" src="{{ $topic->user->avatar }}" width="300px" height="300px">
                        </a>
                        @else
                        <a href="#">
                            <img class="thumbnail img-fluid" src="{{ $topic->admin['avatar'] }}" width="300px" height="300px">
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 topic-content">
        <div class="card ">
            <div class="card-body" style="{{ isset($topic->admin_id) ? 'border:3px dotted orange' : '' }};">
                <h1 class="text-center mt-3 mb-3">
                    {{ $topic->title }}
                </h1>
                <div class="article-meta text-center text-secondary">
                    {{ $topic->created_at->diffForHumans() }}
                    ⋅
                    <i class="far fa-comment"></i>
                    {{ $topic->reply_count }}
                </div>

                <div class="topic-body mt-4 mb-4">
                    {!! $topic->body !!}
                </div>

                @guest
                @else
                @can('update', $topic)
                <div class="operate">
                    <hr>
                    <a href="{{ route('topics.edit', $topic->id) }}" class="btn btn-outline-secondary btn-sm" role="button">
                        <i class="far fa-edit"></i> 编辑
                    </a>
                    <form action="{{ route('topics.destroy', $topic->id) }}" method="post" style="display: inline-block;" onsubmit="return confirm('您确定要删除吗？');">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            <i class="far fa-trash-alt"></i> 删除
                        </button>
                    </form>
                </div>
                @endcan
                @endguest

            </div>
        </div>

        @if(isset($topic->user_id))
        <div class="card topic-reply mt-4">
            <div class="card-body">
                <!-- 视条件加载子模板 -->
                @includeWhen(Auth::check(), 'topics._reply_box', ['topic' => $topic])
                @include('topics._reply_list', ['replies'=>$topic->replies()->with('user','topic')->recent()->paginate(5)])
            </div>
        </div>
        @endif

    </div>
</div>
@endsection