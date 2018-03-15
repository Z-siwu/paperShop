<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class  CreateOrderGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oid')->nullable(false)->default(0)->comment('订单号');
            $table->integer('uid')->nullable(false)->default(0)->comment('用户id');
            $table->integer('goods_id')->nullable(false)->default(0)->comment('商品id');
            $table->string('goods_name', 50)->nullable(false)->default(0)->comment('商品名称');
            $table->string('goods_image', 100)->nullable(false)->default(0)->comment('商品图片');
            $table->string('goods_desc', 200)->nullable(false)->default(0)->comment('商品说明');
            $table->decimal('goods_price', 10 ,2)->nullable(false)->default(0)->comment('商品金额');
            $table->integer('num')->nullable(false)->default(0)->comment('商品购买数量');
            $table->decimal('addr_price', 10 ,2)->nullable(true)->comment('运费');
            $table->decimal('coupon_price', 10 ,2)->nullable(true)->comment('代金券减免 金额');
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
        Schema::dropIfExists('order_goods');
    }
}

