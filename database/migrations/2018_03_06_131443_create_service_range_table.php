<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceRangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('service_range')) {
            Schema::create('service_range', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('delivery_uid')->default(0)->comment('配送员userID');
                $table->integer('sid')->default(0)->comment('学校ID');
                $table->string('school_name')->nullable()->default('')->comment('学校名');
                $table->integer('did')->default(0)->comment('宿舍名');
                $table->string('dorm_name')->nullable()->default('')->comment('宿舍名');
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
        Schema::dropIfExists('service_range');
    }
}
