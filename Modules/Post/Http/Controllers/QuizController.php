<?php

namespace Modules\Post\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Post\Entities\Post;
use Modules\Post\Entities\QuizAnswer;
use Modules\Post\Entities\QuizQuestion;
use Modules\Post\Entities\QuizResult;
use Validator;
use Sentinel;

class QuizController extends Controller
{
    public function saveNewQuiz(Request $request,$type)
    {
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', __('You are not allowed to add/modify in demo mode.'));
        endif;
//        dd($request->all());

        Validator::make($request->all(), [
            'title'             => 'required|min:2|unique:posts',
            'language'          => 'required',
            'content'           => 'required',
            'category_id'       => 'required',
//            'slug'              => 'nullable|min:2|unique:posts|regex:/^\S*$/u',
        ])->validate();

        $post               =   new Post();
        $post->title        =   $request->title;

        if ($request->slug != null) :
            $post->slug = $request->slug;
        else :
            $post->slug = $this->make_slug($request->title);
        endif;

        $post->user_id      = Sentinel::getUser()->id;
        $post->content      = $request->content;

        $post->visibility   = $request->visibility;

        $post->layout       = $request->layout;
        $post->post_type    = $type;

        if(isset($request->featured)):
            $post->featured = 1;
        else :
            $post->featured = 0;
        endif;

        if(isset($request->breaking)):
            $post->breaking = 1;
        else :
            $post->breaking = 0;
        endif;

        if(isset($request->slider)):
            $post->slider   = 1;
        else :
            $post->slider   = 0;
        endif;

        if(isset($request->recommended)):
            $post->recommended  = 1;
        else :
            $post->recommended  = 0;
        endif;

        if(isset($request->editor_picks)):
            $post->editor_picks  = 1;
        else :
            $post->editor_picks  = 0;
        endif;

        if(isset($request->auth_required)):
            $post->auth_required  = 1;
        else :
            $post->auth_required  = 0;
        endif;

        $post->meta_title       = $request->meta_title;
        $post->meta_keywords    = $request->meta_keywords;
        $post->tags             = $request->tags;
        $post->meta_description = $request->meta_description;
        $post->language         = $request->language;
        $post->category_id      = $request->category_id;
        $post->sub_category_id  = $request->sub_category_id;
        $post->image_id         = $request->image_id;

        if($request->status == 2) :
            $post->status           = 0;
            $post->scheduled        = 1;
            $post->scheduled_date   = Carbon::parse($request->scheduled_date);
        else :
            $post->status           = $request->status;
        endif;

        if(isset($request->scheduled)):
            $post->scheduled=1;
        endif;

        $post->save();

        $last_post_id = $post->id;

        if($request->type == 'trivia-quiz'):
            $question_ids       = $request->question_id;
            $question_titles    = $request->question_title;
            $question_images    = $request->question_image;
            $question_contents  = $request->question_content;
            $answer_formats     = $request->answer_format;

            if (!empty($question_titles)):
                for ($i = 0; $i < count($question_titles); $i++):
                    $question = new QuizQuestion();

                    $question->post_id      = $last_post_id;
                    $question->question     = $question_titles[$i];
                    $question->image_id     = $question_images[$i];
                    $question->description  = $question_contents[$i];
                    $question->order        = $i + 1;
                    $question->answer_format = $answer_formats[$i];

                    $question->save();

                    $last_question_id = $question->id;

//                    dd($last_question_id);

                    $answer_unique_ids_question = $request['answer_unique_id_question_'.$question_ids[$i]];
                    $answer_images_question     = $request['answer_image_question_'.$question_ids[$i]];
                    $answer_texts_question      = $request['answer_text_question_'.$question_ids[$i]];

                    $selected_correct_answer    = $request['correct_answer_question_'.$question_ids[$i]];


                    if (!empty($answer_texts_question)) :
                        for ($j = 0; $j < count($answer_texts_question); $j++) :
                            $is_correct = 0;
                            //find correct answer
                            if ($answer_unique_ids_question[$j] == $selected_correct_answer):
                                $is_correct = 1;
                            endif;

                            $answer = new QuizAnswer();

                            $answer->quiz_question_id = $last_question_id;
                            $answer->image_id = $answer_images_question[$j];
                            $answer->answer_text = $answer_texts_question[$j];
                            $answer->is_correct = $is_correct;

                            $answer->save();
                        endfor;
                    endif;
                endfor;
            endif;

            $result_titles      = $request->result_title;
            $result_images      = $request->result_image;
            $result_contents    = $request->result_content;
            $min_correct_counts = $request->min_correct_count;
            $max_correct_counts = $request->max_correct_count;

            if (!empty($result_titles)):
                for ($i = 0; $i < count($result_titles); $i++):
                    $result = new QuizResult();

                    $result->post_id        = $last_post_id;
                    $result->image_id       = $result_images[$i];
                    $result->result_title   = $result_titles[$i];
                    $result->description    = $result_contents[$i];
                    $result->min_correct    = $min_correct_counts[$i];
                    $result->max_correct    = $max_correct_counts[$i];

                    $result->save();
                endfor;
            endif;
        endif;
        if($request->type == 'personality-quiz'):
            $question_ids       = $request->question_id;
            $question_titles    = $request->question_title;
            $question_images    = $request->question_image;
            $question_contents  = $request->question_content;
            $answer_formats     = $request->answer_format;

            $result_titles      = $request->result_title;
            $result_images      = $request->result_image;
            $result_contents    = $request->result_content;

            $results = collect();

            if (!empty($result_titles)):
                for ($i = 0; $i < count($result_titles); $i++):
                    $result = new QuizResult();

                    $result->post_id        = $last_post_id;
                    $result->image_id       = $result_images[$i];
                    $result->result_title   = $result_titles[$i];
                    $result->description    = $result_contents[$i];

                    $result->save();

                    $results->put($i + 1, $result->id);
                endfor;
            endif;

            if (!empty($question_titles)):
                for ($i = 0; $i < count($question_titles); $i++):
                    $question = new QuizQuestion();

                    $question->post_id      = $last_post_id;
                    $question->question     = $question_titles[$i];
                    $question->image_id     = $question_images[$i];
                    $question->description  = $question_contents[$i];
                    $question->order        = $i + 1;
                    $question->answer_format = $answer_formats[$i];

                    $question->save();

                    $last_question_id = $question->id;

                    $answer_unique_ids_question = $request['answer_unique_id_question_'.$question_ids[$i]];
                    $answer_images_question     = $request['answer_image_question_'.$question_ids[$i]];
                    $answer_texts_question      = $request['answer_text_question_'.$question_ids[$i]];

                    if (!empty($answer_texts_question)) :
                        for ($j = 0; $j < count($answer_texts_question); $j++) :
                            $selected_result_id = '';
                            $selected_result = '';
                            if ($request['selected_result_question_answer_'.$answer_unique_ids_question[$j]]):
                                $selected_result            = $request['selected_result_question_answer_'.$answer_unique_ids_question[$j]];
                            endif;
                            //find correct answer

                            $answer = new QuizAnswer();

                            $answer->quiz_question_id = $last_question_id;
                            $answer->image_id = $answer_images_question[$j];
                            $answer->answer_text = $answer_texts_question[$j];
                            $answer->result_id = $results[$selected_result] ??  $selected_result_id;

                            $answer->save();
                        endfor;
                    endif;
                endfor;
            endif;
        endif;

        Cache::Flush();

        return redirect()->back()->with('success',__('successfully_added'));
    }
    public function updateQuiz(Request $request, $type, $id){

        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', __('You are not allowed to add/modify in demo mode.'));
        endif;

        Validator::make($request->all(), [
            'title'             => 'required|min:2',
            'language'          => 'required',
            'content'           => 'required',
            'category_id'       => 'required',
            'slug'              => 'nullable|min:2|max:120|regex:/^\S*$/u|unique:posts,slug,' . $id,
        ])->validate();

        $post           = Post::findOrfail($id);
        $post->title    = $request->title;
        $post->content      = $request->content;

        if ($request->slug != null) :
            $post->slug = $request->slug;
        else :
            $post->slug = $this->make_slug($request->title);
        endif;

        $post->visibility   = $request->visibility;
        $post->layout       = $request->layout;

        if(isset($request->featured)):
            $post->featured = 1;
        else :
            $post->featured = 0;
        endif;

        if(isset($request->breaking)):
            $post->breaking = 1;
        else :
            $post->breaking = 0;
        endif;

        if(isset($request->slider)):
            $post->slider   = 1;
        else :
            $post->slider   = 0;
        endif;

        if(isset($request->recommended)):
            $post->recommended  = 1;
        else :
            $post->recommended  = 0;
        endif;

        if(isset($request->editor_picks)):
            $post->editor_picks  = 1;
        else :
            $post->editor_picks  = 0;
        endif;

        if(isset($request->auth_required)):
            $post->auth_required=1;
        else :
            $post->auth_required=0;
        endif;

        $post->meta_title       = $request->meta_title;
        $post->meta_keywords    = $request->meta_keywords;
        $post->tags             = $request->tags;
        $post->meta_description = $request->meta_description;
        $post->language         = $request->language;
        $post->category_id      = $request->category_id;
        $post->sub_category_id  = $request->sub_category_id;
        $post->image_id         = $request->image_id;

        if($request->status == 2) :
            $post->status   = 0;
            $post->scheduled= 1;
            $post->scheduled_date=Carbon::parse($request->scheduled_date);
        else :

            $post->status=$request->status;
        endif;

        if(isset($request->scheduled)):
            $post->scheduled=1;
        endif;

        $post->save();

        if($request->type == 'trivia-quiz'):
            $question_ids       = $request->question_id;

            if(!empty($question_ids)):

                for ($q = 0; $q< count($question_ids); $q++):

                if (!empty($request['question_title_'.$question_ids[$q]])):

                    $question = QuizQuestion::find($question_ids[$q] );
//                dd($question);

                    if(!empty($question)):
                        $question->post_id      = $id;
                        $question->question     = $request['question_title_'.$question_ids[$q]];
                        $question->image_id     = $request['question_image_'.$question_ids[$q]];
                        $question->description  = $request['question_content_'.$question_ids[$q]];
                        $question->order        = $q + 1;
                        $question->answer_format  = $request['answer_format_'.$question_ids[$q]];

                        $question->save();

                        $selected_correct_answer    = $request['correct_answer_question_'.$question_ids[$q]];

                        $answer_ids                 = $request['answer_unique_id_question_'.$question_ids[$q]];

                        if(!empty($answer_ids)):

                            for ($a = 0; $a< count($answer_ids); $a++):
                                $is_correct = 0;
                                //find correct answer
                                if ($answer_ids[$a] == $selected_correct_answer):
                                    $is_correct = 1;
                                endif;

                                $answer = QuizAnswer::find($answer_ids[$a]);

                                $answer->quiz_question_id = $question_ids[$q];
                                $answer->image_id = $request['answer_image_'.$answer_ids[$a]];
                                $answer->answer_text = $request['answer_text_question_'.$answer_ids[$a]];
                                $answer->is_correct = $is_correct;

                                $answer->save();
                            endfor;
                        endif;
                    endif;

                endif;
            endfor;

            endif;

            $result_ids       = $request->result;

            if(!empty($result_ids)):

                for ($r = 0; $r< count($result_ids); $r++):
                    if(isset($request['result_title_'.$result_ids[$r]])):
                        $result = QuizResult::find($result_ids[$r] );
    //                dd($question);

                        if(isset($result)):
                            $result->post_id            = $id;
                            $result->result_title       = $request['result_title_'.$result_ids[$r]];
                            $result->image_id           = $request['result_image_'.$result_ids[$r]];
                            $result->description        = $request['result_content_'.$result_ids[$r]];
                            $result->min_correct        = $request['min_correct_count_'.$result_ids[$r]];
                            $result->max_correct        = $request['max_correct_count_'.$result_ids[$r]];

                            $result->save();
                        endif;
                    endif;
                endfor;
            endif;
        endif;
        if($request->type == 'personality-quiz'):
            $question_ids       = $request->question_id;

            $result_ids       = $request->result;

            if(!empty($result_ids)):

                for ($r = 0; $r< count($result_ids); $r++):
                    if(isset($request['result_title_'.$result_ids[$r]])):
                        $result = QuizResult::find($result_ids[$r] );
                        //                dd($question);

                        if(isset($result)):
                            $result->post_id            = $id;
                            $result->result_title       = $request['result_title_'.$result_ids[$r]];
                            $result->image_id           = $request['result_image_'.$result_ids[$r]];
                            $result->description        = $request['result_content_'.$result_ids[$r]];
                            $result->save();
                        endif;
                    endif;
                endfor;
            endif;

            if(!empty($question_ids)):
                for ($q = 0; $q< count($question_ids); $q++):

                if (!empty($request['question_title_'.$question_ids[$q]])):

                    $question = QuizQuestion::find($question_ids[$q] );
//                dd($question);

                    if(!empty($question)):
                        $question->post_id      = $id;
                        $question->question     = $request['question_title_'.$question_ids[$q]];
                        $question->image_id     = $request['question_image_'.$question_ids[$q]];
                        $question->description  = $request['question_content_'.$question_ids[$q]];
                        $question->order        = $q + 1;
                        $question->answer_format  = $request['answer_format_'.$question_ids[$q]];

                        $question->save();

                        $answer_ids                 = $request['answer_unique_id_question_'.$question_ids[$q]];

                        if(!empty($answer_ids)):

                            for ($a = 0; $a< count($answer_ids); $a++):
                                $selected_result_id = '';
                                $selected_result = '';
                                if ($request['selected_result_question_answer_'.$answer_ids[$a]]):
                                    $selected_result            = $request['selected_result_question_answer_'.$answer_ids[$a]];
                                endif;

                                $answer = QuizAnswer::find($answer_ids[$a]);

                                $answer->quiz_question_id = $question_ids[$q];
                                $answer->image_id = $request['answer_image_'.$answer_ids[$a]];
                                $answer->answer_text = $request['answer_text_question_'.$answer_ids[$a]];
                                $answer->result_id = $selected_result ??  $selected_result_id;

                                $answer->save();
                            endfor;
                        endif;
                    endif;

                endif;
            endfor;
            endif;

        endif;

        Cache::Flush();

        return redirect()->back()->with('success',__('successfully_updated'));
    }

