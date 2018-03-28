<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoodsCarouselToGoods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('goods', 'goods_carousel')) {
            Schema::table('goods', function (Blueprint $table) {
                $table->string('goods_carousel',300)->nullable()->default('')->comment('商品轮播图片');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        if (Schema::hasColumn('goods', 'goods_carousel')) {
            Schema::table('goods', function (Blueprint $table) {
                $table->dropColumn('goods_carousel');
            });
        }
    }
}
