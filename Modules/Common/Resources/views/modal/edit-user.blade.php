@php
    $user= Modules\User\Entities\User::find($param[0]);
@endphp

{!!  Form::open(['route' => ['update-user-info',$param[0]], 'method' => 'post','enctype'=>'multipart/form-data']) !!}
    <div class="modal-body">

        <div class="form-group">
            <label for="first_name" class="col-form-label">{{ __('first_name') }}</label>
            <input id="first_name" name="first_name" value="{{ $user->first_name }}" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="last_name" class="col-form-label">{{ __('last_name') }}</label>
            <input id="last_name" name="last_name" value="{{ $user->last_name }}" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="email" class="col-form-label">{{ __('email') }}</label>
            <input id="email" disabled value="{{ $user->email }}" type="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="phone" class="col-form-label">{{ __('phone') }}</label>
            <input id="phone" name="phone" value="{{ $user->phone }}" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="dob" class="col-form-label">{{ __('dob') }}</label>
            <input id="dob" name="dob" value="{{ $user->dob }}" type="date" max="{{ date('Y-m-d') }}" pattern="\d{4}-\d{2}-\d{2}" class="form-control">
        </div>
        <div class="form-group">
            <label for="newsletter" class="col-form-label">{{ __('gender') }}</label>

            <select class="form-control" name="gender" id="gender">
                <option>{{__('select_option')}}</option>
                @foreach (__('genders.genderType') as $value => $item)
                    <option @if($user->gender ==$value) Selected
                            @endif value="{{$value}}">{{$item}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="newsletter" class="col-form-label">{{ __('newsletter') }}</label>
            <select name="newsletter_enable" class="form-control">
                <option value="0" @if($user->newsletter_enable==0) selected @endif>{{ __('disable') }}</option>
                <option value="1" @if($user->newsletter_enable==1) selected @endif>{{ __('enable') }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="profile_image" class="upload-file-btn btn btn-primary"><i
                    class="fa fa-folder input-file"
                    aria-hidden="true"></i> {{ __('select_image')}}</label>
            <input id="profile_image" name="profile_image" onChange="swapImage(this)" data-index="0"
                   type="file" class="form-control d-none" accept="image/*">
        </div>
        <div class="form-group text-center">
            @if(profile_exist($user->profile_image) && $user->profile_image!=null)
                <img src="{{static_asset($user->profile_image)}}" data-index="0"
                     height="200" width="200" alt="img">
            @else
                <img src="{{static_asset('default-image/user.jpg') }}" height="200" width="200" data-index="0" alt="user" class="img-responsive ">
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="m-r-10 fas fa-window-close"></i>{{ __('close') }}</button>
        <button type="submit" class="btn btn-primary"><i class="m-r-10 mdi mdi-content-save-all"></i>{{ __('save') }}</button>
    </div>
{{ Form::close() }}
