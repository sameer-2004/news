@php $answer_unique_id = uniqid() @endphp
<div id="quiz_answer_{{ $answer_unique_id }}" class="answer">
    <div class="answer-inner">
        <a href="javascript:void(0)" class="btn-delete-answer"
           onclick="delete_quiz_answer('{{ $answer_unique_id }}')"><i class="fa fa-times"></i></a>
        <div class="form-group quiz-answer-image-item-text m-b-0">
            <input type="hidden" name="answer_unique_id_question_{{ $unique_id }}[]" value="{{ $answer_unique_id }}">
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
        </div>
        <div class="form-group">
            <textarea name="answer_text_question_{{ $unique_id }}[]" class="form-control answer-text" required
                      placeholder="{{ __('answer_text') }}"></textarea>
        </div>
        @if($post_type == 'trivia_quiz')
            <div class="form-group">
                <div class="answer-radio-container">
                    <label class="custom-control custom-radio custom-control-inline">
                        <input type="radio" required name="correct_answer_question_{{ $unique_id }}"
                               id="radio_answer_{{ $answer_unique_id }}" value="{{ $answer_unique_id }}"
                               class="custom-control-input" data-parsley-multiple="correct">
                        <span class="custom-control-label">{{__('correct')}}</span>
                    </label>
                </div>
            </div>
        @elseif($post_type == 'personality_quiz')

            <div class="form-group">
                <div class="result-select px-1">
                    <select name="selected_result_question_answer_{{ $answer_unique_id }}" class="form-control personality-quiz-result-dropdown rounded">
                        <option>{{__('select_a_result')}}</option>
                        <option value="1">1.</option>
                    </select>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    $(document).ready(function (){
        result_dropdowns();
    });
</script>
