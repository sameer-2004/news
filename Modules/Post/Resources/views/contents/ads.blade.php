
<div class="add-new-page content_{{$content_count}} bg-white p-20 m-b-20">
    <div class="row">
        <div class="col-12">
            <div class="right"><button type="button" class="btn btn-danger px-1 py-0 float-right row_remove"><i class="m-r-0 mdi mdi-minus"></i></button></div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="language">{{ __('ads') }}</label>
                <select class="form-control" name="new_content[{{$content_count}}][ads][ads]" id="ad">
                    @foreach ($ads as $value => $ad)
                        <option value="{{$ad->id}}" {{isset($content)? ($content['ads'][0]['ads'] == $ad->id? 'selected':''):''}}>{{$ad->ad_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
</div>
</div>
