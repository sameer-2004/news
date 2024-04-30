@php
    $answer_format_class = "";
@endphp
<div id="panel_quiz_question_{{ $quiz_question->id }}"
     class="panel panel-default panel-quiz-question"
     data-question-id="{{ $quiz_question->id }}" data-quiz-type="{{ $post_type }}">
    <div class="panel panel-default mt-2 bg-white">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title d-flex">
                <a role="button" data-toggle="collapse"
                   data-parent="#accordion"
                   href="#collapse_{{ $quiz_question->id }}" aria-expanded="true"
                   aria-controls="collapse_{{ $quiz_question->id }}">
                    #<span id="question_number_{{ $quiz_question->id }}">{{ $question_number }}</span>
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
             class="panel-collapse collapse in show"
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
                                          id="question_content_{{ $quiz_question->id }}">{{ $quiz_question->description }}</textarea>
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
                                                                <input type="hidden" name="answer_image_{{ $quiz_question_answer->id }}" value="">
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
                                                    <textarea required
                                                        name="answer_text_question_{{ $quiz_question_answer->id }}"
                                                        class="form-control answer-text"
                                                        placeholder="{{ __('answer_text') }}">{{ $quiz_question_answer->answer_text }}</textarea>
                                                </div>
                                                @if($post_type == 'trivia_quiz')
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
                                                @elseif($post_type == 'personality_quiz')
                                                    <div class="form-group">
                                                        <div class="result-select px-1">
                                                            <select name="selected_result_question_answer_{{ $quiz_question_answer->id }}" required class="form-control personality-quiz-result-dropdown rounded">
                                                                <option>{{__('select_a_result')}}</option>
                                                                @foreach($post->quizResults as $key => $result)
                                                                    <option value="{{ $result->id }}" {{ ($quiz_question_answer->result_id == $result->id)  ? 'selected': '' }}>{{ ($key + 1).'. ' . $result->result_title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif
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

<script>
    tinymce.init({
        selector: "textarea#question_content_{{ $quiz_question->id }}",
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

    result_dropdowns();

</script>
