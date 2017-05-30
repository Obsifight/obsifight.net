<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWikiTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('wiki_categories', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 50);
        $table->timestamps();
      });
      Schema::create('wiki_articles', function (Blueprint $table) {
        $table->increments('id');
        $table->string('title', 150);
        $table->string('slug', 150);
        $table->text('content');
        $table->integer('category_id')->unsigned();
        $table->foreign('category_id')->references('id')->on('wiki_categories');
        $table->integer('version')->unsigned();
        $table->boolean('displayed')->default(0);
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
      Schema::dropIfExists('wiki_categories');
      Schema::dropIfExists('wiki_articles');
    }
}
