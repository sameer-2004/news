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
@section('albums-active')
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
                        <div class="col-12 col-lg-12">
                            {!!  Form::open(['route'=>'update-album','method' => 'post', 'enctype' => "multipart/form-data"]) !!}
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('update_album') }}</h2>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="language">{{ __('select_language') }}*</label>
                                        <select class="form-control" name="language" id="language">
                                            @foreach ($activeLang as  $lang)
                                                <option
                                                    @if($album->language==$lang->code) Selected
                                                    @endif value="{{$lang->code}}">{{$lang->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name" class="col-form-label">{{ __('album_name') }}
                                            *</label>
                                        <input id="name" name="name"
                                               value="{{ $album->name }}" type="text" class="form-control">
                                        <input name="album_id" value="{{ $album->id }}" type="hidden">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="album-slug" class="col-form-label"><b>{{ __('slug') }}</b>
                                            ({{ __('slug_message') }})</label>
                                        <input id="album-slug" name="slug" value="{{ $album->slug }}" type="text"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post_tags" class="col-form-label">{{ __('tabs') }} ({{ __('hit_enter') }})</label>
                                        <input id="post_tags" name="tabs" type="text" value="{{ $album->tabs }}"
                                               data-role="tagsinput" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="album-desc" class="col-form-label"><b>{{ __('description') }}</b>
                                            ({{ __('meta_tag') }})</label>
                                        <input id="album-desc" name="meta_description"
                                               value="{{ $album->meta_description }}" type="text"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="album-keywords"
                                               class="col-form-label"><b>{{ __('keywords') }}</b> ({{ __('meta_tag') }})</label>
                                        <input id="album-keywords" name="meta_keywords"
                                               value="{{ $album->meta_keywords }}" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="field" align="left">
                                        <label for="images" class="upload-file-btn btn btn-primary"><i
                                                class="fa fa-folder input-file"
                                                aria-hidden="true"></i> {{__('select_cover_image')}} *
                                        </label><br>

                                        <input type="file" id="images" class="d-none " name="cover_image"
                                               />
                                        <span class="each" id="file1">
                                            @if(isFileExist(@$album, $result = @$album->thumbnail))
                                                <img class="imageThumb" src="{{basePath($album)}}/{{ $result }}" title="cover image">
                                            @endif
                                            <br>
                                        </span>
                                </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 m-t-20">
                                        <div class="form-group form-float form-group-sm text-right">
                                            <button type="submit" name="btnSubmit" class="btn btn-primary pull-right"><i
                                                    class="m-r-10 mdi mdi-content-save-all"></i>{{ __('update_album') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {!! Form::close() !!}
                        </div>
                        <!-- Main Content section end -->
                    </div>
                </div>
            </div>
            <!-- page info end-->
        </div>
    </div>
@section('script')
    <script src="{{static_asset('js/tagsinput.js')}}"></script>
@endsection
@endsection
