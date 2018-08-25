<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractAnnexeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_annexe', function (Blueprint $table) {
            $table->increments('id');
            $table->string('annexe_id')->comment('合同附件id');
            $table->string('annexe_name')->comment('合同附件名称');
            $table->string('annexe_url')->comment('合同附件链接');
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
        Schema::dropIfExists('contract_annexe');
    }
}
