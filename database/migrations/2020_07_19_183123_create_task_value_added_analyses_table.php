<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskValueAddedAnalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_value_added_analyses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('type')->nullable();
            $table->integer('value_added_analysis_id')->unsigned();
            $table->integer('task_id')->unsigned();
            $table->integer('value');
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::table('tasks_value_added_analyses', function($table) {
            $table->foreign('value_added_analysis_id')->references('id')->on('value_added_analyses');
            $table->foreign('task_id')->references('id')->on('tasks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks_value_added_analyses');
    }
}
