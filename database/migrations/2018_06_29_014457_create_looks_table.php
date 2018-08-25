<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('looks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->comment('看单编号');
            $table->unsignedInteger('user_id')->default(0)->comment('用户 ID');
            $table->unsignedInteger('city_id')->default(0)->comment('城市 ID');
            $table->string('city_name')->nullable()->comment('城市名称');
            $table->string('community_code')->default(0)->comment('小区战图编码');
            $table->string('community_name')->nullable()->comment('小区战图名称');
            // $table->string('building_code')->nullable()->comment('座栋编码');
            // $table->string('building_name')->nullable()->comment('座栋名称');
            // $table->string('unit')->nullable()->comment('单元');
            // $table->string('house_code')->nullable()->comment('房屋编码');
            // $table->string('house_name')->nullable()->comment('房屋名称');
            $table->timestamp('look_at')->comment('约看时间');
            $table->timestamp('validate_at')->nullable()->comment('验证时间');
            $table->string('validate_user_id')->nullable()->comment('验证者 ID');
            $table->string('validate_user_mobile')->nullable()->comment('验证者 手机号');
            $table->unsignedInteger('status')->default(0)->comment('看单状态，0 初始状态；1 安全员确认；2 驳回');
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
        Schema::dropIfExists('looks');
    }
}
