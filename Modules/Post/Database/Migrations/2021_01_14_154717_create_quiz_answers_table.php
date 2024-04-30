<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_question_id')->unsigned()->nullable();
            $table->bigInteger('image_id')->unsigned()->nullable();
            $table->string('answer_text')->nullable();
            $table->tinyInteger('is_correct')->default(0);
            $table->bigInteger('result_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('quiz_question_id')->references('id')->on('quiz_questions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')
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
        Schema::dropIfExists('quiz_answers');
    }
}
