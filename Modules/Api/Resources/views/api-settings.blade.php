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
@section('api-settings')
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

                                        <a class="nav-item nav-link active" id="api-settings"
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

                                        <div class="col-sm-12 mb-4">
                                            <div class="form-group">
                                                <label for="app_server_url"
                                                       class="col-form-label">{{ __('api_server_url') }}</label>
                                                <input id="app_server_url" class="form-control"
                                                       value="{{ url('api') }}" onclick="copyInput('app_server_url')">
                                                       <small>{{ __('copy_&_paste_this_to_app_source_code') }}</small>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group position-relative">
                                                        <label for="api_key_for_app"
                                                               class="col-form-label">{{ __('api_key_for_app') }}</label>
                                                        <div class="d-flex setting">
                                                            <input id="api_key_for_app" class="form-control" name="api_key_for_app"
                                                                   value="{{ settingHelper('api_key_for_app') }}">
                                                            @if(Sentinel::getUser()->hasAccess(['api_write']))
                                                                <button type="button" class="rounded mx-1 btn-primary"  onclick="getKey()"><i class="fas fa-refresh"></i></button>
                                                            @endif
                                                            <button type="button" class="rounded btn-primary" onclick="copyInput('api_key_for_app')"><i class="fas fa-copy"></i></button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if(Sentinel::getUser()->hasAccess(['api_write']))
                                            <div class="row">
                                                <div class="col-12 m-t-20">
                                                    <div class="form-group form-float form-group-sm text-right">
                                                        <button type="submit"
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

@section('script')
<script type="text/javascript">
        function copyInput(element) {

            var copyText = document.getElementById(element);

            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");


            swal({
                title: "{{ __('copied') }}",
                text: copyText.value+ "\n{{ __('now_just_paste_into_android_configuration_file') }}",
                icon: "success",
                confirmButtonText: false
            })

        }

        function getKey() {
            var api_key_for_app = "";
            var string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()";

            for (var i = 0; i < 32; i++)
                api_key_for_app += string.charAt(Math.floor(Math.random() * string.length));

            $("#api_key_for_app").val(api_key_for_app);
        }
    </script>

@endsection


