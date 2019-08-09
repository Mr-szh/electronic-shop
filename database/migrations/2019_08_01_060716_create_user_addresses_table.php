<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            // 递增主键
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            // 设置外键
            // onDelete: 如果父表的记录被删除，那么子表的记录也相应的删除
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // 三级联动
            $table->string('province');
            $table->string('city');
            $table->string('district');

            $table->string('address');
            $table->unsignedInteger('zip');
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->dateTime('last_used_at')->nullable();
            // 每个迁移文件名都包含时间戳
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
        Schema::dropIfExists('user_addresses');
    }
}
