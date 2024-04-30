@extends('common::layouts.master')
@section('gallery-aria-expanded')
    aria-expanded="true"
@endsection
@section('gallery-show')
    show
@endsection
@section('gallery')
    active
@endsection
@section('all-images-active')
    active
@endsection

@section('content')
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <!-- page info start-->
            <div class="row clearfix">
                <div class="col-12">
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
                        <div class="col-12 col-lg-8">
                            {{--                            {!!  Form::open(['route'=>'add-album-image','method' => 'post','enctype'='multipart/form-data']) !!}--}}
                            <form class="author-form" name="author-form" method="post"
                                  action="{{ route('update-album-image') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="add-new-page  bg-white p-20 m-b-20">
                                    <div class="add-new-header clearfix m-b-20">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="block-header">
                                                    <h2>{{ __('update_gallery_image') }}</h2>
                                                </div>
                                            </div>
                                            @if(Sentinel::getUser()->hasAccess(['album_read']))
                                                <div class="col-6 text-right">
                                                    <a href="{{ route('images') }}"
                                                       class="btn btn-primary btn-sm btn-add-new"><i
                                                            class="fa fa-bars"></i>
                                                        {{ __('back') }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="language">{{ __('select_language') }} *</label>
                                            <select class="form-control dynamic-album" id="language" name="language"
                                                    required data-dependent="album_id">
                                                @foreach ($activeLang as  $lang)
                                                    <option
                                                        @if(@$galleryImage->album->language==$lang->code) Selected
                                                        @endif value="{{ $lang->code }}">{{ $lang->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="album_id">{{ __('album') }} *</label>
                                            <select class="form-control dynamic-album-tab" id="album_id" name="album_id"
                                                    required data-dependent="album_tab">
                                                <option value="">{{ __('select_album') }}</option>
                                                @foreach ($albums as $album)
                                                    <option @if(@$galleryImage->album_id == $album->id) Selected
                                                            @endif value="{{ $album->id }}">{{ $album->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="tab">{{ __('tab') }} </label>
                                            <select class="form-control dynamic text-capitalize" id="album_tab" name="tab">
                                                <option value="">{{ __('select_tab') }}</option>
                                                @foreach (explode(',', @$galleryImage->album->tabs) as $tab)
                                                    <option @if(@$galleryImage->tab == $tab) Selected
                                                            @endif value="{{ $tab }}">{{ $tab }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="image-slug"
                                                   class="col-form-label">{{ __('title') }}
                                            </label>
                                            <input id="image-slug" name="title" value="{{ $galleryImage->title }}" type="text"
                                                   class="form-control">
                                            <input name="galleryImage_id" value="{{ $galleryImage->id }}" type="hidden">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="author">
                                                @if(isFileExist(@$galleryImage, $result = @$galleryImage->thumbnail))
                                                    <img
                                                        src=" {{basePath($galleryImage)}}/{{ $result }} "
                                                        data-src="{{basePath($galleryImage)}}/{{ $result }}" id="old-image"
                                                        width="200"  height="200" alt="image" class="img-responsive img-thumbnail lazyloaded">

                                                @else
                                                    <img src="{{static_asset('default-image/default-100x100.png') }} " width="200"
                                                         height="200" alt="image" id="old-image"
                                                         class="img-responsive img-thumbnail">
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="field" align="left">

                                            <div class="form-group text-left mb-0">
                                                <input type="file" id="new-image" class="d-none " name="image">
                                                <label for="new-image" class="upload-file-btn btn btn-primary">{{__('change_image')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 m-t-20">
                                            <div class="form-group form-float form-group-sm text-right">
                                                <button type="submit" name="btnsubmit" class="btn btn-primary"><i
                                                        class="m-r-10 mdi mdi-plus"></i>{{ __('update_image') }}</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                            {{--                            {!! Form::close() !!}--}}
                        </div>
                        <!-- Main Content section end -->

                    </div>
                </div>
            </div>
            <!-- page info end-->
        </div>
    </div>
@section('script')

    <script>
        $(document).ready(function () {

            $('.dynamic-album').change(function () {
                if ($(this).val() != '') {
                    var select = $(this).attr("id");
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        url: "{{ route('album-fetch') }}",
                        method: "POST",
                        data: {select: select, value: value, _token: _token},
                        success: function (result) {
                            $('#' + dependent).html(result);
                        }

                    })
                }
            });

            $('.dynamic-album-tab').change(function () {
                if ($(this).val() != '') {
                    var select = $(this).attr("id");
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        url: "{{ route('album-tabs-fetch') }}",
                        method: "POST",
                        data: {select: select, value: value, _token: _token},
                        success: function (result) {
                            $('#' + dependent).html(result);
                        }

                    })
                }
            });

            $('#language').change(function () {
                $('#album_tab').val('');
                $('#album_id').val('');
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#old-image').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#new-image").change(function(){
            readURL(this);
        });

    </script>
@endsection
@endsection
