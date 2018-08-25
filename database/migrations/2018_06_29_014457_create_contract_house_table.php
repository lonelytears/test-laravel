<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractHouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_house', function (Blueprint $table) {
            $table->increments('id');
            $table->string('community_name')->comment('战图小区名');
            $table->string('house_name')->comment('门牌号');
            $table->string('house_code')->comment('房源编号');
            $table->string('property_sn')->comment('产权证编号');
            $table->unsignedInteger('property_user')->comment('产权人');
            $table->unsignedInteger('contract_id')->comment('合同表id');
            $table->unsignedInteger('creator_id')->comment('创建人');
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
        Schema::dropIfExists('contract_house');
    }
}
