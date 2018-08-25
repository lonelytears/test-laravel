<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contract_name')->comment('用户姓名');
            $table->string('idCard')->comment('用户身份证');
            $table->string('mobile')->comment('用户手机');
            $table->string('bankCard')->comment('用户银行卡号');
            $table->string('customerId')->comment('用户ca认证号');
            $table->timestamp('created_at')->comment('创建时间');
            $table->timestamp('updated_at')->comment('更新时间');
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
        Schema::dropIfExists('contract_user');
    }
}