    public function addTriviaQuizQuestion(Request $request){
        $post_type = $request->post_type;
        $question_number = $request->question_number;
        return view("post::contents.trivia-quiz-question", compact('post_type','question_number'));
    }
    public function addTriviaQuizResult(Request $request){
        $post_type = $request->post_type;
        $result_number = $request->result_number;
        return view("post::contents.quiz-result", compact('post_type','result_number'));
    }

    public function addTriviaQuizAnswer(Request $request){
        $unique_id          = $request->question_id;
        $post_type = $request->post_type;

        return view("post::contents.trivia-quiz-answer", compact('unique_id', 'post_type'));
    }

    public function addTriviaQuizQuestionToDB(Request $request){
        $post_type  = $request->post_type;
        $post_id    = $request->post_id;
        $question_number = $request->question_number;


        $quiz_question = new QuizQuestion();
        $quiz_question->post_id = $post_id;

        $post = Post::find($post_id);

        $quiz_question->save();

        for ($i=0; $i<3; $i++):
            $new_answer = new QuizAnswer();
            $new_answer->quiz_question_id =  $quiz_question->id;
            $new_answer->save();
        endfor;

        return view("post::contents.add-question-to-db", compact('post_type','post','question_number','post_id' ,'quiz_question'));
    }

    public function addTriviaQuizResultToDB(Request $request){

//        dd($request->all());
        $post_type          = $request->post_type;
        $result_number      = $request->result_number;
        $post_id            = $request->post_id;

        $result = new QuizResult();
        $result->post_id = $post_id;
        $result->save();

        $quiz_result = $result;
//        dd($quiz_result);

        return view("post::contents.add-result-to-db", compact('post_type','result_number','quiz_result'));
    }

    public function addTriviaQuizAnswerToDB(Request $request){

        $unique_id      = $request->question_id;
        $post_type      = $request->post_type;

        $quiz_question  = QuizQuestion::find($unique_id);

        $quiz_question_answer = new QuizAnswer();

        $quiz_question_answer->quiz_question_id = $unique_id;

        $quiz_question_answer->save();

//        dd($quiz_question_answer);

        return view("post::contents.add-answer-to-db", compact('quiz_question', 'post_type','quiz_question_answer'));
    }

    private function make_slug($string, $delimiter = '-') {

        $string = preg_replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", "", $string);

        $string = preg_replace("/[\/_|+ -]+/", $delimiter, $string);
        $result = mb_strtolower($string);

        if ($result):
            return $result;
        else:
            return $string;
        endif;
    }
}
