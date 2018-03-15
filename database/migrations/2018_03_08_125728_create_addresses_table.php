<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('addresses');
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id')->comment('地址id');
            $table->Integer('uid')->comment('用户id');
            $table->Integer('aid_p')->default(0)->comment('省id');
            $table->Integer('aid_c')->default(0)->comment('市id');
            $table->Integer('aid_a')->default(0)->comment('区/县id');
            $table->Integer('sid')->default(0)->comment('学校id');
            $table->Integer('did')->default(0)->comment('宿舍id');
            $table->string('province',30)->default('')->comment('省');
            $table->string('city',30)->default('')->comment('市');
            $table->string('area',30)->default('')->comment('区/县');
            $table->string('postcode',30)->default('')->comment('邮政编码');
            $table->string('school_name',30)->default('')->comment('学校');
            $table->string('dorm_name',30)->default('')->comment('宿舍/校区');
            $table->string('addr',255)->default('')->comment('详细地址');
            $table->string('true_name',50)->default('')->comment('真实姓名');
            $table->string('mobile',12)->comment('手机号');
            $table->tinyInteger('is_default')->nullable()->default(0)->comment('是否默认');
            $table->tinyInteger('status')->nullable()->default(1)->comment('状态 1无效 2有效');
            $table->index(['uid', 'sid']);
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
        Schema::drop('addresses');
    }
}
