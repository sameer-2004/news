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
@section('update-database')
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

            {{--  {!!  Form::open(['route' => 'update-settings', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'update-settings']) !!} --}}
            <input type="hidden" name="url" id="url" value="{{url('/')}}">

            <div class="row clearfix">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="add-new-page  bg-white p-0 m-b-20">

                                <nav>
                                    <div class="nav m-b-20 setting-tab" id="nav-tab" role="tablist">

                                        <a class="nav-item nav-link " id="general-settings"
                                           href="{{ route('setting-general') }}"
                                           role="tab">{{ __('general_settings') }}</a>
                                        <a class="nav-item nav-link" id="contact-settings"
                                           href="{{ route('setting-company') }}"
                                           role="tab">{{ __('company_informations') }}</a>
                                        <a class="nav-item nav-link" id="mail-settings"
                                           href="{{ route('setting-email') }}" role="tab">{{ __('email_settings') }}</a>
                                        <a class="nav-item nav-link" id="storage-settings"
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
                                        <a class="nav-item nav-link active" id="update-database"
                                           href="{{ route('update-database') }}">{{ __('update') }}</a>
                                    </div>
                                </nav>


                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="general_settings" role="tabpanel">
                                        <div class="block-header">
                                            <div>
                                                <span class="text-warning">{{ __('please_make_sure_you_have_set_writable_permision_following_folder') }}</span>
                                            </div>
                                            <strong><span>./env</span></strong>
                                        </div>
                                        {!!  Form::open(['route' => 'update-database', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'update-settings']) !!}

                                        <div class="row">
                                            @if(settingHelper('version') == "" || settingHelper('version') < 131)
                                                <div class="col-12 m-t-20 text-center">
                                                    <div class="alert alert-danger">
                                                        <h4 class="bold">{{__('your_version')}}</h4>
                                                        <span class="font-medium bold">V{{ settingHelper('version') }}({{__('go_to_upgrade')}})</span>
                                                        <input type="hidden" name="version" value="131">
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-12 m-t-20 text-center">
                                                    <div class="alert alert-success">
                                                        <h4 class="bold">{{__('your_version')}}</h4>
                                                        <span class="font-medium bold">V{{ settingHelper('version') }}({{__('already_up_to_date')}})</span>

                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        @if(settingHelper('version') == "" || settingHelper('version') < 131)
                                            <div class="row">
                                                <div class="col-12 m-t-20">
                                                    <div class="form-group form-float form-group-sm text-right">
                                                        <button type="submit" name="status"
                                                                class="btn btn-primary pull-right"><i
                                                                class="m-r-10 mdi mdi-content-save-all"></i>{{ __('database_sync') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        {{ Form::close() }}
                                    </div>
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


