<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->text('content');
            $table->tinyInteger('pay_mode');

            $table->decimal('signed_money', 12)->default(0);
            $table->decimal('advance_payment_amount', 12)->default(0);
            $table->tinyInteger('advance_payment_times')->nullable();
            $table->tinyInteger('progress_payment_pct')->nullable();

            $table->date('signed_date')->nullable();
            $table->enum('visible', ['on', 'off'])->default('on');
            $table->integer('project_id')->unsigned();
            $table->string('sync_from')->nullable();
            $table->string('sync_pk')->nullable();
            $table->timestamp('sync_at')->nullable();
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
        Schema::dropIfExists('contracts');
    }
}
