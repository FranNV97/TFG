<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssueTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues_tasks', function (Blueprint $table) {
            $table->integer('issue_id')->unsigned();
            $table->integer('task_id')->unsigned();
            $table->timestamps();
            $table->primary(['issue_id', 'task_id']);
        });

        Schema::table('issues_tasks', function($table) {
            $table->foreign('issue_id')->references('id')->on('issues');
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
        Schema::dropIfExists('issues_tasks');
    }
}
