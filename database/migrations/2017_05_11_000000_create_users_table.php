<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->increments('id');
            $table->string('username')->unique();;
            $table->string('nickname')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('password', 60)->nullable();
            $table->string('introduction')->nullable();
            $table->integer('notification_count')->default(0);
            $table->string('avatar')->nullable();
            $table->boolean('verified')->default(false);
            $table->enum('email_notify_enabled', ['yes',  'no'])->default('yes');
            $table->rememberToken();
            $table->timestamp('last_actived_at');
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
