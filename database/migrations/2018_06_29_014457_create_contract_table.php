<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template_id')->comment('合同模板id');
            $table->string('contract_id ')->comment('合同编号');
            $table->unsignedInteger('party_a_id')->default(0)->comment('甲方信息 ID');
            $table->unsignedInteger('party_b_id')->default(0)->comment('乙方信息 ID');
            $table->timestamp('filing_at')->comment('归档时间');
            $table->unsignedInteger('agent_a_id')->default(0)->comment('甲方代理人 ID');
            $table->unsignedInteger('agent_b_id')->default(0)->comment('乙方代理人 ID');
            $table->string('viewpdf_url')->comment('合同查看地址');
            $table->string('download_url')->comment('合同下载地址');
            $table->text('stop_reason')->comment('合同终止原因');
            $table->tinyInteger('is_filing ')->comment('是否归档');
            $table->tinyInteger('status ')->comment('状态');
            $table->tinyInteger('rent_time ')->comment('租赁时长');
            $table->timestamp('start_rent_time')->comment('起租日期');
            $table->timestamp('end_rent_time')->comment('截止日期');
            $table->timestamp('payment_time')->comment('缴费时间');
            $table->decimal('rent_price')->comment('租金');
            $table->decimal('deposit_price')->comment('押金');
            $table->decimal('party_a_commission')->comment('甲方佣金');
            $table->decimal('party_b_commission')->comment('乙方佣金');
            $table->tinyInteger('payment_cycle')->comment('缴费周期');
            $table->string('rent_account ')->comment('收租账户');
            $table->string('account_name ')->comment('开户名');
            $table->string('account_bank ')->comment('开户行');
            $table->text('additional_message')->comment('补充条款');
            $table->unsignedInteger('creator_id')->comment('创建人');
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
        Schema::dropIfExists('contract');
    }
}
