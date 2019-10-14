<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedCategoriesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categories = [
            [
                'name' => '分享',
                'description' => '分享创造，分享发现',
            ],
            [
                'name' => '问答',
                'description' => '请保持友善，互帮互助',
            ],
            [
                'name' => '公告',
                'description' => '站点公告',
            ],
            [
                'name' => '定制',
                'description' => '提供用户定制渠道',
            ],
        ];

        DB::table('topics_categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('topics_categories')->truncate();
    }
}
