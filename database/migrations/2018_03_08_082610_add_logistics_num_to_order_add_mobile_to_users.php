<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticsNumToOrderAddMobileToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('users', 'mobile')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('mobile',11)->nullable(false)->default('')->comment('用户手机号');
            });
        }
        if (!Schema::hasColumn('order', 'logistics_num')) {
            Schema::table('order', function (Blueprint $table) {
                $table->string('logistics_num', '200')->nullable()->default('')->comment('物流单号');
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
        if (Schema::hasColumn('users', 'mobile')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('mobile');
            });
        }

        if (Schema::hasColumn('order', 'logistics_num')) {
            Schema::table('order', function (Blueprint $table) {
                $table->dropColumn('logistics_num');
            });
        }
    }
}
