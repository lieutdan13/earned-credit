<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendeeProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendee_program', function($table)
        {
            $table->increments('id');
            $table->integer('attendee_id')->unsigned()->index();
            $table->foreign('attendee_id')->references('id')->on('attendees')->onDelete('cascade');
            $table->integer('program_id')->unsigned()->index();
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendee_program');
    }
}
