<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('type');
            $table->string('image_url');
            $table->string('level_one_icon')->nullable();
            $table->string('level_one_label')->nullable();
            $table->string('level_two_icon')->nullable();
            $table->string('level_two_label')->nullable();
            $table->string('level_three_icon')->nullable();
            $table->string('level_three_label')->nullable();
            $table->string('level_four_icon')->nullable();
            $table->string('level_four_label')->nullable();
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
        Schema::dropIfExists('challenges');
    }
}
