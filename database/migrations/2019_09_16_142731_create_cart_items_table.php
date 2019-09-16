<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            // 用户 id
            $table->unsignedBigInteger('user_id');
            // 用户 id 关联外键
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // 商品 sku id
            $table->unsignedBigInteger('product_sku_id');
            // 商品 sku id 关联外键
            $table->foreign('product_sku_id')->references('id')->on('product_skus')->onDelete('cascade');
            // 商品数量
            $table->unsignedInteger('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
