<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('developer')->nullable();
            $table->string('contractor')->nullable();
            $table->string('build_area')->nullable();
            $table->decimal('contract_money', 12)->default(0);
            $table->string('manager')->nullable();
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->date('start')->nullable();
            $table->date('finish')->nullable();
            $table->string('location')->nullable();
            $table->enum('visible', ['on', 'off'])->default('on');
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
        Schema::dropIfExists('projects');
    }
}
