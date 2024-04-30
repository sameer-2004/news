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
@section('app-intro')
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

                                        <a class="nav-item nav-link active" id="app-intro"
                                           href="{{ route('app-intro') }}"
                                           role="tab">{{ __('app_intro') }}</a>

                                    </div>
                                </nav>


                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="clearfix"></div>
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default bg-white">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="intro ">
                                                + {{__('add_intro')}}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <div class="tab-content" id="nav-tabContent">
                                                    <div class="tab-pane fade show active" id="ads-config" role="tabpanel">
                                                        {!!  Form::open(['route'=>'add-intro','method' => 'post', 'enctype' => "multipart/form-data"]) !!}
                                                        <div class="add-new-page  bg-white p-20 pt-0 m-b-20">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="language">{{ __('select_language') }} *</label>
                                                                    <select class="form-control" name="language" id="language">
                                                                        @foreach ($activeLang as  $lang)
                                                                            <option
                                                                                @if(App::getLocale()==$lang->code) Selected
                                                                                @endif value="{{$lang->code}}">{{$lang->name}}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="title" class="col-form-label">{{ __('title') }}
                                                                        *</label>
                                                                    <input id="title" name="title" type="text" class="form-control"
                                                                           required>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="description"
                                                                           class="col-form-label">{{ __('description') }}</label> *
                                                                    <textarea id="description" class="form-control" rows="9" cols="9"
                                                                              name="description"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="field" align="left">
                                                                    <label for="images" class="upload-file-btn btn btn-primary"><i
                                                                            class="fa fa-folder input-file"
                                                                            aria-hidden="true"></i> {{__('select_image')}} *
                                                                    </label><br>
                                                                    <input type="file" id="images" class="d-none " name="cover_image" required
                                                                    />
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12 m-t-20">
                                                                    <div class="form-group form-float form-group-sm text-right">
                                                                        <button type="submit" name="btnsubmit" class="btn btn-primary pull-right"><i
                                                                                class="m-r-10 mdi mdi-plus"></i>{{ __('add_intro') }}</button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        {{ Form::close() }}
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class="add-new-page  bg-white p-20 m-b-20">
                                    <div class="block-header m-b-20">
                                        <h2>{{__('app_intro')}}</h2>
                                    </div>
                                    <div class="table-responsive all-pages">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr role="row">
                                                <th>#</th>
                                                <th>{{ __('title') }}</th>
                                                <th>{{ __('language') }}</th>
                                                <th>{{ __('description') }}</th>
                                                <th>{{ __('image') }}</th>
                                                @if(Sentinel::getUser()->hasAccess(['api_write']) || Sentinel::getUser()->hasAccess(['api_delete']))
                                                    <th>{{ __('options') }}</th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($app_intros as $key => $app_intro)
                                                <tr role="row" class="odd" id="row_{{ $app_intro->id }}">
                                                    <td class="sorting_1">{{ $key+1 }}</td>
                                                    <td>{{ $app_intro->title }}</td>
                                                    <td>{{ $app_intro->language }}</td>
                                                    <td>{{ $app_intro->description }}</td>
                                                    <td>
                                                        <div class="post-image">
                                                            @if(isFileExist(@$app_intro, $result = @$app_intro->image))
                                                                <img
                                                                    src=" {{basePath($app_intro)}}/{{ $result }} "
                                                                    data-src="{{basePath($app_intro)}}/{{ $result }}"
                                                                    alt="image" class="img-responsive img-thumbnail lazyloaded" width="200"
                                                                    height="200">

                                                            @else
                                                                <img src="{{static_asset('default-image/default-100x100.png') }} " width="200"
                                                                     height="200" alt="image"
                                                                     class="img-responsive img-thumbnail">
                                                        @endif
                                                    </td>
                                                    @if(Sentinel::getUser()->hasAccess(['api_write']) || Sentinel::getUser()->hasAccess(['api_delete']))
                                                        <td>
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn bg-primary dropdown-toggle btn-select-option"
                                                                    type="button" data-toggle="dropdown">...
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu options-dropdown">
                                                                    @if(Sentinel::getUser()->hasAccess(['api_write']))
                                                                        <li>
                                                                            <a href="{{ route('edit-intro',['id'=>$app_intro->id]) }}"><i
                                                                                    class="fa fa-edit option-icon"></i>{{ __('edit') }}
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                    @if(Sentinel::getUser()->hasAccess(['api_delete']))
                                                                        <li>
                                                                            <a href="javascript:void(0)"
                                                                               onclick="delete_item('app_intros','{{ $app_intro->id }}')"><i
                                                                                    class="fa fa-trash option-icon"></i>{{ __('delete') }}
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="block-header">
                                                <h2>{{ __('showing')}} {{ $app_intros->firstItem()}} {{ __('to') }} {{ $app_intros->lastItem()}}
                                                    of {{ $app_intros->total()}} {{ __('entries') }}</h2>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 text-right">
                                            <div class="table-info-pagination float-right">
                                                <nav aria-label="Page navigation example">
                                                    {!! $app_intros->onEachSide(1)->links() !!}
                                                </nav>
                                            </div>
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


