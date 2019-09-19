<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            // 所属订单号
            $table->unsignedBigInteger('order_id');
            // 订单号 关联外键
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            // 所属商品id
            $table->unsignedBigInteger('product_id');
            // 所属商品id 关联外键
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            // 所属商品sku id
            $table->unsignedBigInteger('product_sku_id');
            // 所属商品sku id 关联外键
            $table->foreign('product_sku_id')->references('id')->on('product_skus')->onDelete('cascade');
            // 商品数量
            $table->unsignedInteger('amount');
            // 商品单价
            $table->decimal('price', 10, 2);
            // 用户打分
            $table->unsignedInteger('rating')->nullable();
            // 用户评价
            $table->text('review')->nullable();
            // 用户评价的时间
            $table->timestamp('reviewed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
