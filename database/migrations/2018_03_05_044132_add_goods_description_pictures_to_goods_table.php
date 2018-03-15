<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoodsDescriptionPicturesToGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('goods', 'goods_description_pictures')) {
            Schema::table('goods', function (Blueprint $table) {
                $table->string('goods_description_pictures', '300')->comment('商品描述图片');
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
        Schema::table('goods', function (Blueprint $table) {
            $table->dropColumn('goods_description_pictures');
        });
    }
}
