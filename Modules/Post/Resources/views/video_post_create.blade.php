@extends('common::layouts.master')
@section('post-aria-expanded')
    aria-expanded="true"
@endsection
@section('post-show')
    show
@endsection
@section('post')
    active
@endsection
@section('create_video')
    active
@endsection
@section('modal')
    @include('gallery::image-gallery')
    @include('gallery::video-gallery')
@endsection

@section('content')
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <!-- page info start-->
            {!!  Form::open(['route' => ['save-new-post','video'],'method' => 'post','enctype'=>'multipart/form-data']) !!}
            <input type="hidden" id="images" value="{{ $countImage }}">
            <input type="hidden" id="videos" value="{{ $countVideo }}">
            <input type="hidden" id="imageCount" value="1">
            <input type="hidden" id="videoCount" value="1">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="add-new-header clearfix m-b-20">
                        <div class="row">
                            <div class="col-6">
                                <div class="block-header">
                                    <h2>{{ __('add_post') }}</h2>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('post') }}" class="btn btn-primary btn-add-new"><i
                                        class="fas fa-list"></i> {{ __('posts') }}
                                </a>
                            </div>
                        </div>
                    </div>
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
                        <div class="col-12 col-lg-9">
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('posts_details') }}</h2>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post_title" class="col-form-label">{{ __('title') }}*</label>
                                        <input id="post_title" onkeyup="metaTitleSet()" name="title"
                                               value="{{ old('title') }}" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post-slug" class="col-form-label"><b>{{ __('slug') }}</b>
                                            ({{ __('slug_message') }})</label>
                                        <input id="post-slug" name="slug" value="{{ old('slug') }}" type="text"
                                               class="form-control">
                                    </div>
                                </div>
                                <!-- tinemcey start -->
                                <div class="row p-l-15">
                                    <div class="col-12">
                                        <label for="post_content" class="col-form-label">{{ __('content') }}*</label>
                                        <textarea name="content" value="{{ old('content') }}" id="post_content"
                                                  cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                                <!-- tinemcey end -->
                            <div class="add-new-page  bg-white p-20 m-b-20 add-new-content">
                                <div class="block-header">
                                    <h2>{{ __('add_content') }}</h2>
                                </div>
                                <div class="row pb-3">
                                    <div class="col-md-2 text-center area">
                                        <div class="item" onclick="addContent('text')">
                                            <img class="pb-3" src="{{static_asset('default-image/content-icon/text.png') }}"><br>
                                            <label>{{ __('text') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center area">
                                        <div class="item" onclick="addContent('image')">
                                            <img class="pb-3" src="{{static_asset('default-image/content-icon/image.png') }}"><br>
                                            <label>{{ __('image') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center area">
                                        <div class="item" onclick="addContent('image-text')">
                                            <img class="pb-3" src="{{static_asset('default-image/content-icon/image-text.png') }}"><br>
                                            <label>{{ __('image_left') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center area">
                                        <div class="item" onclick="addContent('text-image')">
                                            <img class="pb-3" src="{{static_asset('default-image/content-icon/text-image.png') }}"><br>
                                            <label>{{ __('image_right') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center area">
                                        <div class="item" onclick="addContent('text-image-text')">
                                            <img class="pb-3" src="{{static_asset('default-image/content-icon/text-image-text.png') }}"><br>
                                            <label>{{ __('image_center') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center area">
                                        <div class="item" onclick="addContent('video')">
                                            <img class="pb-3" src="{{static_asset('default-image/content-icon/video.png') }}"><br>
                                            <label>{{ __('video') }}</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="row pb-3">
                                    <div class="col-md-2 text-center area">
                                        <div class="item" onclick="addContent('ads')">
                                            <img class="pb-3" src="{{static_asset('default-image/content-icon/ads.png') }}"><br>
                                            <label>{{ __('ads') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-header">
                                    <h2>{{ __('embadded') }}</h2>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 text-center area">
                                        <div class="item" onclick="addContent('code')">
                                            <img class="pb-3" src="{{static_asset('default-image/content-icon/code.png') }}"><br>
                                            <label>{{ __('code') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center area">
                                        <div class="item" onclick="addContent('twitter-embed')">
                                            <img class="pb-3" src="{{static_asset('default-image/content-icon/twitter.png') }}"><br>
                                            <label>{{ __('twitter') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center area">
                                        <div class="item" onclick="addContent('vimeo-embed')">
                                            <img class="pb-3" src="{{static_asset('default-image/content-icon/vimeo.png') }}"><br>
                                            <label>{{ __('vimeo') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center area">
                                        <div class="item" onclick="addContent('youtube-embed')">
                                            <img class="pb-3" src="{{static_asset('default-image/content-icon/youtube.png') }}"><br>
                                            <label>{{ __('youtube') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content-area">
                                {{-- all content  --}}
                            </div>
                            <!-- visibility section start -->
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('visibility') }}</h2>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-12 col-md-4">
                                        <div class="form-title">
                                            <label for="visibility">{{ __('visibility') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="visibility" id="visibility_show" checked value="1"
                                                   class="custom-control-input">
                                            <span class="custom-control-label">{{ __('show') }}</span>
                                        </label>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" name="visibility" id="visibility_hide" value="0"
                                                   class="custom-control-input">
                                            <span class="custom-control-label">{{ __('hide') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-8 col-md-4">
                                        <div class="form-title">
                                            <label for="featured_post">{{ __('add_to_featured') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" id="featured_post" name="featured"
                                                   class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-8 col-md-4">
                                        <div class="form-title">
                                            <label for="add_to_breaking">{{ __('add_to_breaking') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" id="add_to_breaking" name="breaking"
                                                   class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-8 col-md-4">
                                        <div class="form-title">
                                            <label for="add_to_slide">{{ __('add_to_slider') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" id="add_to_slide" name="slider"
                                                   class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-8 col-md-4">
                                        <div class="form-title">
                                            <label for="recommended">{{ __('add_to_recommended') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" id="recommended" name="recommended"
                                                   class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-8 col-md-4">
                                        <div class="form-title">
                                            <label for="editor_picks">{{ __('add_to_editor_picks') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" id="editor_picks" name="editor_picks"
                                                   class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row p-l-15">
                                    <div class="col-8 col-md-4">
                                        <div class="form-title">
                                            <label
                                                for="auth_required">{{ __('show_only_to_authenticate_users') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" id="auth_required" name="auth_required"
                                                   class="custom-control-input">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- visibility section end -->
                            <!-- SEO section start -->
                            <div class="add-new-page  bg-white p-20 m-b-20" id="post_meta">
                                <div class="block-header">
                                    <h2>{{ __('seo_details') }}</h2>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="meta_title"><b>{{ __('title') }}</b> ({{ __('meta_title') }})</label>
                                        <input id="meta_title" class="form-control meta" value="" data-type="title"
                                               name="meta_title">
                                        <p class="display-nothing alert alert-danger mt-2" role="alert">
                                            {{__('current_characters')}}: <span class="characters"></span>, {{ __('meta_title').' '. __('should_bd') .' '. __('in_between') .' '. '30-60 ' . __('characters') }}
                                        </p>
                                        <p class="display-nothing alert alert-success mt-2" role="alert">
                                            {{__('current_characters')}}: <span class="characters"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post-keywords" class="col-form-label"><b>{{ __('keywords') }}</b>
                                        </label>
                                        <input id="post-keywords" name="meta_keywords"
                                               value="" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post_tags" class="col-form-label">{{ __('tags') }}
                                            ({{ __('meta_tags') }})</label>
                                        <input id="post_tags" name="tags" type="text" value="{{ old('tags') }}"
                                               data-role="tagsinput" class="form-control"/>

                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post_desc">
                                            <b>{{ __('description') }}</b> ({{ __('meta_description') }})
                                        </label>
                                        <textarea class="form-control meta" id="meta_description"
                                                  value="" name="meta_description" data-type="description"
                                                  rows="3"></textarea>
                                        <p class="display-nothing alert alert-danger mt-2" role="alert">
                                            {{__('current_characters')}}: <span class="characters"></span>, {{ __('meta_description').' '. __('should_bd') .' '. __('in_between') .' '. '50-160 ' . __('characters') }}
                                        </p>
                                        <p class="display-nothing alert alert-success mt-2" role="alert">
                                            {{__('current_characters')}}: <span class="characters"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- SEO section end -->
                        </div>
                        <!-- Main Content section end -->

                        <!-- right sidebar start -->
                        <div class="col-12 col-lg-3">

                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <!-- Upload video tab start -->
                                <div class="add-video-tab">
                                    <nav>
                                        <div class="nav nav-tabs m-b-20" id="nav-tab" role="tablist">
                                            <a class="nav-item nav-link active" id="upload-video-file" data-toggle="tab"
                                               href="#upload-video" role="tab">{{ __('upload_video') }}</a>
                                            <a class="nav-item nav-link" id="video-link" data-toggle="tab"
                                               href="#video_by_link" role="tab">{{ __('remove_video') }}</a>
                                        </div>
                                    </nav>

                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="upload-video" role="tabpanel">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <button type="button" id="btnVideoModal" class="btn btn-primary"
                                                                data-toggle="modal"
                                                                data-target=".video-modal-lg">{{ __('add_video') }}</button>
                                                        <input id="video_id" name="video_id" type="hidden"
                                                               class="form-control video_id">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    {{-- <label>{{ __('video_preview') }}</label> --}}
                                                    <div class="form-group">
                                                        <div class="form-group text-center">
                                                            <img src="{{static_asset('default-image/default-video-100x100.png') }} "
                                                                 id="video_thumb" width="200"
                                                                 height="200" alt="image"
                                                                 class="img-responsive img-thumbnail video_preview">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="video_by_link" role="tabpanel">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="video_url_type"
                                                               class="col-form-label">{{ __('video_url_type') }}</label>
                                                        <select id="video_url_type" name="video_url_type"
                                                                class="form-control">
                                                            <option value="">{{ __('select_option') }}</option>
                                                            <option value="mp4_url">MP4 url</option>
                                                            <option value="youtube_url">Youtube url</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="video_url"
                                                               class="col-form-label">{{ __('video_url') }}</label>
                                                        <input id="video_url" name="video_url" type="text"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Upload video tab end -->
                                <div class="form-group">
                                    <!-- Large modal -->
                                    <button type="button" class="btn btn-primary btn-image-modal" data-id="2"
                                            data-toggle="modal"
                                            data-target=".image-modal-lg">{{ __('add_video_thumbnail') }}</button>
                                    <input id="video_thumbnail_id" name="video_thumbnail_id" type="hidden"
                                           class="form-control image_id">
                                </div>
                                <div class="form-group">
                                    <div class="form-group text-center">
                                        <img src="{{static_asset('default-image/default-100x100.png') }} " id="video_thumb_preview"
                                             width="200" height="200" alt="image" class="img-responsive img-thumbnail image_preview">
                                    </div>
                                </div>
                            </div>

                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('post_thumbnail') }}</h2>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <!-- Large modal -->
                                        <div id="image_library_selection">
                                            <button type="button" id="btn_image_modal"
                                                    class="btn btn-primary btn-image-modal" data-id="0"
                                                    data-toggle="modal"
                                                    data-target=".image-modal-lg">{{ __('add_image') }}</button>
                                            <input id="image_id" name="image_id" type="hidden" class="form-control image_id">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="form-group text-center">
                                            <img src="{{static_asset('default-image/default-100x100.png') }} " image-data-id='img1'
                                                 id="image_preview" width="200" height="200" alt="image"
                                                 class="img-responsive img-thumbnail image_preview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post_language">{{ __('select_language') }}*</label>
                                        <select class="form-control dynamic-category" id="post_language" name="language"
                                        data-dependent="category_id" required>
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
                                        <label for="category_id">{{ __('category') }}*</label>
                                       {{--  <select class="form-control dynamic" id="category_id" name="category_id"
                                               data-dependent="sub_category_id" required>
                                            <option value="">{{ __('select_category') }}</option>
                                        </select> --}}

                                        <select class="form-control dynamic" id="category_id" name="category_id"
                                                data-dependent="sub_category_id" required>
                                            <option value="">{{ __('select_category') }}</option>
                                            @foreach ($categories as $category)
                                                <option
                                                    value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="sub_category_id">{{ __('sub_category') }}</label>
                                        <select class="form-control dynamic" id="sub_category_id" name="sub_category_id">
                                            <option value="">{{ __('select_sub_category') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="col-md-12">
                                    <div class="block-header">
                                        <h2>{{ __('article_detail') }}</h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-control custom-radio detail-control-inline">
                                                <input type="radio" name="layout" id="detail_style_1" value="default" checked  class="custom-control-input">
                                                <span class="custom-control-label"></span>
                                            </label>
                                            <img src="{{static_asset('default-image/Detail/detail_1.png') }}" alt="" class="img-responsive cat-block-img">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-control custom-radio detail-control-inline">
                                                <input type="radio" name="layout" id="detail_style_2" value="style_2"  class="custom-control-input">
                                                <span class="custom-control-label"></span>
                                            </label>
                                            <img src="{{static_asset('default-image/Detail/detail_2.png') }}" alt="" class="img-responsive cat-block-img">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="custom-control custom-radio detail-control-inline">
                                                <input type="radio" name="layout" id="detail_style_3" value="style_3"  class="custom-control-input">
                                                <span class="custom-control-label"></span>
                                            </label>
                                            <img src="{{static_asset('default-image/Detail/detail_3.png') }}" alt="" class="img-responsive cat-block-img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('publish') }}*</h2>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control" id="post_status" name="status" required>
                                            <option value="1">{{ __('published') }}</option>
                                            <option value="0">{{ __('draft') }}</option>
                                            <option value="2">{{ __('scheduled') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 divScheduleDate">
                                    <label for="scheduled_date">{{ __('schedule_date') }}</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="scheduled_date"><i
                                                class="fa fa-calendar-alt"></i></label>
                                        <input type="text" class="form-control date" id="scheduled_date"
                                               name="scheduled_date"/>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="custom-control" for="btnSubmit"></label>
                                        <button type="submit" name="btnSubmit" class="btn btn-primary pull-right"><i
                                                class="m-r-10 mdi mdi-plus"></i>{{ __('create_post') }}</button>
                                        <label class="" for="btnSubmit"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- right sidebar end -->
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        <!-- page info end-->
        </div>
    </div>


    <input type="hidden" value="0" id="content_number">


@endsection
@section('script')


    <script>
        $(document).ready(function () {

           $('.dynamic-category').change(function () {
                if ($(this).val() != '') {
                    var select = $(this).attr("id");
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        url: "{{ route('category-fetch') }}",
                        method: "POST",
                        data: {select: select, value: value, _token: _token},
                        success: function (result) {
                            $('#' + dependent).html(result);
                        }

                    })
                }
            });


            $('#post_language').change(function () {
                $('#category_id').val('');
                $('#sub_category_id').val('');
            });

            $('.dynamic').change(function () {
                if ($(this).val() != '') {
                    var select = $(this).attr("id");
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        url: "{{ route('subcategory-fetch') }}",
                        method: "POST",
                        data: {select: select, value: value, _token: _token},
                        success: function (result) {
                            $('#' + dependent).html(result);
                        }

                    })
                }
            });


            $('#category').change(function () {
                $('#sub_category_id').val('');
            });
        });
    </script>
    <script>
        addContent = function(value) {

            var content_number = $("#content_number").val();
            content_number++;

            $.ajax({
                url: "{{ route('add-content') }}",
                method: "GET",
                data: {value: value, content_count:content_number},
                success: function (result) {
                    $('.content-area').append(result);
                    $("#content_number").val(content_number);

                    // auto scrolling to newly added element
                    var newlyAdded = 'content_'+content_number;
                    $('body, html').animate({ scrollTop: $('.'+newlyAdded).offset().top }, 1000);
                }

            });
        }


        $(document).on("click", ".add-new-page .row_remove", function () {
            let element = $(this).parents('.add-new-page');
            //element.remove(1000);
            element.hide("slow", function(){ $(this).remove(); })
        });
    </script>

    <script type="text/javascript" src="{{static_asset('js/post.js') }}"></script>
    <script src="{{static_asset('js/tagsinput.js')}}"></script>
@endsection
