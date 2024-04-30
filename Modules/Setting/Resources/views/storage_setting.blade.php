@extends('common::layouts.master')
@section('settings')
    aria-expanded="true"
@endsection
@section('s-show')
    show
@endsection
@section('settings_active')
    active
@endsection
@section('setting-storage')
    active
@endsection

@section('content')
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <!-- page info start-->
            @if(session('error'))
                <div id="error_m" class="alert alert-danger">
                    {{session('error')}}
                </div>
            @endif
            @if(session('success'))
                <div id="success_m" class="alert alert-success">
                    {{ session('success') }}
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

            <input type="hidden" name="url" id="url" value="{{url('/')}}">

            <div class="row clearfix">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="add-new-page  bg-white p-0 m-b-20">

                                <nav>
                                    <div class="nav m-b-20 setting-tab" id="nav-tab" role="tablist">

                                        <a class="nav-item nav-link" id="general-settings"
                                           href="{{ route('setting-general') }}"
                                           role="tab">{{ __('general_settings') }}</a>
                                        <a class="nav-item nav-link" id="contact-settings"
                                           href="{{ route('setting-company') }}"
                                           role="tab">{{ __('company_informations') }}</a>
                                        <a class="nav-item nav-link" id="mail-settings"
                                           href="{{ route('setting-email') }}" role="tab">{{ __('email_settings') }}</a>
                                        <a class="nav-item nav-link active" id="storage-settings"
                                           href="{{ route('setting-storage') }}"
                                           role="tab">{{ __('storage_settings') }}</a>
                                        <a class="nav-item nav-link" id="seo-settings" href="{{ route('setting-seo') }}"
                                           role="tab">{{ __('seo_settings') }}</a>
                                        <a class="nav-item nav-link" id="recaptcha-settings"
                                           href="{{ route('setting-recaptcha') }}"
                                           role="tab">{{ __('recaptcha_settings') }}</a>
                                        <a class="nav-item nav-link" id="setting-url" href="{{ route('settings-url') }}"
                                           role="tab">{{ __('url_settings') }}</a>

                                        <a class="nav-item nav-link" id="setting-ffmpeg"
                                           href="{{ route('settings-ffmpeg') }}" role="tab">{{ __('ffmpeg_settings') }}</a>

                                        <a class="nav-item nav-link" id="setting-custom"
                                           href="{{ route('setting-custom-header-footer') }}">{{ __('custom_header_footer') }}</a>
                                        <a class="nav-item nav-link" id="cron-information"
                                           href="{{ route('cron-information') }}">{{ __('cron_information') }}</a>
                                        <a class="nav-item nav-link" id="preference-control"
                                           href="{{ route('preferene-control') }}">{{ __('preference_setting') }}</a>
                                        <a class="nav-item nav-link" id="setting-social-login"
                                           href="{{ route('setting-social-login') }}">{{ __('social_login_settings') }}</a>
                                        <a class="nav-item nav-link" id="setting-config-cache"
                                           href="{{ route('cache') }}">{{ __('cache') }}</a>
                                           <a class="nav-item nav-link" id="update-database"
                                           href="{{ route('update-database') }}">{{ __('update') }}</a>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="tab-content" id="nav-tabContent">

                                    <!-- single tab content start -->
                                    <div class="tab-pane fade show active" id="storage_settings" role="tabpanel">
                                        {!!  Form::open(['route' => 'update-settings', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'update-settings']) !!}
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="default_storage"
                                                       class="col-form-label">{{ __('default_storage') }}</label>
                                                <select name="default_storage" id="default_storage"
                                                        class="form-control">
                                                    <option @if( settingHelper('default_storage') =='local') selected
                                                            @endif value="local">local
                                                    </option>
                                                    <option @if( settingHelper('default_storage') =='s3') selected
                                                            @endif value="s3">AWS s3
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="inputFilds {{settingHelper('default_storage') == 'local'? 'display-nothing':''}}" id="s3Div">
                                             <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="aws_access_key_id" class="col-form-label">{{ __('aws_access_key_id') }}</label>
                                                    <input name="aws_access_key_id"value="{{ strtolower(\Config::get('app.demo_mode') == 'yes') ? '*************' : settingHelper('aws_access_key_id') }}" id="aws_access_key_id" class="form-control" type="text" placeholder="IKIAQYWNX08HZZEHABCD">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="aws_secret_access_key" class="col-form-label">{{ __('aws_secret_access_key') }}</label>
                                                    <input name="aws_secret_access_key"value="{{ strtolower(\Config::get('app.demo_mode') == 'yes') ? '***************' : settingHelper('aws_secret_access_key') }}" id="aws_secret_access_key" class="form-control" type="text" placeholder="aBcDbBa64yvovEhS4AbCdZCriyQQ6nRf3BmhAbCC">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="aws_default_region" class="col-form-label">{{ __('aws_default_region') }}</label>
                                                    <input name="aws_default_region"value="{{ strtolower(\Config::get('app.demo_mode') == 'yes') ? '***************' : settingHelper('aws_default_region') }}" id="aws_default_region" class="form-control" type="text" placeholder="ap-south-1">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="aws_bucket" class="col-form-label">{{ __('aws_bucket') }}</label>
                                                    <input name="aws_bucket" value="{{ strtolower(\Config::get('app.demo_mode') == 'yes') ? '***************' : settingHelper('aws_bucket') }}" id="aws_bucket" class="form-control" type="text" placeholder="demo123">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 m-t-20">
                                                <div class="form-group form-float form-group-sm text-right">
                                                    <button type="submit" name="status"
                                                            class="btn btn-primary pull-right"><i
                                                            class="m-r-10 mdi mdi-content-save-all"></i>{{ __('save_changes') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                    <!-- single tab content end -->
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--  tab end -->
                </div>
            </div>
            <!-- right sidebar end -->
        </div>
    </div>

@endsection

