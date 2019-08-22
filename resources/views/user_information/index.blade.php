@extends('layouts.app')
@section('title', '用户信息')

@section('content')
<div class="section-padding">
    <div class="container">
        <div class="member-area-from-wrap">
            <div class="row">

                <div class="col-lg-6">
                    <div class="user-information-form-wrap">
                        <h4>用户头像</h4>
                        <form action="{{ route('user_information.updateAvatar') }}" method="post" class="text-center" enctype="multipart/form-data">
                            {{csrf_field()}}    
                            <div class="box">
                            
                                <img class="img-thumbnail" src="{{ URL::asset($user->avatar) }}" />
                            </div>
                            <div class="single-input-item">
                                <input class="upload" type="file" name="avatar">
                                <button class="btn btn-upload">上传头像</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="user-information-form-wrap sign-up-form">
                        <h4>用户信息</h4>
                        <a href="{{ route('user_information.change') }}" class="float-right">更改密码</a>
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
                                注册于：{{ $user->created_at->diffForHumans() }}
                            </div>
                            <div class="single-input-item">
                                <button class="btn btn-reset">重置</button>
                                <button class="btn btn-confirm">确定修改</button>
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
        var key = $('#transferData').val();
        $("#showSex option[value='"+key+"']").attr("selected","selected"); 
        
        // 监听修改按钮的点击事件
        $('.btn-confirm').click(function() {
            var msg = '';
            
            axios.put('{{ route('user_information.update') }}',
            {
                name: $("input[name='name']").val(),
                sex: $('#showSex').find("option:selected").val(),
                birthday: $("input[name='birthday']").val(),
            }).then(function() { // 请求成功会执行这个回调
                swal('修改成功', '', 'success').then(function () {
                    // 重新刷新页面
                    location.reload();
                });
            }, function(error) { 
                // 请求失败会执行这个回调
                $("input").removeClass('dangerous');

                if (error.response.data.errors.name) {
                    msg = msg + error.response.data.errors.name[0];
                    $("input[name='name']").addClass('dangerous');
                    swal('输入错误', msg, 'error');
                } else {
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
        });
        
        
    });
</script>
@endsection