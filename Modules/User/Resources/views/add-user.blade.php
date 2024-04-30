@extends('common::layouts.master')

@section('users_management')
    active
@endsection
@section('users_management_')
    aria-expanded="true"
@endsection
@section('u-show')
    show
@endsection
@section('user-create')
    active
@endsection
@section('modal')
    @include('gallery::image-gallery')
@endsection

@section('content')
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <!-- page info start-->
            {!!  Form::open(['route' => 'user-store','method' => 'post', 'enctype'=>'multipart/form-data']) !!}

            @csrf
            <div class="row clearfix">
                <div class="col-12">
                    <div class="row">
                        <!-- Main Content section start -->
                        <div class="col-12 col-lg-6">
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('add_user') }}</h2>
                                </div>
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
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="first_name"
                                                   class="col-form-label">{{ __('first_name') }} *</label>
                                            <input id="first_name" name="first_name" value="{{ old('first_name') }}"
                                                   type="text" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="last_name" class="col-form-label">{{ __('last_name') }}  *</label>
                                            <input id="last_name" name="last_name" value="{{ old('last_name') }}"
                                                   type="text" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email" class="col-form-label">{{ __('email') }}  *</label>
                                            <input id="email" name="email" type="email" value="{{ old('email') }}"
                                                   class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="phone" class="col-form-label">{{ __('phone') }}  *</label>
                                            <input id="phone" name="phone" type="phone" value="{{ old('phone') }}"
                                                   class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="dob" class="col-form-label">{{ __('dob') }}  *</label>
                                            <input id="dob" name="dob" type="date" max="{{ date("Y-m-d") }}"
                                                   pattern="\d{4}-\d{2}-\d{2}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="gender" class="col-form-label">{{ __('gender') }}  *</label>
                                            <select class="form-control" id="gender" name="gender" required>
                                                <option>{{__('select_option')}}</option>
                                                @foreach (__('genders.genderType') as $value => $item)
                                                    <option value="{{ $value }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="user_role">{{ __('role') }}  *</label>
                                            <select class="form-control" id="user_role" name="user_role" required>
                                                @foreach ($roles as $role)

                                                    <option value="{{ $role->slug }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="password" class="col-form-label">{{ __('password') }}  *</label>
                                            <input id="password" name="password" value="{{ old('password') }}"
                                                   type="password" class="form-control"
                                                   data-parsley-minlength="6"
                                                   data-parsley-required
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="password_confirmation"
                                                   class="col-form-label">{{ __('password_confirmation') }}  *</label>
                                            <input id="password_confirmation" name="password_confirmation"
                                                   value="{{ old('password_confirmation') }}" type="password"
                                                   class="form-control"
                                                   data-parsley-equalto="#password"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="profile_image" class="upload-file-btn btn btn-primary"><i
                                                    class="fa fa-folder input-file"
                                                    aria-hidden="true"></i> {{ __('select_image')}}</label>
                                            <input id="profile_image" name="profile_image" onChange="swapImage(this)" data-index="0"
                                                   type="file" class="form-control d-none" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="form-group text-center">
                                                <img src="{{static_asset('default-image/user.jpg') }} " data-index="0"
                                                     width="200" height="200" alt="image"
                                                     class="img-responsive img-thumbnail">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 m-t-20">
                                        <div class="form-group form-float form-group-sm text-right">
                                            <button type="submit" name="status" value="1"
                                                    class="btn btn-primary pull-right"><i
                                                    class="m-r-10 mdi mdi-account-plus"></i>{{ __('add_user') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Main Content section end -->
                    </div>
                </div>
            </div>
        {{ Form::close() }}
        <!-- page info end-->
        </div>
    </div>

@endsection
