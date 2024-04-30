@php $result_unique_id = uniqid() @endphp
<div id="panel_quiz_result_{{ $result_unique_id }}"
     class="panel panel-default panel-quiz-result"
     data-result-id="{{ $result_unique_id }}">
    <div class="panel panel-default mt-2 bg-white">
        <div class="panel-heading" role="tab" id="heading_"{{ $result_unique_id }}>
            <h4 class="panel-title d-flex">
                <a role="button" data-toggle="collapse" data-parent="#accordion"
                   href="#collapse_{{ $result_unique_id }}" aria-expanded="true"
                   aria-controls="collapse_{{ $result_unique_id }}">
                    #<span id="result_number_{{ $result_unique_id }}">1</span> <span id="result_{{ $result_unique_id }}"></span>
                </a>
                <div class="text-right">
                    <button type="button" class="btn btn-default"
                            onclick="delete_quiz_result('{{ $result_unique_id }}')">
                        <i class="fa fa-trash"></i></button>
                </div>
            </h4>
        </div>
        <div id="collapse_{{ $result_unique_id }}"
             class="panel-collapse in collapse show"
             role="tabpanel" aria-labelledby="heading_{{ $result_unique_id }}"
             style="">
            <div class="panel-body bg-white">

                <div class="col-12 col-lg-12">
                    <div class="col-sm-12 pr-0">
                        <div class="form-group">
                            <label
                                for="result_input"><b>{{ __('result') }}</b></label>
                            <input type="text" id="result_input" required
                                   class="form-control input-result-text"
                                   data-result-id="{{ $result_unique_id }}"
                                   data-is-update="0"
                                   name="result_title[]"
                                   placeholder="{{ __('result') }}" value="">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-12">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="result-image">
                                <div class="form-group">
                                    <!-- Large modal -->
                                    <button type="button" id="btn_image_modal"
                                            class="btn btn-primary btn-image-modal ml-3"
                                            data-id="1" data-toggle="modal" data-result-id="{{ $result_unique_id }}"
                                            data-target=".image-modal-lg">{{ __('add_image') }}</button>
                                    <input id="image_id_result_{{$result_unique_id}}" name="result_image[]"
                                           type="hidden"
                                           class="form-control image_id">
                                </div>
                                <div class="form-group">
                                    <div class="form-group text-center">
                                        <img
                                            src="{{static_asset('default-image/default-100x100.png') }} "
                                            id="image_preview_result_{{$result_unique_id}}"
                                            width="200" height="200" alt="image"
                                            class="img-responsive img-thumbnail image_preview">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="form-group">
                                <label for="result_content"
                                       class="col-form-label pt-0">{{ __('content') }}</label>
                                <textarea name="result_content[]"
                                          class="result-content"
                                          id="result_content_{{ $result_unique_id }}"></textarea>
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
                                        <input type="number" min="0" required class="form-control input-question-text" data-result-id="result_{{$result_unique_id}}" name="min_correct_count[]" placeholder="{{__('minimum_correct')}}" value="">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <input type="number" min="0" required class="form-control input-question-text" data-result-id="result_{{$result_unique_id}}" name="max_correct_count[]" placeholder="{{__('maximum_correct')}}" value="">
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
        selector: "textarea#result_content_{{ $result_unique_id }}",
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

        var old_count = parseInt({{ $result_number }});

        document.getElementById("result_number_{{$result_unique_id}}").innerHTML = ++old_count;
    });
</script>
