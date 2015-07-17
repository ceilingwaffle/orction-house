<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title', 50);
            $table->text('description');
            $table->decimal('start_price', 8, 2);
            $table->dateTime('end_date');
            $table->string('image_file_name')->nullable();

            $table->unsignedInteger('user_id'); // Auction created by user ID
            $table->unsignedInteger('auction_category_id');
            $table->unsignedInteger('auction_condition_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('auction_category_id')->references('id')->on('auction_categories');
            $table->foreign('auction_condition_id')->references('id')->on('auction_conditions');

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
        Schema::drop('auctions');
    }
}
