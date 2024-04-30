@php $unique_id = uniqid() @endphp
<div id="panel_quiz_question_{{ $unique_id }}"
     class="panel panel-default panel-quiz-question"
     data-question-id="{{ $unique_id }}">
    <div class="panel panel-default mt-2 bg-white">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title d-flex">
                <a role="button" data-toggle="collapse" data-parent="#accordion"
                   href="#collapse_{{ $unique_id }}" aria-expanded="true"
                   aria-controls="collapse_{{ $unique_id }}" class="">
                    #<span id="question_number_{{ $unique_id }}"></span> <span id="question_{{ $unique_id }}"></span>
                </a>
                <div class="text-right">
                    <button type="button" class="btn btn-default"
                            onclick="delete_quiz_question('{{ $unique_id }}')"><i
                            class="fa fa-trash"></i></button>
                </div>
            </h4>
        </div>
        <div id="collapse_{{ $unique_id }}" class="panel-collapse in collapse show"
             role="tabpanel" aria-labelledby="heading_{{ $unique_id }}" style="">
            <div class="panel-body bg-white">

                <div class="col-12 col-lg-12">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label
                                for="question"><b>{{ __('question') }} *</b></label>
                            <input name="question_id[]" type="hidden" value="{{ $unique_id }}">
                            <input type="text" id="question_input" required
                                   class="form-control input-question-text"
                                   data-question-id="{{ $unique_id }}"
                                   name="question_title[]"
                                   placeholder="{{ __('question') }}" value="">
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
                                            data-id="1" data-toggle="modal" data-question-id="{{ $unique_id }}"
                                            data-target=".image-modal-lg">{{ __('add_image') }}</button>
                                    <input id="image_id_question_{{ $unique_id }}" name="question_image[]"
                                           type="hidden"
                                           class="form-control image_id">
                                </div>
                                <div class="form-group">
                                    <div class="form-group text-center">
                                        <img
                                            src="{{static_asset('default-image/default-100x100.png') }} "
                                            id="image_preview_question_{{ $unique_id }}"
                                            width="200" height="200" alt="image"
                                            class="img-responsive img-thumbnail image_preview">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="form-group">
                                <label for="question_content"
                                       class="col-form-label pt-0">{{ __('content') }}
                                    </label>
                                <textarea name="question_content[]" class="question-content"
                                          value=""
                                          id="question_content_{{ $unique_id }}"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12 m-b-15">
                                    <label
                                        class="control-label">{{ __('answers') }}</label>
                                </div>
                            </div>
                            <div class="quiz-answers">
                                <div class="row">
                                    <div
                                        class="col-sm-12 btn-group-answer-formats-container">
                                        <input type="hidden" name="answer_format[]" required
                                               id="input_answer_format_{{ $unique_id }}"
                                               value="small_image">
                                        <span
                                            class="span-answer-format">{{ __('answer_format') }}</span>
                                        <div
                                            class="btn-group btn-group-answer-formats"
                                            role="group">
                                            <button type="button"
                                                    class="btn btn-default active btn_{{ $unique_id }}"
                                                    data-answer-format="small_image"
                                                    data-question-id="{{ $unique_id }}">
                                                <i class="fa fa-th"></i></button>
                                            <button type="button"
                                                    class="btn btn-default btn_{{ $unique_id }}"
                                                    data-answer-format="large_image"
                                                    data-question-id="{{ $unique_id }}">
                                                <i class="fa fa-th-large"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-default btn_{{ $unique_id }}"
                                                    data-answer-format="text"
                                                    data-question-id="{{ $unique_id }}">
                                                <i class="fa fa-th-list"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    id="quiz_answers_container_question_{{ $unique_id }}"
                                    class="row row-answer">

                                    @php $answer_unique_id = uniqid() @endphp
                                    <div id="quiz_answer_{{ $answer_unique_id }}"
                                         class="answer">
                                        <div class="answer-inner">
                                            <a href="javascript:void(0)"
                                               class="btn-delete-answer"
                                               onclick="delete_quiz_answer('{{ $answer_unique_id }}')"><i
                                                    class="fa fa-times"></i></a>
                                            <div
                                                class="form-group quiz-answer-image-item-text m-b-0">
                                                <input type="hidden"
                                                       name="answer_unique_id_question_{{ $unique_id }}[]"
                                                       value="{{ $answer_unique_id }}">
                                                <div
                                                    id="quiz_answer_image_container_answer_{{ $answer_unique_id }}"
                                                    class="quiz-answer-image-item">
                                                    <div
                                                        class="quiz-answer-image-container" data-question-id="{{ $unique_id }}"
                                                        data-answer-id="{{ $answer_unique_id }}">
                                                        <input type="hidden"
                                                               name="answer_image_question_{{ $unique_id }}[]"
                                                               value="" class="image_id">
                                                        <a class="btn-select-image btn-image-modal"
                                                           id="btn_image_modal"
                                                           data-toggle="modal"
                                                           data-target=".image-modal-lg"
                                                           data-id="1"
                                                           data-quiz-image-type="answer"
                                                           data-question-id="{{ $unique_id }}"
                                                           data-answer-id="{{ $answer_unique_id }}"
                                                           data-is-update="0">
                                                            <div
                                                                class="btn-select-image-inner">
                                                                <i class="icon-images fa fa-picture-o"></i>
                                                                <button
                                                                    class="btn">{{ __('select_image') }}</button>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <textarea required
                                                    name="answer_text_question_{{ $unique_id }}[]"
                                                    class="form-control answer-text"
                                                    placeholder="{{ __('answer_text') }}"></textarea>
                                            </div>
                                            @if($post_type == 'trivia_quiz')
                                                <div class="form-group">
                                                    <div class="answer-radio-container">
                                                        <label
                                                            class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" required
                                                                   name="correct_answer_question_{{ $unique_id }}"
                                                                   id="radio_answer_{{ $answer_unique_id }}"
                                                                   value="{{ $answer_unique_id }}"
                                                                   class="custom-control-input"
                                                                   data-parsley-multiple="correct">
                                                            <span
                                                                class="custom-control-label">{{__('correct')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @elseif($post_type == 'personality_quiz')

                                                <div class="form-group">
                                                    <div class="result-select px-1">
                                                        <select name="selected_result_question_answer_{{ $answer_unique_id }}" class="form-control personality-quiz-result-dropdown rounded">
                                                            <option>{{__('select_a_result')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @php $answer_unique_id = uniqid() @endphp
                                    <div id="quiz_answer_{{ $answer_unique_id }}"
                                         class="answer">
                                        <div class="answer-inner">
                                            <a href="javascript:void(0)"
                                               class="btn-delete-answer"
                                               onclick="delete_quiz_answer('{{ $answer_unique_id }}')"><i
                                                    class="fa fa-times"></i></a>
                                            <div
                                                class="form-group quiz-answer-image-item-text m-b-0">
                                                <input type="hidden"
                                                       name="answer_unique_id_question_{{ $unique_id }}[]"
                                                       value="{{ $answer_unique_id }}">
                                                <div
                                                    id="quiz_answer_image_container_answer_{{ $answer_unique_id }}"
                                                    class="quiz-answer-image-item">
                                                    <div
                                                        class="quiz-answer-image-container" data-question-id="{{ $unique_id }}"
                                                        data-answer-id="{{ $answer_unique_id }}">
                                                        <input type="hidden"
                                                               name="answer_image_question_{{ $unique_id }}[]"
                                                               value="" class="image_id">
                                                        <a class="btn-select-image btn-image-modal"
                                                           id="btn_image_modal"
                                                           data-toggle="modal"
                                                           data-target=".image-modal-lg"
                                                           data-id="1"
                                                           data-quiz-image-type="answer"
                                                           data-question-id="{{ $unique_id }}"
                                                           data-answer-id="{{ $answer_unique_id }}"
                                                           data-is-update="0">
                                                            <div
                                                                class="btn-select-image-inner">
                                                                <i class="icon-images fa fa-picture-o"></i>
                                                                <button
                                                                    class="btn">{{ __('select_image') }}</button>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <textarea required
                                                    name="answer_text_question_{{ $unique_id }}[]"
                                                    class="form-control answer-text"
                                                    placeholder="{{ __('answer_text') }}"></textarea>
                                            </div>
                                            @if($post_type == 'trivia_quiz')
                                                <div class="form-group">
                                                    <div class="answer-radio-container">
                                                        <label
                                                            class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" required
                                                                   name="correct_answer_question_{{ $unique_id }}"
                                                                   id="radio_answer_{{ $answer_unique_id }}"
                                                                   value="{{ $answer_unique_id }}"
                                                                   class="custom-control-input"
                                                                   data-parsley-multiple="correct">
                                                            <span
                                                                class="custom-control-label">{{__('correct')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @elseif($post_type == 'personality_quiz')

                                                <div class="form-group">
                                                    <div class="result-select px-1">
                                                        <select name="selected_result_question_answer_{{ $answer_unique_id }}" class="form-control personality-quiz-result-dropdown rounded">
                                                            <option>{{__('select_a_result')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @php $answer_unique_id = uniqid() @endphp
                                    <div id="quiz_answer_{{ $answer_unique_id }}"
                                         class="answer">
                                        <div class="answer-inner">
                                            <a href="javascript:void(0)"
                                               class="btn-delete-answer"
                                               onclick="delete_quiz_answer('{{ $answer_unique_id }}')"><i
                                                    class="fa fa-times"></i></a>
                                            <div
                                                class="form-group quiz-answer-image-item-text m-b-0">
                                                <input type="hidden"
                                                       name="answer_unique_id_question_{{ $unique_id }}[]"
                                                       value="{{ $answer_unique_id }}">
                                                <div
                                                    id="quiz_answer_image_container_answer_{{ $answer_unique_id }}"
                                                    class="quiz-answer-image-item">
                                                    <div
                                                        class="quiz-answer-image-container" data-question-id="{{ $unique_id }}"
                                                        data-answer-id="{{ $answer_unique_id }}">
                                                        <input type="hidden"
                                                               name="answer_image_question_{{ $unique_id }}[]"
                                                               value="" class="image_id">
                                                        <a class="btn-select-image btn-image-modal"
                                                           id="btn_image_modal"
                                                           data-toggle="modal"
                                                           data-target=".image-modal-lg"
                                                           data-id="1"
                                                           data-quiz-image-type="answer"
                                                           data-question-id="{{ $unique_id }}"
                                                           data-answer-id="{{ $answer_unique_id }}"
                                                           data-is-update="0">
                                                            <div
                                                                class="btn-select-image-inner">
                                                                <i class="icon-images fa fa-picture-o"></i>
                                                                <button
                                                                    class="btn">{{ __('select_image') }}</button>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <textarea required
                                                    name="answer_text_question_{{ $unique_id }}[]"
                                                    class="form-control answer-text"
                                                    placeholder="{{ __('answer_text') }}"></textarea>
                                            </div>
                                            @if($post_type == 'trivia_quiz')
                                                <div class="form-group">
                                                    <div class="answer-radio-container">
                                                        <label
                                                            class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" required
                                                                   name="correct_answer_question_{{ $unique_id }}"
                                                                   id="radio_answer_{{ $answer_unique_id }}"
                                                                   value="{{ $answer_unique_id }}"
                                                                   class="custom-control-input"
                                                                   data-parsley-multiple="correct">
                                                            <span
                                                                class="custom-control-label">{{__('correct')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @elseif($post_type == 'personality_quiz')

                                                <div class="form-group">
                                                    <div class="result-select px-1">
                                                        <select name="selected_result_question_answer_{{ $answer_unique_id }}" class="form-control personality-quiz-result-dropdown rounded">
                                                            <option>{{__('select_a_result')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <button type="button"
                                                id="btn_add_quiz_answer"
                                                class="btn-add-quiz-answer btn btn-add-answer"
                                                data-question-id="{{ $unique_id }}"
                                                data-question-type="{{ $post_type== 'trivia_quiz' ? 'trivia_quiz' : 'personality_quiz'  }}"><i
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
        selector: "textarea#question_content_{{ $unique_id }}",
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

    $(document).ready(function () {
        result_dropdowns();

        var old_count = parseInt({{ $question_number }});

        document.getElementById("question_number_{{$unique_id}}").innerHTML = ++old_count;
    });
</script>
