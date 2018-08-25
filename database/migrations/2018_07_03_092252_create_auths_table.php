<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auths', function (Blueprint $table) {
            $table->increments('id');
            $table->string('open_id')->comment('微信用户唯一标识');
            $table->string('mobile')->nullable()->comment('绑定手机号');
            $table->unsignedInteger('flag')->default(0)->comment('用户来源标识，1 未微信；2 安居客...');
            $table->text('original')->nullable()->content('用户授权原始信息');
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
        Schema::dropIfExists('auths');
    }
}
