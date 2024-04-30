
<div class="add-new-page content_{{$content_count}} bg-white p-20 m-b-20" id="video_content_{{$content_count}}">
    <input type="hidden" value="{{ $content_count }}" id="content_count">
    <div class="row">
        <div class="col-12">
            <div class="right"><button type="button" class="btn btn-danger px-1 py-0 float-right row_remove"><i class="m-r-0 mdi mdi-minus"></i></button></div>
        </div>
    </div>
    <!-- Upload video tab start -->
    <div class="add-video-tab">
        <nav>
            <div class="nav nav-tabs m-b-20" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="upload-video-file" data-toggle="tab"
                   href="#upload-video-{{ $content_count }}" role="tab">{{ __('upload_video') }}</a>
                <a class="nav-item nav-link" id="video-link" data-toggle="tab"
                   href="#video_by_link-{{ $content_count }}" role="tab">{{ __('remove_video') }}</a>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="upload-video-{{ $content_count }}" role="tabpanel">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <button type="button" id="btnVideoModal" class="btn btn-primary"
                                    data-toggle="modal"
                                    data-target=".video-modal-lg">{{ __('add_video') }}</button>
                            <input id="video_id_content" name="new_content[{{$content_count}}][video][video_id]" type="hidden"
                                   class="form-control video_id" value="{{isset($content)? $content['video'][0]['video_id']:''}}">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        {{-- <label>{{ __('video_preview') }}</label> --}}
                        <div class="form-group">
                            <div class="form-group text-center">

                                @if(isset($content) && $content['video'][0]['video_id'] != "")
                                @php
                                $video = $content['video'][0]['video'];

                                @endphp
                                    @if(isFileExist(@$video, $result = @$video->video_thumbnail))

                                    <img src=" {{basePath($video)}}/{{ $result }} "
                                        id="video_thumb_content" width="200"
                                        height="200" alt="image"
                                        class="img-responsive img-thumbnail video_preview" style="display: inline">
                                    @else
                                    <img src="{{static_asset('default-image/default-video-100x100.png') }} "
                                        id="video_thumb_content" width="200"
                                        height="200" alt="image"
                                        class="img-responsive img-thumbnail video_preview">
                                    @endif

                                @else

                                    <img src="{{static_asset('default-image/default-video-100x100.png') }} "
                                        id="video_thumb_content" width="200"
                                        height="200" alt="image"
                                        class="img-responsive img-thumbnail video_preview">

                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="video_by_link-{{ $content_count }}" role="tabpanel">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="video_url_type"
                                   class="col-form-label">{{ __('video_url_type') }}</label>
                            <select id="video_url_type" name="new_content[{{$content_count}}][video][video_url_type]"
                                    class="form-control">
                                <option value="">{{ __('select_option') }}</option>
                                <option value="mp4_url" {{isset($content)? ($content['video'][1]['video_url_type'] == 'mp4_url'? 'selected':''):''}}>MP4 url</option>
                                <option value="youtube_url" {{isset($content)? ($content['video'][1]['video_url_type'] == 'youtube_url'? 'selected':''):''}}>Youtube url</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="video_url"
                                   class="col-form-label">{{ __('video_url') }}</label>
                            <input id="video_url" name="new_content[{{$content_count}}][video][video_url]" type="text"
                                   class="form-control" value="{{isset($content)? $content['video'][2]['video_url']:''}}">
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
        <input id="video_thumbnail_id_content" name="new_content[{{$content_count}}][video][video_thumbnail_id]" type="hidden"
               class="form-control image_id" value="{{isset($content)? $content['video'][3]['video_thumbnail_id']:''}}">
    </div>
    <div class="form-group">
        <div class="form-group text-center">

                 @if(isset($content) && $content['video'][3]['video_thumbnail_id'] != "")

                    @php
                    $image = $content['video'][3]['video_thumbnail'];

                    @endphp
                    @if(isFileExist(@$image, $result = @$image->thumbnail))

                    <img src=" {{basePath($image)}}/{{ $result }} " id="video_thumb_preview_content"
                    width="200" height="200" alt="image" class="img-responsive img-thumbnail image_preview">
                    @else
                    <img src="{{static_asset('default-image/default-100x100.png') }} " id="video_thumb_preview_content"
                    width="200" height="200" alt="image" class="img-responsive img-thumbnail image_preview">
                    @endif

                    @else
                    <img src="{{static_asset('default-image/default-100x100.png') }} " id="video_thumb_preview_content"
            width="200" height="200" alt="image" class="img-responsive img-thumbnail image_preview">
                    @endif
        </div>
    </div>
</div>
