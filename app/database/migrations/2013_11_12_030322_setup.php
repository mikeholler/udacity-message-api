<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Setup extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('user', function(Blueprint $table) {
            $table->increments('userId');
            $table->string('username')->unique();
            $table->dateTime('created');

            $table->engine = 'InnoDB';
        });

        Schema::create('group', function(Blueprint $table) {
            $table->increments('groupId');
            $table->string('groupName')->unique();
            $table->dateTime('created');

            $table->engine = 'InnoDB';
        });

        Schema::create('groupMember', function(Blueprint $table) {
            $table->unsignedInteger('groupId');
            $table->unsignedInteger('userId');

            $table->foreign('groupId')
                ->references('groupId')
                ->on('group');

            $table->foreign('userId')
                ->references('userId')
                ->on('user');

            $table->primary(['groupId', 'userId']);

            $table->engine = 'InnoDB';
        });

        Schema::create('messageSend', function(Blueprint $table) {
            $table->increments('messageId');
            $table->unsignedInteger('fromUser');
            $table->string('subject');
            $table->dateTime('created');

            $table->foreign('fromUser')
                ->references('userId')
                ->on('user');

            $table->engine = 'InnoDB';
        });

        Schema::create('messageBody', function(Blueprint $table) {
            $table->increments('messageId');
            $table->mediumText('body');

            $table->foreign('messageId')
                ->references('messageId')
                ->on('messageSend');

            $table->engine = 'InnoDB';
        });

        Schema::create('messageReceive', function(Blueprint $table) {
            $table->unsignedInteger('messageId');
            $table->unsignedInteger('toUser');
            $table->boolean('read');

            $table->foreign('messageId')
                ->references('messageId')
                ->on('messageSend');

            $table->foreign('toUser')
                ->references('userId')
                ->on('user');

            $table->primary(['messageId', 'toUser']);

            $table->engine = 'InnoDB';
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('groupMember');
        Schema::dropIfExists('group');
        Schema::dropIfExists('messageReceive');
        Schema::dropIfExists('messageBody');
        Schema::dropIfExists('messageSend');
        Schema::dropIfExists('user');
	}

}