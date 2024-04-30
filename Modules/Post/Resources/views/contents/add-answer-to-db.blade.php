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
                        <option value="1">1.</option>
                    </select>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    $(document).ready(function () {
        result_dropdowns();
    });
</script>
