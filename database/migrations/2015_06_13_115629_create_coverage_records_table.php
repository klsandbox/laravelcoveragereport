<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoverageRecordsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('coverage_records', function(Blueprint $table) {
            $table->integer('coverage_run_id')->unsigned();
            $table->foreign('coverage_run_id')->references('id')->on('coverage_runs');
            
            $table->increments('id');
            $table->timestamps();
            
            $table->enum('category', ['Route', 'View']);
            $table->string('namespace');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('coverage_records');
    }

}
