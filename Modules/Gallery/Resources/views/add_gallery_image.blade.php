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
                                  action="{{ route('add-album-image') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="add-new-page  bg-white p-20 m-b-20">
                                    <div class="add-new-header clearfix m-b-20">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="block-header">
                                                    <h2>{{ __('add_gallery_image') }}</h2>
                                                </div>
                                            </div>
                                            @if(Sentinel::getUser()->hasAccess(['post_write']))
                                                <div class="col-6 text-right">
                                                    <a href="{{ route('images') }}"
                                                       class="btn btn-primary btn-sm btn-add-new"><i
                                                            class="fa fa-bars"></i>
                                                        {{ __('gallery_images') }}
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
                                                        @if(App::getLocale()==$lang->code) Selected
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
                                                    <option value="{{ $album->id }}">{{ $album->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="album_tab">{{ __('tab') }} </label>
                                            <select class="form-control dynamic text-capitalize" id="album_tab" name="tab">
                                                <option value="">{{ __('select_album_first') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="image-slug"
                                                   class="col-form-label">{{ __('title') }}
                                             </label>
                                            <input id="image-slug" name="title" value="" type="text"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="field" align="left">
                                            <label for="images" class="upload-file-btn btn btn-primary"><i
                                                    class="fa fa-folder input-file"
                                                    aria-hidden="true"></i> {{__('select_image')}} *
                                            </label><br>
                                            <input type="file" id="images" class="d-none " name="files[]" required
                                                   multiple/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 m-t-20">
                                            <div class="form-group form-float form-group-sm text-right">
                                                <button type="submit" name="btnsubmit" class="btn btn-primary"><i
                                                        class="m-r-10 mdi mdi-plus"></i>{{ __('add_image') }}</button>
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
        </script>
    @endsection
@endsection
