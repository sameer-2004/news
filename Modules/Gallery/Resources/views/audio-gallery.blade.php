<div id="audio-gallery" class="modal fade audio-modal-lg" tabindex="-1" role="dialog" aria-labelledby="audioGallery"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('audio_gallery') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 border-right">
                        {!!  Form::open(['id'=>'audioUploadForm','method' => 'post','enctype'=>'multipart/form-data']) !!}
                        <div class="form-group">
                            <div class="form-group">
                                <label for="audio" class="upload-file-btn btn btn-primary  btn-block"><i
                                        class="fa fa-folder input-file" aria-hidden="true"></i> {{ __('add_audio') }}
                                </label>
                                <input id="audio" name="audio" type="file" class="form-control d-none" required
                                       onChange="audioUploadBtn()" data-index="0">
                            </div>
                            <div class="form-group">
                                <div class="form-group text-center">
                                    <div id="audio_name"></div>
                                </div>
                            </div>
                            <div class="form-group" id="divAudioUploadBtn">
                                <div class="form-group text-center">
                                    <button type="submit" name="btn_audio_upload" id="audio-upload-btn"
                                            class="btn btn-primary btn-block"><i
                                            class="fas fa-cloud-upload-alt"></i> {{ __('upload') }}</button>
                                </div>
                            </div>
                        </div>
                        {!!  Form::close() !!}
                    </div>
                    <div class="col-md-8">
                        <div class="row" id="audio-library">


                        </div>
                        <div class="ajax-loading" id="ajax-audio-loading"><img
                                src="{{static_asset('site/images/preloader-2.gif') }}"/>
                        </div>
                        <div class="load-more" id="load-more-audio"><a href="javascript:void(0)"
                                                                       class="">{{ __('load_more') }}</a></div>

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="selectAudio" class="btn btn-primary"
                        data-dismiss="modal">{{ __('select_audio') }}</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('close') }}</button>
                <div class="delete-audio-btn">
                    <button type="button" class='btn btn-danger'>{{ __('delete') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>

    $(document).ready(function (e) {

        var audio_page_no = 1;
        fetch_audio_url = "{{ route('fetch-audio') }}";

        $(document).on('click', '#btnAudioModal', function () {
            $.ajax({
                url: fetch_audio_url,
                type: 'get',
                dataType: 'html',
                beforeSend: function () {
                    $('#ajax-audio-loading').show();
                },
                success: function (data) {

                    if (parseInt($("#audioCount").val()) * 24 >= parseInt($("#audios").val())) {
                        $('#load-more-audio').hide();
                        $('#ajax-audio-loading').html('{{ __('no_more_records') }}');
                    } else {
                        $('#ajax-audio-loading').hide();
                        $('#load-more-audio').show();
                    }

                    $("#audio-library").html(data);
                    $("#audioCount").val(parseInt($("#audioCount").val()) + 1);
                }
            })
                .fail(function () {
                    $('#ajax-audio-loading').hide();
                    swal('Oops...', '{{ __('something_went_wrong_with_ajax') }}', 'error');
                })
        });
        $(document).on('click', '#load-more-audio', function () {
            audio_page_no++;
            let next_url = fetch_audio_url + '?page=' + audio_page_no;

            $.ajax({
                url: next_url,
                type: 'get',
                beforeSend: function () {
                    $('#ajax-audio-loading').show();
                },
                dataType: 'html',
                success: function (data) {

                    if (parseInt($("#audioCount").val()) * 24 >= parseInt($("#audios").val())) {
                        $('#load-more-audio').hide();
                        $('#ajax-audio-loading').html('{{ __('no_more_records') }}');
                    } else {
                        $('#ajax-audio-loading').hide();
                        $('#load-more-audio').show();
                    }

                    $("#audio-library").append(data);
                    $("#audioCount").val(parseInt($("#audioCount").val()) + 1);
                }
            })
                .fail(function () {
                    $('#ajax-audio-loading').hide();
                    swal('Oops...', '{{ __('something_went_wrong_with_ajax') }}', 'error');
                })
            // }
        });


        $('#audioUploadForm').on('submit', (function (e) {
            e.preventDefault();
            $("#audio-upload-btn").prop('disabled', true);
            $("#audio-upload-btn").html('<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only"></span> Loading...');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('audio-upload')}}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    $("#audio-library").prepend(
                        @if(settingHelper('default_storage') =='local')
                            '<div class="col-md-2" id="row_' + data[0].id + '"><img src="{{ static_asset('default-image/music-100x100.png') }}" alt="' + data[0].audio_name + '" for="' + data[0].id + '" class="audio img-responsive img-thumbnail"><label class="audio_lvl" for="' + data[0].id + '">' + data[0].audio_name + '</label> </div>');
                    @else
                        '<div class="col-md-2" id="row_' + data[0].id + '"><img src="{{ static_asset('default-image/music-100x100.png') }}" alt="' + data[0].audio_name + '" for="' + data[0].id + '" class="audio img-responsive img-thumbnail"><label class="audio_lvl" for="' + data[0].id + '">' + data[0].audio_name + '</label> </div>'
                );
                @endif
                $.notify('successfully audio uploaded to gallery', "success");
                $("#audio").val('');
            $("#audio-upload-btn").html('<i class="fas fa-cloud-upload-alt"></i> Upload');
            $("#audio-upload-btn").prop('disabled', false);
            $("#audio_name").text(' ');
            $("#divAudioUploadBtn").hide();
        },

            error: function(data)
        {
            if (data.responseJSON.errors['audio'] !== undefined) {
                $.notify(data.responseJSON.errors['audio'], "danger");
            }
            $.notify(data.responseJSON.message, "danger");
            $("#audio").val('');
            $("#audio-upload-btn").html('<i class="fas fa-cloud-upload-alt"></i> Upload');
            $("#audio-upload-btn").prop('disabled', false);
            $("#audio_name").text(' ');
            $("#divAudioUploadBtn").hide();
            // $('#error_msg').append(data.responseJSON.message);
            // console.log(data.responseJSON.message);
        }
    });
    }))
    ;
    })
    ;
