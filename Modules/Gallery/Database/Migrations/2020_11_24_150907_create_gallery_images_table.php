<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('disk');
            $table->bigInteger('album_id')->unsigned()->nullable();
            $table->string('tab')->nullable();
            $table->string('title')->nullable();
            $table->boolean('is_cover')->default(0)->comment('0 no, 1 yes');
            $table->string('original_image');
            $table->string('thumbnail');
            $table->timestamps();

            $table->foreign('album_id')->references('id')->on('albums')
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
        Schema::dropIfExists('gallery_images');
    }
}
