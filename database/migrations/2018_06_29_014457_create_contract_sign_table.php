<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractSignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_sign', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id')->comment('交易号');
            $table->string('contract_id')->comment('合同表id');
            $table->string('template_url')->comment('合同模板链接');
            $table->timestamp('created_at')->comment('签署时间');
            $table->unsignedInteger('customer_id')->comment('签署人');
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
        Schema::dropIfExists('contract_sign');
    }
}
