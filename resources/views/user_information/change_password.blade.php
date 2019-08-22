@extends('layouts.app')
@section('title', '更改密码')

@section('content')
<div class="section-padding">
    <div class="container">
        <div class="member-area-from-wrap">
            <div class="row">

                <div class="col-lg-6">
                    <div class="user-information-form-wrap">
                        <h4>用户头像</h4>
                        <form action="#" method="post">
                            <div class="box">
                                <img class="img-thumbnail" src="{{ URL::asset($user->avatar) }}" />
                            </div>
                            <div class="single-input-item text-center">
                                <h5>{{ $user->name }}</h5>
                                <h6>{{ $user->created_at->toDateString() }}</h6>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="user-information-form-wrap sign-up-form">
                        <h4>更改密码</h4>
                        <a href="{{ route('user_information.index') }}" class="float-right">返回个人信息页</a>
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="single-input-item">
                            原始密码：
                            <input type="text" name="oldPassword" placeholder="请输入您的原密码" />
                        </div>
                        <div class="single-input-item">
                            新密码：
                            <input type="text" name="password" placeholder="请输入您的新密码" />
                        </div>
                        <div class="single-input-item">
                            确认新密码：
                            <input type="text" name="password_confirmation" placeholder="再次输入您的新密码" />
                        </div>
                        <div class="single-input-item">
                            <button class="btn btn-reset">重置</button>
                            <button class="btn btn-confirm">确定更改</button>
                        </div>
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
        // 监听更改按钮的点击事件
        $('.btn-confirm').click(function() {
            var msg = '';

            axios.put('{{ route('user_information.replace') }}', {
                    oldPassword: $("input[name='oldPassword']").val(),
                    password: $("input[name='password']").val(),
                    password_confirmation: $("input[name='password_confirmation']").val(),
                }).then(function() {
                swal('密码更改成功', '', 'success').then(function() {
                    location.reload();
                });
            }, function(error) {
                $("input").removeClass('dangerous');
                if (error.response.data.errors.oldPassword) {
                    msg = msg + error.response.data.errors.oldPassword[0];
                    $("input[name='oldPassword']").addClass('dangerous');
                } else if (error.response.data.errors.password) {
                    msg = msg + error.response.data.errors.password[0];
                    $("input[name='password']").addClass('dangerous');
                } else if (error.response.data.errors.password_confirmation) {
                    msg = msg + error.response.data.errors.password_confirmation[0];
                    $("input[name='password_confirmation']").addClass('dangerous');
                } else {
                    swal('系统错误', '', 'error');
                }
                swal('输入错误', msg, 'error');
            });
        });

        $('.btn-reset').click(function() {
            $("input[name='oldPassword']").val('');
            $("input[name='password']").val('');
            $("input[name='password_confirmation']").val('');
        });
    });
</script>
@endsection