</script>

<script>
    var selected_audio_id = '';

    $(document).on('click', '.audio', function () {
        $('.audio').removeClass('selected');
        $('.delete-audio-btn').css('display', 'block');
        $('#selectAudio').css('display', 'block');
        selected_audio_id = $(this).attr('for');
        selected_audio_lvl = $(this).attr('alt');
        console.log(selected_audio_lvl);
        selected_audio_src = $(this).attr('src');
        $(this).addClass('selected');
    });
    $("#selectAudio").on('click', function () {
        $('#selected_audio_name').text(selected_audio_src);
        $('#audio_id').val(selected_audio_id);
        $('#selected_audio_lvl').val(selected_audio_lvl);
        $('#audio_thumb').show();
        $("#audio_thumb").prepend(
            '<div class="audio-item audio_' + selected_audio_id + ' mb-1" id="' + selected_audio_id + '"><input id="' + selected_audio_id + '" name="audio[]" type="hidden" value="' + selected_audio_id + '"><div class="left"><i class="fa fa-music"></i></div><div class="center">' + selected_audio_lvl + '</div><div class="right"><button type="button" class="btn btn-danger px-1 py-0 float-right row_remove"><i class="m-r-0 mdi mdi-delete"></i></button></div></div>');
    });

    $(".delete-audio-btn").on('click', function () {
        var div_row = '#row_' + selected_audio_id;
        var selected = 'audio_' + selected_audio_id;
        var token = "{{ csrf_token() }}";
        deleteUrl = "{{ route('delete-audio') }}";

        swal({
            title: "{{ __('are_you_sure?') }}",
            text: "{{ __('it_will_be_deleted_permanently') }}",
            icon: "warning",
            buttons: true,
            buttons: ["{{ __('cancel') }}", "{{ __('delete') }}"],
            dangerMode: true,
            closeOnClickOutside: false
        })
            .then(function (confirmed) {
                if (confirmed) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'delete',
                        data: 'row_id=' + selected_audio_id + '&_token=' + token,
                        dataType: 'json'
                    })
                        .done(function (response) {
                            swal.stopLoading();
                            if (response.status == "success") {
                                console.log(response);
                                swal("{{ __('deleted') }}!", response.message, response.status);
                                $(div_row).fadeOut(2000);

                                if ($(".audio-item").hasClass(selected)) {
                                    let id = selected;
                                    $('.' + id).remove();
                                }

                            } else {
                                swal("Error!", response.message, response.status);
                            }
                        })
                        .fail(function () {
                            swal('Oops...', '{{ __('something_went_wrong_with_ajax') }}', 'error');
                        })
                }
            })
    });
</script>

