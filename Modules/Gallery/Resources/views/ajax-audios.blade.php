@foreach ($audios as $audio)
    <div class="col-md-2 " id="row_{{ $audio->id }}">
        <img id='{{ $audio->id }}' for="{{ $audio->id }}" src="{{static_asset('default-image/music-100x100.png') }}" alt="{{ $audio->audio_name }}"
             class="audio img-responsive img-thumbnail">

        <label class="audio_lvl" id="audio_lvl" for="{{ $audio->id }}">{{ $audio->audio_name }}</label>
    </div>
@endforeach
