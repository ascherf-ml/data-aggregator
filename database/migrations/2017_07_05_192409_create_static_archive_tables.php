<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaticArchiveTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->integer('site_id')->unsigned()->unique()->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('web_url')->nullable();
            $table->timestamps();
        });

        Schema::create('artwork_site', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('artwork_citi_id')->index();
            $table->integer('site_site_id')->unsigned()->index();
        });

        Schema::create('exhibition_site', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('exhibition_citi_id')->index();
            $table->integer('site_site_id')->unsigned()->index();
        });

        Schema::create('agent_site', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_citi_id')->index();
            $table->integer('site_site_id')->unsigned()->index();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('artwork_site');
        Schema::dropIfExists('agent_site');
        Schema::dropIfExists('exhibition_site');
        Schema::dropIfExists('sites');

    }

}
