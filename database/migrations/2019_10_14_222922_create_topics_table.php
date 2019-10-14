<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            // 帖子标题
            $table->string('title')->index();
            // 帖子内容
            $table->text('body');
            // 用户id 外键
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // 帖子分类id 外键
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('topics_categories')->onDelete('cascade');
            // 回复数量
            $table->unsignedInteger('reply_count')->default(0);
            // 查看数量
            $table->unsignedInteger('view_count')->default(0);
            // 最后回复的用户 ID
            $table->unsignedInteger('last_reply_user_id')->default(0);
            // 可用来做排序使用
            $table->unsignedInteger('order')->default(0);
            // 文章摘要
            $table->text('excerpt')->nullable();
            // SEO 友好的 URI
            $table->string('slug')->nullable();

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
        Schema::dropIfExists('topics');
    }
}
