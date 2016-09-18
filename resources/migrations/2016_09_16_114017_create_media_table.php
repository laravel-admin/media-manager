<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');

			$table->boolean('active');
			$table->integer('user_id')->unsigned()->nullable();
			$table->string('name');
			$table->text('description')->nullable();
			$table->string('storage')->nullable();
			$table->string('source')->nullable();
			$table->string('type')->nullable();
			$table->integer('size')->nullable();
			
			$table->text('styles')->nullable();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

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
		Schema::table('media', function(Blueprint $table)
		{
			$table->dropForeign('media_user_id_foreign');
		});

        Schema::dropIfExists('media');
    }
}
