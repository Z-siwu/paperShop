<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dorm', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('sid')->commit('学校id');
            $table->string('dorm_name', 20)->commit('宿舍名称');
            $table->Integer('create_user')->commit('创建人id');
            $table->Integer('update_user')->commit('修改人id');
            $table->index('sid');
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
        Schema::drop('dorm');
    }
}
