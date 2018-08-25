<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLookHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('look_houses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('look_id')->default(0)->comment('看单 ID');
            $table->string('building_code')->nullable()->comment('座栋编码');
            $table->string('building_name')->nullable()->comment('座栋名称');
            $table->string('unit')->nullable()->comment('单元');
            $table->string('house_code')->nullable()->comment('房屋编码');
            $table->string('house_name')->nullable()->comment('房屋名称');
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
        Schema::dropIfExists('look_houses');
    }
}
