<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_template', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template_id')->comment('合同模板表id(模板编号)');
            $table->string('template_name')->comment('模板名称');
            $table->string('template_url')->comment('模板链接');
            $table->timestamp('created_at')->comment('创建时间');
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
        Schema::dropIfExists('contract_template');
    }
}
