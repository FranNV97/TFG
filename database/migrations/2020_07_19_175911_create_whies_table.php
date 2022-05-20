<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('answer');
            $table->integer('level');
            $table->boolean('rootCause')->default(false);
            $table->integer('five_why_id')->unsigned();
            $table->integer('why_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('whies', function($table) {
            $table->foreign('five_why_id')->references('issue_id')->on('five_whies');
            $table->foreign('why_id')->references('id')->on('whies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whies');
    }
}
