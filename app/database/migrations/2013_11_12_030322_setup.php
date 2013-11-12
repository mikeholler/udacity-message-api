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
            $table->string('gname')->unique();
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

        Schema::create('messageBody', function(Blueprint $table) {
            $table->increments('bodyId');
            $table->mediumText('body');

            $table->engine = 'InnoDB';
        });

        Schema::create('message', function(Blueprint $table) {
            $table->increments('messageId');
            $table->unsignedInteger('bodyId');
            $table->unsignedInteger('fromUser');
            $table->dateTime('created');

            $table->foreign('bodyId')
                ->references('bodyId')
                ->on('messageBody');

            $table->foreign('fromUser')
                ->references('userId')
                ->on('user');

            $table->engine = 'InnoDB';
        });

        Schema::create('userMessage', function(Blueprint $table) {
            $table->unsignedInteger('messageId');
            $table->unsignedInteger('userId');
            $table->boolean('read');

            $table->foreign('messageId')
                ->references('messageId')
                ->on('message');

            $table->foreign('userId')
                ->references('userId')
                ->on('user');

            $table->primary(['messageId', 'userId']);

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
        Schema::drop('userMessage');
        Schema::drop('messageBody');
        Schema::drop('message');
        Schema::drop('groupMember');
        Schema::drop('group');
        Schema::drop('user');
	}

}