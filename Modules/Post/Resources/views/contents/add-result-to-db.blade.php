<div id="panel_quiz_result_{{ $quiz_result->id }}"
     class="panel panel-default panel-quiz-result"
     data-result-id="{{ $quiz_result->id }}">
    <div class="panel panel-default mt-2 bg-white">
        <div class="panel-heading" role="tab"
             id="heading_{{ $quiz_result->id }}">
            <h4 class="panel-title d-flex">
                <a role="button" data-toggle="collapse" data-parent="#accordion"
                   href="#collapse_result_{{ $quiz_result->id }}" aria-expanded="true"
                   aria-controls="collapse_result_{{ $quiz_result->id }}">
                    #<span id="result_number_{{ $quiz_result->id }}">{{ $result_number }}</span>
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
             class="panel-collapse collapse in show"
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
                                   data-is-update="1"
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
                                          id="result_content_{{ $quiz_result->id }}">{{ $quiz_result->description }}</textarea>
                            </div>
                        </div>
                        @if($post_type == 'trivia_quiz')
                            <div class="col-sm-12">
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <label class="control-label">
                                        {{ __('range_of_correct_ans_for_showing_this_result') }}
                                    </label>
                                </div>
                                <div class="col-sm-6 form-group">
                                    <input type="number" required
                                           class="form-control input-question-text"
                                           data-result-id="result_{{$quiz_result->id}}"
                                           name="min_correct_count_{{ $quiz_result->id }}"
                                           placeholder="{{__('minimum_correct')}}"
                                           value="{{ $quiz_result->min_correct }}" min="0">
                                </div>
                                <div class="col-sm-6 form-group">
                                    <input type="number" required
                                           class="form-control input-question-text"
                                           data-result-id="result_{{$quiz_result->id}}"
                                           name="max_correct_count_{{ $quiz_result->id }}"
                                           placeholder="{{__('maximum_correct')}}"
                                           value="{{ $quiz_result->max_correct }}" min="0">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    tinymce.init({
        selector: "textarea#result_content_{{$quiz_result->id}}",
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
