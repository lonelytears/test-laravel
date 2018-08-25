<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->comment('姓名');
            $table->string('city_name')->comment('意向城市');
            $table->string('job_name')->comment('意向岗位');
            $table->date('birthday')->comment('出生年月');
            $table->string('mobile')->comment('电话号码');
            $table->string('education')->comment('最高学历');
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
        Schema::dropIfExists('recruits');
    }
}
