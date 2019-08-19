<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // 用户id
            $table->bigIncrements('id');
            // 用户名
            $table->string('name');
            // 电子邮箱 unique()用来确保邮箱唯一
            $table->string('email')->unique();
            // 判断用户是否已经验证过邮箱 nullable()表示验证字段的值可以是 null
            $table->timestamp('email_verified_at')->nullable();
            // 密码
            $table->string('password');
            // 用于记住密码
            $table->rememberToken();
            // 时间戳,操作对应数据表的 created_at, updated_at 字段
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
