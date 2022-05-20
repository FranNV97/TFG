<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDocumentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('type');
            $table->integer('process_id')->unsigned()->nullable();
            $table->integer('issue_register_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('documentations', function($table) {
            $table->foreign('process_id')->references('id')->on('processes');
            $table->foreign('issue_register_id')->references('issue_id')->on('issue_registers');
        });

        DB::statement("ALTER TABLE documentations ADD file MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentations');
    }
}
