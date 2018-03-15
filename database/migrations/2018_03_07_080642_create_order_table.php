<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id')->comment('订单号');
            $table->integer('uid')->nullable(false)->default(0)->comment('用户id');
            $table->integer('sid')->nullable(True)->default(0)->comment('学校id');
            $table->integer('did')->nullable(True)->default(0)->comment('宿舍楼');
            $table->date('cancel_date')->nullable(True)->comment('取消时间');
            $table->date('pay_date')->nullable(True)->comment('支付时间');
            $table->string('school_name', 50)->nullable(True)->default('')->comment('学校名称');
            $table->string('dorm_name', 20)->nullable(false)->default('')->comment('宿舍楼名称');
            $table->tinyInteger('pay_method')->nullable(false)->default(1)->comment('支付方式，默认1：微信支付');
            $table->string('order_sn', 50)->nullable(True)->default(1)->comment('订单编号 index索引');
            $table->string('paycode', 50)->nullable(True)->default(1)->comment('支付的交易号');
            $table->string('trade_no', 50)->nullable(True)->default(1)->comment('支付宝微信交易号,(支付时返回，退款时用)');
            $table->decimal('total_price', 10 ,2)->nullable(false)->default(0)->comment('订单总金额');
            $table->decimal('addr_price', 10 ,2)->nullable(true)->comment('运费');
            $table->decimal('coupon_price', 10 ,2)->nullable(true)->comment('代金券减免 金额');
            $table->decimal('pay_price', 10 ,2)->nullable(true)->comment('支付的金额');
            $table->integer('aid_p')->nullable(false)->default(0)->comment('省id');
            $table->integer('aid_c')->nullable(false)->default(0)->comment('市id');
            $table->integer('aid_a')->nullable(false)->default(0)->comment('区/县id');
            $table->string('addr_prov', 50)->nullable(false)->default(0)->comment('收货地址-省');
            $table->string('addr_city', 50)->nullable(false)->default(0)->comment('收货地址-市');
            $table->string('addr_area', 50)->nullable(false)->default(0)->comment('收货地址-区');
            $table->string('addr_detail', 200)->nullable(false)->default(0)->comment('详细地址');
            $table->string('user_name', 50)->nullable(false)->default('')->comment('收货人');
            $table->string('user_mobile', 50)->nullable(false)->default('')->comment('收货人手机号');
            $table->integer('cancel_admin')->nullable(True)->comment('后台取消订单管理人员');
            $table->string('remark', 200)->nullable(true)->default('')->comment('备注 （有说明是问题订单）');
            $table->string('remark_img', 300)->nullable(true)->default('')->comment('备注图片');
            $table->string('msg', 200)->nullable(true)->default('')->comment('用户留言');
            /**
             * lock_state!=0 的时候 禁止一切人员操作
             * （1）lock_state=1订单待付款（2）lock_state=2 支付失败 （3）lock_state=3订单退款中
             * （4）lock_state=4 订单异常（如微信返回支付金额和订单里支付金额不一致）
             *
             */
            $table->tinyInteger('lock_state')->nullable(false)->default(0)->comment('订单锁状态 1待支付 2退款中 0支付完成后正常');
            /*  需系统完成-> 0:订单关闭或无效（用户取消订单也置0 前台该订单显示已取消  后台显示无效）。
            *10：订单待支付 22：订单支付完成
            */
            $table->tinyInteger('status')->nullable(false)->default(0)->comment('订单状态');
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
        Schema::dropIfExists('order');
    }
}