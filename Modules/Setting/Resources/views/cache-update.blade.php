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
@section('cache-route-config')
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

                                        <a class="nav-item nav-link" id="general-settings"
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
                                        <a class="nav-item nav-link active" id="setting-config-cache"
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
                                    <div class="tab-pane fade show active" role="tabpanel">
                                        <div class="block-header">
                                            <div>
                                                <span class="text-warning">{{ __('please_make_sure_you_have_set_writable_permision_following_folder') }}</span>
                                            </div>
                                            <strong><span>./bootstrap/cache</span></strong>

                                            <h2>{{ __('cache_update') }}</h2>
                                        </div>
                                        {!!  Form::open(['route' => 'cache-update', 'method' => 'post' ]) !!}


                                        <div class="row p-l-15">
                                            <div class="col-12 col-md-4">
                                                <div class="form-title">
                                                    <label for="visibility">{{ __('cache') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-3 col-md-2">
                                                <label class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="config_cache" id="config_cache_enable"
                                                           value="enable"
                                                           {{( File::exists(base_path().'/bootstrap/cache/config.php') || File::exists(base_path().'/bootstrap/cache/routes.php')) ? 'checked':"" }} class="custom-control-input">
                                                    <span class="custom-control-label">{{ __('enable') }}</span>
                                                </label>
                                            </div>
                                            <div class="col-3 col-md-2">
                                                <label class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="config_cache" id="config_cache_disable"
                                                           value="disable"
                                                           class="custom-control-input" {{ (!File::exists(base_path().'/bootstrap/cache/config.php') && !File::exists(base_path().'/bootstrap/cache/routes.php')) ? 'checked':"" }}>
                                                    <span class="custom-control-label">{{ __('disable') }}</span>
                                                </label>
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
                                        {{-- {{ Form::close() }} --}}
                                    </div>
                                    <!-- single tab content end -->
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--  tab end -->
                </div>
            </div>
            <!-- Main Content Section End -->
        </div>
    </div>
@endsection

