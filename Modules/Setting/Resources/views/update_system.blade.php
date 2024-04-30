@extends('common::layouts.master')
@section('system-update')
    active
@endsection
@section('content')
    <section class="container-fluid dashboard-content">
        <div class="alert fade show d-none alert_div" role="alert">
            <strong></strong> <span></span>
        </div>
        <div class="row block-element">
            <div class="col-sm-xs-12 col-md-6">
                <div class="card">
                    <div class="card-header input-title">
                        <h4>{{ __('System Update') }}</h4>
                    </div>
                    <div class="card-body card-body-paddding">
                        @php
                            $is_old         = settingHelper('version') < $latest_version;
                        @endphp
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 text-center">
                                    <div class="alert alert-{{ $is_old ? 'danger' : 'info'}}" {{ $is_old ? '' : 'style="background-color: #d1ecf1 !important"'}} >
                                        <h5 class="bold" style="font-size: 22px; font-weight: bold;">Your Version</h5>
                                        <p class="font-medium bold">{{ get_version(settingHelper('version')) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="alert alert-{{ $is_old ? 'success' : 'info'}}" style="background-color: #d1ecf1 !important">
                                        <h5 class="bold" style="font-size: 22px; font-weight: bold;">{{ __('latest_version') }}</h5>
                                        <p class="font-medium bold">{{ get_version($latest_version) }}</p>
                                    </div>
                                </div>
                            </div>
                            @if(!$is_old)
                                <div class="alert alert-success center">
                                    <p><i class="bx bx-check-circle"></i> {{ __('You are using the latest version') }}</p>
                                </div>
                            @else
                                <div class="alert alert-warning center">
                                    <p><i class="far fa-alarm-clock"></i> {{ __('An update is available') }}</p>
                                </div>

                                <div class="alert alert-success center" style="display: flex; justify-content: center;">
                                    <a class="btn text-black" tabindex="4" id="download_update"
                                            href="{{ route('do-system-update') }}" style="background: transparent;"><b>
                                        <i class="bx bx-download"></i> {{ __('Process Update') }} <span class="text-lowercase">({{ get_version($latest_version) }})</span>
                                            </b></a>
                                    <button type="submit" class="btn text-black disable_btn d-none" tabindex="4" style="background: transparent;" id="preloader">
                                        <img src="{{ static_asset('images/preloader.gif') }}" alt="updater" height="22">
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-xs-12 col-md-6">
                <div class="card">
                    <div class="card-header input-title">
                        <h4>{{ __('System Update Procedures') }}</h4>
                    </div>
                    <div class="card-body">
                        <p>{{ __('Please check this before hitting the update button') }}:</p>
                        <ol>
                            <li> It is strongly recommended to create a full backup of your current installation (files and database)</li>
                            
                            <li> Review the <a style="color: cornflowerblue" href="https://codecanyon.net/item/onno-laravel-news-magazine-script/29030619#item-description__change-log" target="_blank">Change Log</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
