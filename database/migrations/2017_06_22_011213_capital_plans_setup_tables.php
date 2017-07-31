<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CapitalPlansSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 板块表
        Schema::create('plates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        // 付款明细分类
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        // 资金计划主表
        Schema::create('capital_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->integer('plate_id')->unsigned();
            $table->decimal('payment_amount', 12)->default(0);
            $table->date('calendar');
            $table->timestamp('statistical_data')->nullable();
            $table->timestamps();
        });

        // 资金计划明细表
        Schema::create('capital_plan_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('capital_plan_id')->nullable();
            $table->integer('category_id')->nullable()->index();
            $table->string('pay_to')->nullable();
            $table->string('info')->nullable();
            $table->decimal('contract_amount', 12)->default(0);
            $table->string('pay_mode')->nullable();
            $table->decimal('completed_amount', 12)->default(0);
            $table->decimal('payable_in_contract', 12)->default(0);
            $table->decimal('paid_in_contract', 12)->default(0);
            $table->decimal('paid_in_contract_amount', 12)->default(0);
            $table->decimal('payable_in_plan', 12)->default(0);
            $table->decimal('paid_in_plan', 12)->default(0);
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('plates');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('capital_plans');
        Schema::dropIfExists('capital_plan_details');
    }
}
