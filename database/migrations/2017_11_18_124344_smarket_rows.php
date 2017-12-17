<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SmarketRows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smarket_rows', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event')->nullable();
            $table->string('details')->nullable();
            $table->string('date')->nullable();
            $table->string('backers_stake')->nullable();
            $table->string('odds')->nullable();
            $table->string('exposure')->nullable();
            $table->string('in_out')->nullable();
            $table->string('balance')->nullable();

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
        Schema::dropIfExists('smarket_rows');
    }
}
