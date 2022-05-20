<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('type');
            $table->integer('state');
            $table->integer('process_id')->unsigned();
            $table->integer('issue_id')->unsigned()->nullable();
            $table->integer('creado')->unsigned();
            $table->integer('modificado')->unsigned();
            $table->timestamps();
        });

        Schema::table('issues', function($table) {
            $table->foreign('process_id')->references('id')->on('processes');
            $table->foreign('issue_id')->references('id')->on('issues');
            $table->foreign('creado')->references('id')->on('users');
            $table->foreign('modificado')->references('id')->on('users');
        });

        DB::statement("ALTER TABLE issues ADD recorte MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
}
