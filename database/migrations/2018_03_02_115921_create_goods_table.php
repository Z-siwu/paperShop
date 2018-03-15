<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('goods')) {
            Schema::create('goods', function (Blueprint $table) {
                $table->increments('id');
                $table->string('goods_name', 100)->nullable(false)->default('')->comment('商品名称');
                $table->integer('class_id')->nullable(false)->default(0)->comment('所属分类ID');
                $table->float('goods_price', 6, 2)->nullable(false)->default(0.00)->comment('商品单价');
                $table->float('goods_marketprice', 6, 2)->nullable(false)->default(0.00)->comment('商品市场价');
                $table->float('goods_onsaleprice', 6, 2)->nullable(false)->default(0.00)->comment('商品折扣价');
                $table->string('goods_main_image', 255)->nullable(false)->default('')->comment('商品主图路径');
                $table->string('goods_small_image', 255)->nullable(false)->default('')->comment('商品缩略图路径');
                $table->integer('goods_salenum')->nullable(false)->default(0)->comment('商品销量');
                $table->integer('goods_storage')->nullable(false)->default(0)->comment('商品库存');
                $table->integer('goods_click')->nullable(false)->default(0)->comment('商品点击量');
                $table->string('goods_desc', 300)->nullable()->default('')->comment('商品描述');
                $table->tinyInteger('goods_state')->unsigned()->nullable(false)->default(1)->comment('商品状态(0-下架,1-正常,2-禁售)');
                $table->timestamps();
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
        Schema::dropIfExists('goods');
    }
}
