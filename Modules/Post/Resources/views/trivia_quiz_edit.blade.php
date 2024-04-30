@extends('common::layouts.master')
@section('post-aria-expanded')
    aria-expanded="true"
@endsection
@section('post-show')
    show
@endsection
@section('post')
    active
@endsection
@section('create_trivia_quiz')
    active
@endsection
@section('modal')
    @include('gallery::image-gallery')
    @include('gallery::video-gallery')
    {{-- @include('gallery::image-gallery-content') --}}
@endsection

@section('content')

    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <!-- page info start-->
            {!!  Form::open(['route'=>['update-quiz','trivia-quiz',$post->id], 'method' => 'post','enctype'=>'multipart/form-data']) !!}
            <input type="hidden" id="images" value="{{ $countImage }}">
            <input type="hidden" id="videos" value="{{ $countVideo }}">
            <input type="hidden" id="imageCount" value="1" class="imageCount">
            <input type="hidden" id="videoCount" value="1">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="add-new-header clearfix m-b-20">
                        <div class="row">
                            <div class="col-6">
                                <div class="block-header">
                                    <h2>{{ __('update_post') }}</h2>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('post') }}" class="btn btn-primary btn-add-new"><i
                                        class="fas fa-list"></i> {{ __('posts') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if(session('error'))
                                <div id="error_m" class="alert alert-danger">
                                    {{session('error')}}
                                </div>
                            @endif
                            @if(session('success'))
                                <div id="success_m" class="alert alert-success">
                                    {{session('success')}}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Main Content section start -->
                        <div class="col-12 col-lg-9">
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('posts_details') }}</h2>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post_title" class="col-form-label">{{ __('title') }} *</label>
                                        <input id="post_title" onkeyup="metaTitleSet()" name="title"
                                               value="{{ $post->title }}" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post-slug" class="col-form-label"><b>{{ __('slug') }}</b>
                                            ({{ __('slug_message') }})</label>
                                        <input id="post-slug" name="slug" value="{{ $post->slug }}" type="text"
                                               class="form-control">
                                    </div>
                                </div>
                                <!-- tinemcey start -->
                                <div class="row p-l-15">
                                    <div class="col-12">
                                        <label for="post_content" class="col-form-label">{{ __('content') }}*</label>
                                        <textarea name="content" value="{{ $post->content }}" id="post_content"
                                                  cols="30" rows="5">
                                                        {!! $post->content !!}
                                                    </textarea>
                                    </div>
                                </div>
                                <!-- tinemcey end -->
                            </div>

                            {{--                            question section start--}}
                            <h3 class="block-header">{{ __('questions') }} </h3>
                            <div class="content-area">
                                <div class="clearfix"></div>
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div id="quiz_questions_container" class="quiz_questions_container">

                                        @foreach($quiz_questions as $value => $quiz_question)
                                            @php
                                                $answer_format_class = "";
                                                if ($quiz_question->answer_format == 'large_image') {
                                                    $answer_format_class = "quiz-answers-format-large-image";
                                                } elseif ($quiz_question->answer_format == 'text') {
                                                    $answer_format_class = "quiz-answers-format-text";
                                                }
                                            @endphp
                                            <div id="panel_quiz_question_{{ $quiz_question->id }}"
                                                 class="panel panel-default panel-quiz-question"
                                                 data-question-id="{{ $quiz_question->id }}" data-quiz-type="trivia-quiz">
                                                <div class="panel panel-default mt-2 bg-white">
                                                    <div class="panel-heading" role="tab" id="headingOne">
                                                        <h4 class="panel-title d-flex">
                                                            <a role="button" data-toggle="collapse"
                                                               data-parent="#accordion"
                                                               href="#collapse_{{ $quiz_question->id }}" aria-expanded="false"
                                                               aria-controls="collapse_{{ $quiz_question->id }}">
                                                                #<span id="question_number_{{ $quiz_question->id }}">{{ $value+1 }}</span>
                                                                <span id="question_{{ $quiz_question->id }}">{{ $quiz_question->question }}</span>
                                                            </a>
                                                            <div class="text-right">
                                                                <button type="button" class="btn btn-default"
                                                                        onclick="delete_item('quiz_questions','{{ $quiz_question->id }}')">
                                                                    <i class="fa fa-trash"></i></button>
                                                            </div>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse_{{ $quiz_question->id }}"
                                                         class="panel-collapse collapse"
                                                         role="tabpanel" aria-labelledby="heading_{{ $quiz_question->id }}"
                                                         style="">
                                                        <div class="panel-body bg-white">

                                                            <div class="col-12 col-lg-12">
                                                                <div class="col-sm-12 pr-0">
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="question"><b>{{ __('question') }} *</b></label>
                                                                        <input name="question_id[]" type="hidden"
                                                                               value="{{ $quiz_question->id }}">
                                                                        <input type="text" id="question_input" required
                                                                               class="form-control input-question-text"
                                                                               data-question-id="{{ $quiz_question->id }}"
                                                                               name="question_title_{{ $quiz_question->id }}"
                                                                               placeholder="{{ __('question') }}"
                                                                               value="{{ $quiz_question->question }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-lg-12">
                                                                <div class="row">
                                                                    <div class="col-sm-3">
                                                                        <div class="question-image">
                                                                            <div class="form-group">
                                                                                <!-- Large modal -->
                                                                                <button type="button" id="btn_image_modal"
                                                                                        class="btn btn-primary btn-image-modal ml-3"
                                                                                        data-id="1" data-toggle="modal"
                                                                                        data-question-id="{{ $quiz_question->id }}"
                                                                                        data-target=".image-modal-lg">{{ __('add_image') }}</button>
                                                                                <input
                                                                                    id="image_id_question_{{ $quiz_question->id }}"
                                                                                    name="question_image_{{ $quiz_question->id }}"
                                                                                    type="hidden"  value="{{ $quiz_question->image_id }}"
                                                                                    class="form-control image_id">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <div class="form-group text-center">

                                                                                    @if(isFileExist($quiz_question->image, $result = @$quiz_question->image->thumbnail))
                                                                                        <img src=" {{basePath($quiz_question->image)}}/{{ $result }} "
                                                                                             id="image_preview_question_{{ $quiz_question->id }}" width="200" height="200"
                                                                                             class="img-responsive img-thumbnail image_preview"
                                                                                             alt="{!! $quiz_question->question !!}">
                                                                                    @else
                                                                                        <img
                                                                                            src="{{static_asset('default-image/default-100x100.png') }} "
                                                                                            id="image_preview_question_{{ $quiz_question->id }}"
                                                                                            width="200" height="200" alt="{!! $quiz_question->question !!}"
                                                                                            class="img-responsive img-thumbnail image_preview">
                                                                                    @endif


                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-9">
                                                                        <div class="form-group">
                                                                            <label for="question_content"
                                                                                   class="col-form-label pt-0">{{ __('content') }}
                                                                                </label>
                                                                            <textarea name="question_content_{{ $quiz_question->id }}"
                                                                                      class="question-content"
                                                                                      id="question_content">{{ $quiz_question->description }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <div class="row">
                                                                            <div class="col-sm-12 m-b-15">
                                                                                <label
                                                                                    class="control-label">{{ __('answers') }}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="quiz-answers {{ $answer_format_class }}">
                                                                            <div class="row">
                                                                                <div
                                                                                    class="col-sm-12 btn-group-answer-formats-container">
                                                                                    <input type="hidden" required
                                                                                           name="answer_format_{{ $quiz_question->id }}"
                                                                                           id="input_answer_format_{{ $quiz_question->id }}"
                                                                                           value="{{ $quiz_question->answer_format }}">
                                                                                    <span
                                                                                        class="span-answer-format">{{ __('answer_format') }}</span>

                                                                                    <div
                                                                                        class="btn-group btn-group-answer-formats"
                                                                                        role="group">
                                                                                        <button type="button"
                                                                                                class="btn btn-default btn_{{ $quiz_question->id }} {{ $quiz_question->answer_format == 'small_image' ? 'active' : ''}}"
                                                                                                data-answer-format="small_image"
                                                                                                data-question-id="{{ $quiz_question->id }}">
                                                                                            <i class="fa fa-th"></i>
                                                                                        </button>
                                                                                        <button type="button"
                                                                                                class="btn btn-default btn_{{ $quiz_question->id }}"  {{ $quiz_question->answer_format == 'large_image' ? 'active' : ''}}
                                                                                                data-answer-format="large_image"
                                                                                                data-question-id="{{ $quiz_question->id }}">
                                                                                            <i class="fa fa-th-large"></i>
                                                                                        </button>
                                                                                        <button type="button"
                                                                                                class="btn btn-default btn_{{ $quiz_question->id }}" {{ $quiz_question->answer_format == 'text' ? 'active' : ''}}
                                                                                                data-answer-format="text"
                                                                                                data-question-id="{{ $quiz_question->id }}">
                                                                                            <i class="fa fa-th-list"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                id="quiz_answers_container_question_{{ $quiz_question->id }}"
                                                                                class="row row-answer">

                                                                                @foreach($quiz_question->quizAnswers as $quiz_question_answer)
                                                                                <div
                                                                                    id="quiz_answer_{{ $quiz_question_answer->id }}"
                                                                                    class="answer">
                                                                                    <div class="answer-inner">
                                                                                        <a href="javascript:void(0)"
                                                                                           class="btn-delete-answer" onclick="delete_item('quiz_answers','{{ $quiz_question_answer->id }}')">
                                                                                            <i
                                                                                                class="fa fa-times"></i></a>
                                                                                        <div
                                                                                            class="form-group quiz-answer-image-item-text m-b-0">
                                                                                            <input type="hidden"
                                                                                                   name="answer_unique_id_question_{{ $quiz_question->id }}[]"
                                                                                                   value="{{ $quiz_question_answer->id }}">
                                                                                            <div
                                                                                                id="quiz_answer_image_container_answer_{{ $quiz_question_answer->id }}"
                                                                                                class="quiz-answer-image-item">
                                                                                                <div
                                                                                                    class="quiz-answer-image-container" data-question-id="{{ $quiz_question->id }}"
                                                                                                    data-answer-id="{{ $quiz_question_answer->id }}">

                                                                                                    @if (!empty($quiz_question_answer->image_id))
                                                                                                    <input type="hidden" name="answer_image_{{ $quiz_question_answer->id }}"
                                                                                                           value="{{ $quiz_question_answer->image_id }}" class="image_id">
                                                                                                        @if(isFileExist($quiz_question_answer->image, $result = @$quiz_question_answer->image->thumbnail))
                                                                                                            <img src=" {{basePath($quiz_question_answer->image)}}/{{ $result }} " class="image_preview">
                                                                                                        @else
                                                                                                            <img src="{{static_asset('default-image/default-100x100.png') }} " class="image_preview">
                                                                                                        @endif
                                                                                                    <a class="btn btn-danger btn-sm btn-delete-selected-image delete-selected-quiz-answer-image"
                                                                                                       data-question-id="<?php echo $quiz_question_answer->quiz_question_id; ?>"
                                                                                                       data-answer-id="<?php echo $quiz_question_answer->id; ?>" data-is-update="1">
                                                                                                        <i class="fa fa-times"></i>
                                                                                                    </a>
                                                                                                    @else
                                                                                                    <input type="hidden" name="answer_image_{{ $quiz_question_answer->id }}" value="" class="image_id">
                                                                                                        <a class="btn-select-image btn-image-modal"
                                                                                                           id="btn_image_modal"
                                                                                                           data-toggle="modal"
                                                                                                           data-target=".image-modal-lg"
                                                                                                           data-id="1"
                                                                                                           data-quiz-image-type="answer"
                                                                                                           data-question-id="{{ $quiz_question->id }}"
                                                                                                           data-answer-id="{{ $quiz_question_answer->id }}"
                                                                                                           data-is-update="1">
                                                                                                            <div
                                                                                                                class="btn-select-image-inner">
                                                                                                                <i class="icon-images fa fa-picture-o"></i>
                                                                                                                <button
                                                                                                                    class="btn">{{ __('select_image') }}</button>
                                                                                                            </div>
                                                                                                    </a>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                        <textarea
                                                                                            name="answer_text_question_{{ $quiz_question_answer->id }}"
                                                                                            class="form-control answer-text" required
                                                                                            placeholder="{{ __('answer_text') }}">{{ $quiz_question_answer->answer_text }}</textarea>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <div
                                                                                                class="answer-radio-container">
                                                                                                <label
                                                                                                    class="custom-control custom-radio custom-control-inline">
                                                                                                    <input type="radio" required
                                                                                                           name="correct_answer_question_{{ $quiz_question->id }}"
                                                                                                           id="radio_answer_{{ $quiz_question_answer->id }}"
                                                                                                           value="{{ $quiz_question_answer->id }}"
                                                                                                           class="custom-control-input" {{ $quiz_question_answer->is_correct ? 'checked' : '' }}
                                                                                                           data-parsley-multiple="correct">
                                                                                                    <span
                                                                                                        class="custom-control-label">{{__('correct')}}</span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                @endforeach

                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-sm-12 text-center">
                                                                                    <button type="button"
                                                                                            id="btn_add_quiz_answer_to_db"
                                                                                            class="btn-add-quiz-answer btn btn-add-answer btn-add-answer-to-db"
                                                                                            data-question-id="{{ $quiz_question->id }}"
                                                                                            data-question-type="trivia_quiz">
                                                                                        <i
                                                                                            class="fa fa-plus"></i>{{ __('add_answer') }}
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="row m-3">
                                        <div class="col-sm-12 text-center">
                                            <button type="button" id="btn_add_quiz_question_to_db" data-post-id="{{ $post->id }}"
                                                    data-question-number="{{ $quiz_questions->count() }}"
                                                    class="btn btn-md btn-primary btn-add-post-item"><i
                                                    class="fa fa-plus"></i>{{__('add_question')}}</button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{--                            question section end--}}

                            {{--                            result section start--}}

                            <h3 class="block-header">{{ __('results') }} </h3>
                            <div class="content-area">
                                <div class="clearfix"></div>
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div id="quiz_result_container" class="quiz_result_container">

                                        @foreach($post->quizResults as $key => $quiz_result)
                                        <div id="panel_quiz_result_{{ $quiz_result->id }}"
                                             class="panel panel-default panel-quiz-result"
                                             data-result-id="{{ $quiz_result->id }}">
                                            <div class="panel panel-default mt-2 bg-white">
                                                <div class="panel-heading" role="tab"
                                                     id="heading_{{ $quiz_result->id }}">
                                                    <h4 class="panel-title d-flex">
                                                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                                                           href="#collapse_result_{{ $quiz_result->id }}" aria-expanded="false"
                                                           aria-controls="collapse_result_{{ $quiz_result->id }}">
                                                            #<span id="result_number_{{ $quiz_result->id }}">{{ $key+1 }}</span>
                                                            <span id="result_{{ $quiz_result->id }}">{{ $quiz_result->result_title }}</span>
                                                        </a>
                                                        <div class="text-right">
                                                            <button type="button" class="btn btn-default"
                                                                    onclick="delete_item('quiz_results','{{ $quiz_result->id }}')">
                                                                <i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </h4>
                                                </div>
                                                <div id="collapse_result_{{ $quiz_result->id }}"
                                                     class="panel-collapse collapse"
                                                     role="tabpanel" aria-labelledby="heading_{{ $quiz_result->id }}"
                                                     style="">
                                                    <div class="panel-body bg-white">

                                                        <div class="col-12 col-lg-12">
                                                            <div class="col-sm-12 pr-0">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="result_input"><b>{{ __('result') }} *</b></label>
                                                                    <input hidden name="result[]" value="{{ $quiz_result->id }}">
                                                                    <input type="text" id="result_input" required
                                                                           class="form-control input-result-text"
                                                                           data-result-id="{{ $quiz_result->id }}"
                                                                           name="result_title_{{ $quiz_result->id }}"
                                                                           placeholder="{{ __('result') }}" value="{{ $quiz_result->result_title }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-lg-12">
                                                            <div class="row">
                                                                <div class="col-sm-3 ">
                                                                    <div class="result-image">
                                                                        <div class="form-group">
                                                                            <!-- Large modal -->
                                                                            <button type="button" id="btn_image_modal"
                                                                                    class="btn btn-primary btn-image-modal ml-3"
                                                                                    data-id="1" data-toggle="modal"
                                                                                    data-result-id="{{ $quiz_result->id }}"
                                                                                    data-target=".image-modal-lg">{{ __('add_image') }}</button>
                                                                            <input
                                                                                id="image_id_result_{{$quiz_result->id}}"
                                                                                name="result_image_{{ $quiz_result->id }}"
                                                                                type="hidden" value="{{ $quiz_result->image_id }}"
                                                                                class="form-control image_id">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="form-group text-center">
                                                                                @if(isFileExist($quiz_result->image, $result = @$quiz_result->image->thumbnail))
                                                                                    <img src=" {{basePath($quiz_result->image)}}/{{ $result }} "
                                                                                         id="image_preview_result_{{ $quiz_result->id }}" width="200" height="200"
                                                                                         class="img-responsive img-thumbnail image_preview"
                                                                                         alt="{!! $quiz_result->result_title !!}">
                                                                                @else
                                                                                    <img
                                                                                        src="{{static_asset('default-image/default-100x100.png') }} "
                                                                                        id="image_preview_result_{{ $quiz_result->id }}"
                                                                                        width="200" height="200" alt="{!! $quiz_result->result_title !!}"
                                                                                        class="img-responsive img-thumbnail image_preview">
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-9">
                                                                    <div class="form-group">
                                                                        <label for="result_content"
                                                                               class="col-form-label pt-0">{{ __('content') }}</label>
                                                                        <textarea name="result_content_{{ $quiz_result->id }}"
                                                                                  class="result-content"
                                                                                  id="result_content">{{ $quiz_result->description }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <div class="row mb-3">
                                                                        <div class="col-sm-12">
                                                                            <label class="control-label">
                                                                                {{ __('range_of_correct_ans_for_showing_this_result') }}
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-sm-6 form-group">
                                                                            <input type="number" required min="0"
                                                                                   class="form-control input-question-text"
                                                                                   data-result-id="result_{{$quiz_result->id}}"
                                                                                   name="min_correct_count_{{ $quiz_result->id }}"
                                                                                   placeholder="{{__('minimum_correct')}}"
                                                                                   value="{{ $quiz_result->min_correct }}">
                                                                        </div>
                                                                        <div class="col-sm-6 form-group">
                                                                            <input type="number" required min="0"
                                                                                   class="form-control input-question-text"
                                                                                   data-result-id="result_{{$quiz_result->id}}"
                                                                                   name="max_correct_count_{{ $quiz_result->id }}"
                                                                                   placeholder="{{__('maximum_correct')}}"
                                                                                   value="{{ $quiz_result->max_correct }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="row m-3">
                                        <div class="col-sm-12 text-center">
                                            <button type="button" id="btn_add_quiz_result_to_db" data-post-id="{{ $post->id }}"
                                                    data-result-number="{{ $post->quizResults->count() }}"
                                                    class="btn btn-md btn-primary btn-add-post-item"><i
                                                    class="fa fa-plus"></i>{{__('add_result')}}</button>
                                        </div>
                                    </div>

                                </div>
                            </div>


                        {{--                            result section end--}}

                        <!-- SEO section start -->
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('seo_details') }}</h2>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="meta_title"><b>{{ __('title') }}</b> ({{ __('meta_title') }})</label>
                                        <input class="form-control meta" value="{{ $post->meta_title ?? $post->title }}"
                                               id="meta_title" name="meta_title" data-type="title">
                                        <p class="display-nothing alert alert-danger mt-2" role="alert">
                                            {{__('current_characters')}}: <span class="characters"></span>, {{ __('meta_title').' '. __('should_bd') .' '. __('in_between') .' '. '30-60 ' . __('characters') }}
                                        </p>
                                        <p class="display-nothing alert alert-success mt-2" role="alert">
                                            {{__('current_characters')}}: <span class="characters"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post-keywords" class="col-form-label"><b>{{ __('keywords') }}</b>
                                            </label>
                                        <input id="post-keywords" name="meta_keywords"
                                               value="{{ $post->meta_keywords }}" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post_tags" class="col-form-label">{{ __('tags') }}({{ __('meta_tag') }})</label>
                                        <input id="post_tags" name="tags" type="text" value="{{ $post->tags }}"
                                               data-role="tagsinput" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post_desc"><b>{{ __('description') }}</b> ({{ __('meta_description') }}
                                            )</label>
                                        <textarea class="form-control meta" id="meta_description" name="meta_description" data-type="description"
                                                  rows="3">{{ $post->meta_description }}</textarea>
                                        <p class="display-nothing alert alert-danger mt-2" role="alert">
                                            {{__('current_characters')}}: <span class="characters"></span>, {{ __('meta_title').' '. __('should_bd') .' '. __('in_between') .' '. '30-60 ' . __('characters') }}
                                        </p>
                                        <p class="display-nothing alert alert-success mt-2" role="alert">
                                            {{__('current_characters')}}: <span class="characters"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- SEO section end -->
                            <!-- visibility section start -->
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('visibility') }}</h2>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-12 col-md-4">
                                        <div class="form-title">
                                            <label for="visibility">{{ __('visibility') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" @if($post->visibility==1) checked
                                                   @endif name="visibility" id="visibility_show" value="1"
                                                   class="custom-control-input">
                                            <span class="custom-control-label">{{ __('show') }}</span>
                                        </label>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" @if($post->visibility==0) checked
                                                   @endif name="visibility" id="visibility_hide" value="0"
                                                   class="custom-control-input">
                                            <span class="custom-control-label">{{ __('hide') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-8 col-md-4">
                                        <div class="form-title">
                                            <label for="featured_post">{{ __('add_to_featured') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" @if($post->featured==1) checked
                                                   @endif id="featured_post" name="featured"
                                                   class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-8 col-md-4">
                                        <div class="form-title">
                                            <label for="add_to_breaking">{{ __('add_to_breaking') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" id="add_to_breaking" @if($post->breaking==1) checked
                                                   @endif name="breaking" class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-8 col-md-4">
                                        <div class="form-title">
                                            <label for="add_to_slide">{{ __('add_to_slider') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" id="add_to_slide" @if($post->slider==1) checked
                                                   @endif name="slider" class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-8 col-md-4">
                                        <div class="form-title">
                                            <label for="recommended">{{ __('add_to_recommended') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" id="recommended" @if($post->recommended==1) checked
                                                   @endif name="recommended" class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-8 col-md-4">
                                        <div class="form-title">
                                            <label for="editor_picks">{{ __('add_to_editor_picks') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" id="editor_picks" name="editor_picks"
                                                   class="custom-control-input" @if($post->editor_picks==1) checked
                                                @endif>
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-8 col-md-4">
                                        <div class="form-title">
                                            <label
                                                for="auth_required">{{ __('show_only_to_authenticate_users') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" id="auth_required"
                                                   @if($post->auth_required==1) checked @endif name="auth_required"
                                                   class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- visibility section end -->

                        </div>
                        <!-- Main Content section end -->

                        <!-- right sidebar start -->
                        <div class="col-12 col-lg-3">
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('image') }}</h2>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <!-- Large modal -->
                                        <button type="button" id="btn_image_modal"
                                                class="btn btn-primary btn-image-modal" data-id="1" data-toggle="modal"
                                                data-target=".image-modal-lg">{{ __('add_image') }}</button>
                                        <input id="image_id" value="{{ $post->image_id }}" name="image_id" type="hidden"
                                               class="form-control image_id">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="form-group text-center">
                                            @if(isFileExist($post->image, $result = @$post->image->thumbnail))
                                                <img src=" {{basePath($post->image)}}/{{ $result }} "
                                                     id="image_preview" width="200" height="200"
                                                     class="img-responsive img-thumbnail image_preview"
                                                     alt="{!! $post->title !!}">
                                            @else
                                                <img src="{{static_asset('default-image/default-100x100.png') }} "
                                                     id="image_preview"
                                                     width="200" height="200"
                                                     class="img-responsive img-thumbnail image_preview"
                                                     alt="{!! $post->title !!}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post_language">{{ __('select_language') }}*</label>
                                        <select class="form-control dynamic-category" id="post_language" name="language"
                                                data-dependent="category_id">
                                            @foreach ($activeLang as  $lang)
                                                <option
                                                    @if($post->language==$lang->code) Selected
                                                    @endif value="{{ $lang->code }}">{{ $lang->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="category_id">{{ __('category') }}*</label>
                                        <select class="form-control dynamic" id="category_id" name="category_id"
                                                data-dependent="sub_category_id" required>
                                            <option value="">{{ __('select_category') }}</option>
                                            @foreach ($categories as $category)
                                                <option @if($post->category_id == $category->id) Selected
                                                        @endif value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="sub_category_id">{{ __('sub_category') }}</label>
                                        <select class="form-control dynamic" id="sub_category_id"
                                                name="sub_category_id">
                                            <option value="">{{ __('select_sub_category') }}</option>
                                            @foreach ($subCategories as $subCategory)
                                                <option @if($post->sub_category_id == $subCategory->id) Selected
                                                        @endif value="{{ $subCategory->id }}">{{ $subCategory->sub_category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="col-md-12">
                                    <div class="block-header">
                                        <h2>{{ __('article_detail') }}</h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-control custom-radio detail-control-inline">
                                                {{--                                            {{(data_get($activeTheme, 'options.detail_style') == "style_1"? 'checked':'')}}--}}
                                                <input type="radio" name="layout" id="detail_style_1" value="default"
                                                       {{@$post->layout=="default" ? 'checked': ''}}  class="custom-control-input">
                                                <span class="custom-control-label"></span>
                                            </label>
                                            <img src="{{static_asset('default-image/Detail/detail_1.png') }}" alt=""
                                                 class="img-responsive cat-block-img">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-control custom-radio detail-control-inline">
                                                <input type="radio" name="layout" id="detail_style_2" value="style_2"
                                                       {{@$post->layout=="style_2" ? 'checked': ''}}  class="custom-control-input">
                                                <span class="custom-control-label"></span>
                                            </label>
                                            <img src="{{static_asset('default-image/Detail/detail_2.png') }}" alt=""
                                                 class="img-responsive cat-block-img">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-control custom-radio detail-control-inline">
                                                <input type="radio" name="layout" id="detail_style_3" value="style_3"
                                                       {{@$post->layout=="style_3" ? 'checked': ''}}  class="custom-control-input">
                                                <span class="custom-control-label"></span>
                                            </label>
                                            <img src="{{static_asset('default-image/Detail/detail_3.png') }}" alt=""
                                                 class="img-responsive cat-block-img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('publish') }}*</h2>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control" id="post_status" name="status" required>
                                            <option @if($post->status==1 && $post->scheduled==0) selected
                                                    @endif value="1">{{ __('published') }}</option>
                                            <option @if($post->status==0 && $post->scheduled==0) selected
                                                    @endif value="0">{{ __('draft') }}</option>
                                            <option @if($post->status==0 && $post->scheduled==1) selected
                                                    @endif value="2">{{ __('scheduled') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 divScheduleDate"
                                     @if($post->post_status==0 && $post->scheduled==1) @else id="display-nothing" @endif>
                                    <label for="scheduled_date">{{ __('schedule_date') }}</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="scheduled_date"><i
                                                class="fa fa-calendar-alt"></i></label>
                                        <input type="text" class="form-control date" id="scheduled_date"
                                               value="{{ Carbon\Carbon::parse($post->scheduled_date)->translatedFormat('m/d/Y g:i A') }}"
                                               name="scheduled_date"/>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="custom-control" for="btnSubmit"></label>
                                        <button type="submit" name="btnSubmit" class="btn btn-primary pull-right"><i
                                                class="m-r-10 mdi mdi-content-save-all"></i>{{ __('update_post') }}
                                        </button>
                                        <label class="" for="btnSubmit"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- right sidebar end -->
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        <!-- page info end-->
        </div>
    </div>


    <input type="hidden" value="0" id="content_number">

@endsection

@section('script')

    <script>
        $(document).ready(function () {

            $('.dynamic-category').change(function () {
                if ($(this).val() != '') {
                    var select = $(this).attr("id");
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        url: "{{ route('category-fetch') }}",
                        method: "POST",
                        data: {select: select, value: value, _token: _token},
                        success: function (result) {
                            $('#' + dependent).html(result);
                        }

                    })
                }
            });

            $('#post_language').change(function () {
                $('#category_id').val('');
                $('#sub_category_id').val('');
            });

            $('.dynamic').change(function () {
                if ($(this).val() != '') {
                    var select = $(this).attr("id");
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        url: "{{ route('subcategory-fetch') }}",
                        method: "POST",
                        data: {select: select, value: value, _token: _token},
                        success: function (result) {
                            $('#' + dependent).html(result);
                        }

                    })
                }
            });


            $('#category').change(function () {
                $('#sub_category_id').val('');
            });
        });
    </script>
    <script type="text/javascript" src="{{static_asset('js/post.js') }}"></script>
    <script type="text/javascript" src="{{static_asset('js/tagsinput.js') }}"></script>
    <script>
        addContent = function (value) {

            var content_number = $("#content_number").val();
            content_number++;

            $.ajax({
                url: "{{ route('add-content') }}",
                method: "GET",
                data: {value: value, content_count: content_number},
                success: function (result) {
                    $('.content-area').append(result);
                    $("#content_number").val(content_number);

                    // auto scrolling to newly added element
                    var newlyAdded = 'content_' + content_number;
                    $('body, html').animate({scrollTop: $('.' + newlyAdded).offset().top}, 1000);

                }

            });
        }

        $(document).on("click", ".add-new-page .row_remove", function () {
            let element = $(this).parents('.add-new-page');
            //element.remove(1000);
            element.hide("slow", function () {
                $(this).remove();
            })
        });
    </script>

    <script>
        $(document).ready(function () {

            tinymce.init({
                selector: "textarea#question_content",
                theme: "modern",
                height: 130,
                plugins: [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools'
                ],
                toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                image_advtab: true
            });

            tinymce.init({
                selector: "textarea#result_content",
                theme: "modern",
                height: 130,
                plugins: [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools'
                ],
                toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                image_advtab: true
            });

            $(".toggle-accordion").on("click", function () {
                var accordionId = $(this).attr("accordion-id"),
                    numPanelOpen = $(accordionId + ' .collapse.in').length;

                $(this).toggleClass("active");

                if (numPanelOpen == 0) {
                    openAllPanels(accordionId);
                } else {
                    closeAllPanels(accordionId);
                }
            })

            openAllPanels = function (aId) {
                console.log("setAllPanelOpen");
                $(aId + ' .panel-collapse:not(".in")').collapse('show');
            }
            closeAllPanels = function (aId) {
                console.log("setAllPanelclose");
                $(aId + ' .panel-collapse.in').collapse('hide');
            }

        });
    </script>

{{--    //add quiz question to db--}}
    <script>
        $(document).on("click", "#btn_add_quiz_question_to_db", function (event) {

            event.preventDefault();
            var post_type = 'trivia_quiz';

            // var question_number = $('#question_number_'+unique_id).text();

            var question_number = parseInt($(this).attr('data-question-number'));
            var post_id         = parseInt($(this).attr('data-post-id'));

            var update = question_number + 1;

            $('#btn_add_quiz_question_to_db').attr('data-question-number', update);


            console.log(question_number);

            $.ajax({
                url: "{{ route('add-trivia-quiz-question-to-db') }}",
                method: "GET",
                data: {post_type: post_type, question_number: update, post_id: post_id},
                success: function (result) {
                    $('#quiz_questions_container').append(result);
                }
            });
        });
    </script>

{{--    //add quiz result to db--}}
    <script>
        $(document).on("click", "#btn_add_quiz_result_to_db", function (event) {

            event.preventDefault();
            var post_type = 'trivia_quiz';

            // var question_number = $('#question_number_'+unique_id).text();

            var result_number = parseInt($(this).attr('data-result-number'));
            var post_id = parseInt($(this).attr('data-post-id'));

            var update = result_number + 1;

            $('#btn_add_quiz_result_to_db').attr('data-result-number', update);


            console.log(result_number);

            $.ajax({
                url: "{{ route('add-trivia-quiz-result-to-db') }}",
                method: "GET",
                data: {post_type: post_type, result_number: update, post_id: post_id},
                success: function (result) {
                    $('#quiz_result_container').append(result);
                }
            });
        });
    </script>

{{--    //add quiz answer to db--}}
    <script>
        $(document).on("click", ".btn-add-answer-to-db", function (event) {
            event.preventDefault();

            var question_id = $(this).attr('data-question-id');
            var post_type = $(this).attr('data-question-type');

            console.log(question_id);

            $.ajax({
                url: "{{ route('add-trivia-quiz-answer-to-db') }}",
                method: "GET",
                data: {post_type: post_type, question_id: question_id},
                success: function (result) {
                    $('#quiz_answers_container_question_' + question_id).append(result);
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.btn-delete-selected-image', function () {
            var result_id = $(this).attr("data-answer-id");
            console.log(result_id)
            var question_id = $(this).attr("data-question-id");
            var input = '<input type="hidden" name="answer_image_'+result_id+'" value="" class="image_id">';
            var content = '<div class="quiz-answer-image-container" data-question-id="'+question_id+'" data-answer-id="'+result_id+'">' +
                input +
                '<a class="btn-select-image btn-image-modal" data-toggle="modal" id="btn_image_modal" data-target=".image-modal-lg"  data-id="1" data-quiz-image-type="answer" data-question-id="' + question_id + '" data-answer-id="' + result_id + '" data-is-update="1">' +
                '<div class="btn-select-image-inner">' +
                '<i class="icon-images fa fa-picture-o"></i>' +
                '<button class="btn">{{__('select_image')}}</button>' +
                '</div>' +
                '</a>' +
                '</div>';

            // $("#quiz_answer_image_container_answer_" + result_id).empty();
            // $("#quiz_answer_image_container_answer_" + result_id).html(content);
            document.getElementById("quiz_answer_image_container_answer_" + result_id).innerHTML = content;
        });
    </script>

@endsection
