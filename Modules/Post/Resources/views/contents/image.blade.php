<div class="add-new-page content_{{$content_count}} bg-white p-20 m-b-20" id="image_content_{{$content_count}}">
    <input type="hidden" value="{{ $content_count }}" id="content_count">
    <div class="row">
        <div class="col-12">
            <div class="right"><button type="button" class="btn btn-danger px-1 py-0 float-right row_remove"><i class="m-r-0 mdi mdi-minus"></i></button></div>
        </div>
        <div class="col-12 p-t-20 p-l-15">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <!-- Large modal -->
                        <button type="button" id="btn_image_modal"
                                class="btn btn-primary btn-image-modal" data-id="1" data-toggle="modal"
                                data-target=".image-modal-lg">{{ __('add_image') }}</button>
                        <input id="image_id_content" name="new_content[{{$content_count}}][image][image_id]" type="hidden" class="form-control image_id" value="{{isset($content)? $content['image'][0]['image_id']:''}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-group text-center">
                            @if(isset($content) && $content['image'][0]['image_id'] != "")
                            @php
                            $image = $content['image'][0]['image'];
                            @endphp
                            @if(isFileExist(@$image, $result = @$image->thumbnail))
                                <img src=" {{basePath($image)}}/{{ $result }} "  id="image_preview_content"
                                    width="200" height="200" alt="image"
                                    class="img-responsive img-thumbnail image_preview">
                            @else
                                <img src="{{static_asset('default-image/default-100x100.png') }} " id="image_preview_content"
                                width="200" height="200" alt="image"
                                class="img-responsive img-thumbnail image_preview">
                            @endif
                            @else
                            <img src="{{static_asset('default-image/default-100x100.png') }} " id="image_preview_content"
                                    width="200" height="200" alt="image"
                                    class="img-responsive img-thumbnail image_preview">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
