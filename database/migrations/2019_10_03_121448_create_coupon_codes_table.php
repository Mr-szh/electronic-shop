<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            // 优惠券名称
            $table->string('name');
            // 优惠码
            $table->string('code')->unique();
            // 优惠券类型，支持固定金额和百分比折扣
            $table->string('type');
            // 折扣值
            $table->decimal('value');
            // 全站可兑换的数量
            $table->unsignedInteger('total');
            // 当前已兑换的数量
            $table->unsignedInteger('used')->default(0);
            // 使用该优惠券的最低订单金额
            $table->decimal('min_amount', 10, 2);
            // 在这个时间之前不可用
            $table->datetime('not_before')->nullable();
            // 在这个时间之后不可用
            $table->datetime('not_after')->nullable();
            // 优惠券是否生效
            $table->boolean('enabled');
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
        Schema::dropIfExists('coupon_codes');
    }
}
