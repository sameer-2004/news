@extends('common::layouts.master')
@section('api')
    aria-expanded="true"
@endsection
@section('api-show')
    show
@endsection
@section('api_active')
    active
@endsection
@section('ads-config')
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

                                        <a class="nav-item nav-link" id="api-settings"
                                           href="{{ route('api-settings') }}"
                                           role="tab">{{ __('api_settings') }}</a>
                                        <a class="nav-item nav-link" id="android-settings"
                                           href="{{ route('android-settings') }}"
                                           role="tab">{{ __('android_settings') }}</a>

                                        <a class="nav-item nav-link" id="ios-settings"
                                           href="{{ route('ios-settings') }}"
                                           role="tab">{{ __('ios_settings') }}</a>

                                        <a class="nav-item nav-link" id="app-config"
                                           href="{{ route('app-config') }}"
                                           role="tab">{{ __('app_config') }}</a>

                                        <a class="nav-item nav-link active" id="ads-config"
                                           href="{{ route('ads-config') }}"
                                           role="tab">{{ __('ads_config') }}</a>

                                        <a class="nav-item nav-link" id="app-intro"
                                           href="{{ route('app-intro') }}"
                                           role="tab">{{ __('app_intro') }}</a>

                                    </div>
                                </nav>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="ads-config" role="tabpanel">
                                        {!!  Form::open(['route' => 'update-settings', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'update-settings']) !!}

                                        <div class="block-header">
                                            <h2>{{__('ads_config')}}</h2>
                                        </div>
                                        <div class="row p-l-15">
                                            <div class="col-12 col-md-4">
                                                <div class="form-title">
                                                    <label for="visibility">{{ __('ads_enable') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-3 col-md-2">
                                                <label class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="ads_enable" id="visibility_show"
                                                           value="true"
                                                           {{ settingHelper('ads_enable') == 'true'? 'checked':"" }} class="custom-control-input">
                                                    <span class="custom-control-label">{{ __('yes') }}</span>
                                                </label>
                                            </div>
                                            <div class="col-3 col-md-2">
                                                <label class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="ads_enable" id="visibility_hide"
                                                           value="false"
                                                           class="custom-control-input" {{ settingHelper('ads_enable') == 'false'? 'checked':"" }}>
                                                    <span class="custom-control-label">{{ __('no') }}</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="admob_app_id"
                                                       class="col-admob_app_id-label">{{ __('admob_app_id') }}</label>
                                                <input id="admob_app_id" class="form-control" name="admob_app_id"
                                                       value="{{ settingHelper('admob_app_id') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="admob_banner_ads_id"
                                                       class="col-form-label">{{ __('admob_banner_ads_id') }}</label>
                                                <input id="admob_banner_ads_id" class="form-control" name="admob_banner_ads_id"
                                                       value="{{ settingHelper('admob_banner_ads_id') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="admob_interstitial_ads_id"
                                                       class="col-form-label">{{ __('admob_interstitial_ads_id') }}</label>
                                                <input id="admob_interstitial_ads_id" class="form-control" name="admob_interstitial_ads_id"
                                                       value="{{ settingHelper('admob_interstitial_ads_id') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="admob_native_ads_id"
                                                       class="col-form-label">{{ __('admob_native_ads_id') }}</label>
                                                <input id="admob_native_ads_id" class="form-control" name="admob_native_ads_id"
                                                       value="{{ settingHelper('admob_native_ads_id') }}">
                                            </div>
                                        </div>
                                        
                                        @if(Sentinel::getUser()->hasAccess(['api_write']))
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


