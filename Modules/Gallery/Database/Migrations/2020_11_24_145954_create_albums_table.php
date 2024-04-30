<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('language');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tabs')->nullable();
            $table->integer('order')->default(0);
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('disk');
            $table->string('original_image')->nullable()->comment('cover image');
            $table->string('thumbnail')->nullable()->comment('cover image');
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
        Schema::dropIfExists('albums');
    }
}
