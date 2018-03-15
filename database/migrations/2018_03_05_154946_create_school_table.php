<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('school', function (Blueprint $table) {
            $table->increments('id')->comment('学校id');
            $table->Integer('aid_p')->comment('省id');
            $table->Integer('aid_c')->comment('市id');
            $table->Integer('aid_a')->comment('区/县id');
            $table->string('school_name',50)->comment('学校名称');
            $table->Integer('create_user')->nullable()->comment('创建人id');
            $table->Integer('update_user')->nullable()->comment('更新人id');
            $table->tinyInteger('status')->nullable()->comment('状态 1不支持了 2支持中');
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
        Schema::drop('school');
    }
}
