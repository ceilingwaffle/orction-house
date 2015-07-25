<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');
            $table->unsignedInteger('auction_id')->unique();
            $table->unsignedInteger('feedback_type_id');
            $table->unsignedInteger('left_by_user_id');

            $table->foreign('auction_id')->references('id')->on('auctions');
            $table->foreign('feedback_type_id')->references('id')->on('feedback_types');
            $table->foreign('left_by_user_id')->references('id')->on('users');

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
        Schema::drop('feedback');
    }
}
