<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        Schema::create('article',function(Blueprint $table){

            $table->increments('id');
            $table->integer('cate_id');
            $table->integer('user_id');
            $table->string('title')->unique();
            $table->text('content');
            $table->string('tags')->nullable();
            $table->string('keyword')->nullable();
            $table->string('desc')->nullable();
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
		//
        Schema::drop('article');
	}

}
