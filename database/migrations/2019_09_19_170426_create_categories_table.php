<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            // 类目名称
            $table->string('name');
            // 父类目 ID
            $table->unsignedBigInteger('parent_id')->nullable();
            // 父类目 ID 关联外键
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            // 是否拥有子类目
            $table->boolean('is_directory');
            // 当前类目层级
            $table->unsignedInteger('level');
            // 该类目所有父类目 id
            $table->string('path');
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
        Schema::dropIfExists('categories');
    }
}
