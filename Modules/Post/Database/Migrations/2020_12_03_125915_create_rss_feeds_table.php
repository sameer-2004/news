<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRssFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rss_feeds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('feed_url');
            $table->string('language');
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->bigInteger('sub_category_id')->unsigned()->nullable();
            $table->smallInteger('post_limit');
            $table->boolean('auto_update')->default(0);
            $table->boolean('show_read_more')->default(0);
            $table->boolean('status')->default(0);
            $table->boolean('keep_date')->default(0);

            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('tags')->nullable();
            $table->timestamp('scheduled_date')->nullable();
            $table->string('layout')->default('default');

            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')
                ->onUpdate('cascade')->onDelete('set null');

            $table->foreign('sub_category_id')->references('id')->on('sub_categories')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rss_feeds');
    }
}
