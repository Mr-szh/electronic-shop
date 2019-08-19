@extends('layouts.app')
@section('title', '用户信息')

@section('content')

<!-- 输出后端报错开始 -->
@if (count($errors) > 0)
<div class="card-body">
    <div class="alert alert-danger">
        <h4>有错误发生：</h4>
        <ul>
            @foreach ($errors->all() as $error)
            <li><i class="glyphicon glyphicon-remove"></i> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="section-padding">
    <div class="container">
        <div class="member-area-from-wrap">
            <div class="row">

                <div class="col-lg-6">
                    <div class="user-information-form-wrap">
                        <h4>用户头像</h4>
                        <form action="#" method="post">
                            <div class="single-input-item">
                                <button class="btn btn-default">上传头像</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="user-information-form-wrap sign-up-form">
                        <h4>用户信息</h4>
                        <a href="#" class="float-right">更改密码</a>
                        <!-- <form class="form-horizontal" role="form" action="{{ route('user_information.update') }}" method="post"> -->
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}
                            <div class="single-input-item">
                                用户名：
                                <input type="text" name="name" placeholder="请输入您的用户名" old-data="{{ $user->name }}" value="{{ $user->name }}" />
                            </div>
                            <div class="single-input-item">
                                邮箱：
                                <input type="email" name="email" placeholder="请输入您的邮箱" old-data="{{ $user->email }}" value="{{ $user->email }}" readonly />
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="single-input-item">
                                        性别：
                                        <select name="sex" id="showSex" class="form-control">
                                            <option value="0"></option>
                                            <option value="1">男</option>
                                            <option value="2">女</option>
                                        </select>
                                        <input type="hidden" id ="transferData" value="{{ $user->sex }}" />
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="single-input-item">
                                        生日：
                                        <input type="date" name="birthday" placeholder="请选择您的生日" old-data="{{ $user->birthday }}" value="{{ $user->birthday }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="single-input-item">
                                注册时间：{{ $user->created_at }}
                            </div>
                            <div class="single-input-item">
                                <button class="btn btn-reset">重置</button>
                                <button class="btn btn-confirm">确定修改</button>
                            </div>
                        <!-- </form> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scriptsAfterJs')
<script>
    $(document).ready(function() {
        var key = $('#transferData').val();
        $("#showSex option[value='"+key+"']").attr("selected","selected"); 
        
        // 监听修改按钮的点击事件
        $('.btn-confirm').click(function() {
            axios.put('{{ route('user_information.update') }}',
            {
                name: $("input[name='name']").val(),
                sex: $('#showSex').find("option:selected").val(),
                birthday: $("input[name='birthday']").val(),
            }).then(function() { // 请求成功会执行这个回调
                swal('操作成功', '', 'success').then(function () {
                    // 这里加了一个 then() 方法
                    location.reload();
                });
            }, function(error) { // 请求失败会执行这个回调
                // 如果返回码是 401 代表没登录
                if (error.response && error.response.status === 401) {
                    swal('请先登录', '', 'error');
                } else if (error.response && error.response.data.msg) {
                    // 其他有 msg 字段的情况，将 msg 提示给用户
                    swal(error.response.data.msg, '', 'error');
                } else {
                    // 其他情况应该是系统挂了
                    swal('系统错误', '', 'error');
                }
            });
        });

        $('.btn-reset').click(function() {
            var name = $("input[name='name']").attr('old-data');
            var sex = $('#transferData').val();
            var birthday = $("input[name='birthday']").attr('old-data');

            $("input[name='name']").val(name);
            $("#showSex option").removeAttr("selected");
            $("#showSex option[value='"+sex+"']").attr("selected","selected");
            $("input[name='birthday']").val(birthday);
        })
    });
</script>
@endsection