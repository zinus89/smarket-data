<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SmarketBetMatchTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('smarket_row_id');
            $table->integer('match_id');
            $table->integer('bet_type_id')->nullable();
            $table->decimal('in_out');
            $table->boolean('won');
            $table->timestamp('date')->nullable();
            $table->timestamps();
        });
        Schema::create('bets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('smarket_row_id');
            $table->integer('market_id');
            $table->decimal('backers_stake');
            $table->timestamp('date')->nullable()	;
            $table->decimal('odds');
            $table->timestamps();
        });
        Schema::create('bet_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('lay');
            $table->timestamps();
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('country')->nullable();
            $table->timestamps();
        });

        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('home_team_id');
            $table->integer('away_team_id');
            $table->integer('competition_id')->nullable();
            $table->timestamp('date')->nullable();
            $table->timestamps();
        });

        Schema::create('competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('location');
            $table->string('season');
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
        Schema::dropIfExists('markets');
        Schema::dropIfExists('bets');
        Schema::dropIfExists('bet_types');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('matches');
        Schema::dropIfExists('competitions');
    }
}
