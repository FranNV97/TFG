<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssueRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_registers', function (Blueprint $table) {
            $table->integer('issue_id')->unsigned();
            $table->integer('priority');
            $table->string('qualitativeImpact')->default("");
            $table->string('quantitativeImpact')->default("");
            $table->timestamps();
            $table->primary('issue_id');
        });

        Schema::table('issue_registers', function($table) {
            $table->foreign('issue_id')->references('id')->on('issues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issue_registers');
    }
}
