<input type="hidden" value="{{$content_count}}" id="content_count">
@foreach ($images as $image)
	@if(!blank($image))
	    <div class="col-md-2" id="row_{{ $image->id }}">

	        @if(isFileExist(@$image, $result =@$image->thumbnail))
	            <img id='{{ $image->id }}' src="{{basePath(@$image)}}/{{ $result }}" alt="image"
	                 class="image img-responsive img-thumbnail">
	        @else
	            <img id='{{ $image->id }}' src="{{static_asset('default-image/default-100x100.png') }}" width="200" height="200"
	                 alt="image" class="image img-responsive img-thumbnail">
	        @endif

	    </div>
    @endif
@endforeach
