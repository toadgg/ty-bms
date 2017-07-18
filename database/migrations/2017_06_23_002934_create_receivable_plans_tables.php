<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceivablePlansTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 项目应收工程款主表
        Schema::create('project_receivable_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->date('calendar');
            $table->decimal('receivable_in_contract', 12)->default(0);
            $table->decimal('receivable_in_contract_amount', 12)->default(0);
            $table->timestamp('statistical_data')->nullable();
            $table->timestamps();
        });

        // 项目产值汇总表
        Schema::create('project_output_value_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->smallInteger('year')->nulable();
            $table->decimal('m_1', 12)->default(0)->nulable();
            $table->decimal('m_2', 12)->default(0)->nulable();
            $table->decimal('m_3', 12)->default(0)->nulable();
            $table->decimal('m_4', 12)->default(0)->nulable();
            $table->decimal('m_5', 12)->default(0)->nulable();
            $table->decimal('m_6', 12)->default(0)->nulable();
            $table->decimal('m_7', 12)->default(0)->nulable();
            $table->decimal('m_8', 12)->default(0)->nulable();
            $table->decimal('m_9', 12)->default(0)->nulable();
            $table->decimal('m_10', 12)->default(0)->nulable();
            $table->decimal('m_11', 12)->default(0)->nulable();
            $table->decimal('m_12', 12)->default(0)->nulable();
            $table->timestamps();
        });

        // 项目产值
        Schema::create('project_output_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->date('calendar');
            $table->decimal('declare_amount', 12)->default(0);
            $table->date('declare_date');
            $table->decimal('audited_amount', 12)->default(0);
            $table->date('audited_date');
            $table->timestamps();
        });

        // 项目收款汇总表
        Schema::create('project_receipt_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->smallInteger('year')->nulable();
            $table->decimal('m_1', 12)->default(0)->nulable();
            $table->decimal('m_2', 12)->default(0)->nulable();
            $table->decimal('m_3', 12)->default(0)->nulable();
            $table->decimal('m_4', 12)->default(0)->nulable();
            $table->decimal('m_5', 12)->default(0)->nulable();
            $table->decimal('m_6', 12)->default(0)->nulable();
            $table->decimal('m_7', 12)->default(0)->nulable();
            $table->decimal('m_8', 12)->default(0)->nulable();
            $table->decimal('m_9', 12)->default(0)->nulable();
            $table->decimal('m_10', 12)->default(0)->nulable();
            $table->decimal('m_11', 12)->default(0)->nulable();
            $table->decimal('m_12', 12)->default(0)->nulable();
            $table->timestamps();
        });

        // 收款纪录表
        Schema::create('project_receipt_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('project_id')->unsigned();
            $table->date('calendar');
            $table->decimal('receipt_amount', 12)->default(0);
            $table->date('receipt_date');
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
        Schema::dropIfExists('project_receivable_statements');
        Schema::dropIfExists('project_output_value_statements');
        Schema::dropIfExists('project_output_values');
        Schema::dropIfExists('project_receipt_statements');
        Schema::dropIfExists('project_receipt_records');
    }
}
