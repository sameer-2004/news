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
@section('android-settings')
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

                                        <a class="nav-item nav-link" id="api-settings"
                                           href="{{ route('api-settings') }}"
                                           role="tab">{{ __('api_settings') }}</a>
                                        <a class="nav-item nav-link active" id="android-settings"
                                           href="{{ route('android-settings') }}"
                                           role="tab">{{ __('android_settings') }}</a>

                                        <a class="nav-item nav-link" id="ios-settings"
                                           href="{{ route('ios-settings') }}"
                                           role="tab">{{ __('ios_settings') }}</a>

                                        <a class="nav-item nav-link" id="app-config"
                                           href="{{ route('app-config') }}"
                                           role="tab">{{ __('app_config') }}</a>

                                        <a class="nav-item nav-link" id="ads-config"
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
                                    <div class="tab-pane fade show active" id="general_settings" role="tabpanel">
                                        {!!  Form::open(['route' => 'update-settings', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'update-settings']) !!}



                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="latest_apk_version"
                                                       class="col-form-label">{{ __('latest_apk_version') }}</label>
                                                <input id="latest_apk_version" class="form-control" name="latest_apk_version"
                                                       value="{{ settingHelper('latest_apk_version') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="latest_apk_code"
                                                       class="col-latest_apk_code-label">{{ __('latest_apk_code') }}</label>
                                                <input id="latest_apk_code" class="form-control" name="latest_apk_code"
                                                       value="{{ settingHelper('latest_apk_code') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="apk_file_url"
                                                       class="col-form-label">{{ __('apk_file_url') }}</label>
                                                <input id="apk_file_url" class="form-control" name="apk_file_url"
                                                       value="{{ settingHelper('apk_file_url') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">

                                        <div class="form-group">
                                                <label for="whats_new_on_latest_apk"
                                                       class="col-form-label">{{ __('whats_new_on_latest_apk') }}</label>
                                                <textarea name="whats_new_on_latest_apk" id="whats_new_on_latest_apk"
                                                          class="form-control">{{ settingHelper('whats_new_on_latest_apk') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row p-l-15">
                                            <div class="col-12 col-md-4">
                                                <div class="form-title">
                                                    <label for="visibility">{{ __('update_skipable') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-3 col-md-2">
                                                <label class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="apk_update_skipable_status" id="visibility_show"
                                                           value="true"
                                                           {{ settingHelper('apk_update_skipable_status') == true? 'checked':"" }} class="custom-control-input">
                                                    <span class="custom-control-label">{{ __('yes') }}</span>
                                                </label>
                                            </div>
                                            <div class="col-3 col-md-2">
                                                <label class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="apk_update_skipable_status" id="visibility_hide"
                                                           value="false"
                                                           class="custom-control-input" {{ settingHelper('apk_update_skipable_status') == 'false'? 'checked':"" }}>
                                                    <span class="custom-control-label">{{ __('no') }}</span>
                                                </label>
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


