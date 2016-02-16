<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendeeCounselorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendee_counselor', function($table)
        {
            $table->increments('id');
            $table->integer('attendee_id')->unsigned()->index();
            $table->foreign('attendee_id')->references('id')->on('attendees')->onDelete('cascade');
            $table->integer('counselor_id')->unsigned()->index();
            $table->foreign('counselor_id')->references('id')->on('counselors')->onDelete('cascade');
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
        Schema::dropIfExists('attendee_counselor');
    }
}
