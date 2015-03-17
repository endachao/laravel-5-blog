<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        Schema::create('category',function(Blueprint $table){

            $table->increments('id');
            $table->string('cate_name')->unique();
            $table->string('as_name')->unique();
            $table->integer('parent_id')->default(0);
            $table->string('seo_title');
            $table->string('seo_key');
            $table->string('seo_desc');
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
        Schema::drop('category');
	}

}
