<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile')->nullable()->comment('手机号');
            $table->string('real_name')->nullable()->comment('用户真实名称');
            $table->string('sex')->default(2)->comment('性别,默认未知');
            $table->string('id_card')->nullable()->comment('身份证号');
            $table->string('user_business_code')->nullable()->comment('中介从业资格编号');
            $table->unsignedInteger('city_id')->default(0)->comment('所属城市 ID');
            $table->string('city_name')->nullable()->comment('所属城市名称');
            $table->string('business_name')->nullable()->comment('所属公司');
            $table->text('id_card_photo_front')->nullable()->comment('身份证正面 URL');
            $table->text('id_card_photo_reverse')->nullable()->comment('身份证背面 URL');
            $table->unsignedInteger('status')->default(0)->comment('用户状态，0 未审核；1 已审核；2 已封禁');
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
        Schema::dropIfExists('users');
    }
}
