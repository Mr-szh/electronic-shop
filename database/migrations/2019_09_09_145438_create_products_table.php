<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            // 商品名称
            $table->string('title');
            // 商品详情
            $table->text('description');
            // 商品封面图片
            $table->string('image');
            // 商品详情图片
            $table->string('images');
            // 商品是否在售卖
            $table->boolean('on_sale')->default(true);
            // 商品平均评分
            $table->float('rating')->default(5);
            // 商品销量
            $table->unsignedInteger('sold_count')->default(0);
            // 商品评价数量
            $table->unsignedInteger('review_count')->default(0);
            // 商品SKU最低价格
            $table->decimal('price', 10, 2);
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
        Schema::dropIfExists('products');
    }
}
