<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery', function (Blueprint $table) {
            $table->increments('id')->comment('物流id');
            $table->integer('oid')->nullable(false)->default(0)->comment('订单号');
            $table->integer('uid')->nullable(false)->default(0)->comment('前台员操作id');
            $table->string('delivery_name', 50)->nullable(false)->default(0)->comment('配送员名称');
            $table->string('delivery_mobile', 11)->nullable(false)->default(0)->comment('配送员手机号');
            $table->string('desc', 255)->nullable(false)->default(0)->comment('说明（系统备注）');
            $table->string('remark', 255)->nullable(false)->default(0)->comment('说明（有备注说明是问题订单）');
            $table->tinyInteger('status')->nullable(false)->default(1)->comment('状态');
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
        Schema::dropIfExists('delivery');
    }
}


