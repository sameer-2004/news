@extends('common::layouts.master')
@section('gallery-aria-expanded')
    aria-expanded="true"
@endsection
@section('gallery-show')
    show
@endsection
@section('gallery')
    active
@endsection
@section('app-intro')
    active
@endsection

@section('content')
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <!-- page info start-->
            <div class="row clearfix">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
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
                        </div>
                        <!-- Main Content section start -->
                        <div class="col-12 col-lg-5">
                            {!!  Form::open(['route'=>'add-album','method' => 'post', 'enctype' => "multipart/form-data"]) !!}
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header">
                                    <h2>{{ __('add_album') }}</h2>
                                </div>

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
                                        <label for="name" class="col-form-label">{{ __('album_name') }}
                                            *</label>
                                        <input id="name" name="name" type="text" class="form-control"
                                               required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="album-slug" class="col-form-label"><b>{{ __('slug') }}</b>
                                            ({{ __('slug_message') }})</label>
                                        <input id="album-slug" name="slug" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="post_tags" class="col-form-label">{{ __('tabs') }} ({{ __('hit_enter') }})</label>
                                        <input id="post_tags" name="tabs" type="text" value=""
                                               data-role="tagsinput" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="album-desc" class="col-form-label"><b>{{ __('description') }}</b>
                                            ({{ __('meta_tag') }})</label>
                                        <input id="album-desc" name="meta_description" type="text"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="album-keywords"
                                               class="col-form-label"><b>{{ __('keywords') }}</b> ({{ __('meta_tag') }})</label>
                                        <input id="album-keywords" name="meta_keywords" type="text"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="field" align="left">
                                        <label for="images" class="upload-file-btn btn btn-primary"><i
                                                class="fa fa-folder input-file"
                                                aria-hidden="true"></i> {{__('select_cover_image')}} *
                                        </label><br>
                                        <input type="file" id="images" class="d-none " name="cover_image" required
                                               />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 m-t-20">
                                        <div class="form-group form-float form-group-sm text-right">
                                            <button type="submit" name="btnsubmit" class="btn btn-primary pull-right"><i
                                                    class="m-r-10 mdi mdi-plus"></i>{{ __('add_album') }}</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {!! Form::close() !!}
                        </div>
                        <!-- Main Content section end -->

                        <!-- right sidebar start -->
                        <div class="col-12 col-lg-7">
                            <div class="add-new-page  bg-white p-20 m-b-20">
                                <div class="block-header m-b-20">
                                    <h2>{{__('albums')}}</h2>
                                </div>
                                <div class="table-responsive all-pages">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr role="row">
                                            <th>#</th>
                                            <th>{{ __('album_name') }}</th>
                                            <th>{{ __('language') }}</th>
                                            <th>{{ __('tabs') }}</th>
                                            <th>{{ __('cover_image') }}</th>
                                            @if(Sentinel::getUser()->hasAccess(['album_write']) || Sentinel::getUser()->hasAccess(['album_delete']))
                                                <th>{{ __('options') }}</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($albums as $key => $album)
                                            <tr role="row" class="odd" id="row_{{ $album->id }}">
                                                <td class="sorting_1">{{ $key+1 }}</td>
                                                <td>{{ $album->name }}</td>
                                                <td>{{ $album->language }}</td>
                                                <td class="text-capitalize">{{ $album->tabs }}</td>
                                                <td>
                                                    <div class="post-image">
                                                        @if(isFileExist(@$album, $result = @$album->thumbnail))
                                                            <img
                                                                src=" {{basePath($album)}}/{{ $result }} "
                                                                data-src="{{basePath($album)}}/{{ $result }}"
                                                                alt="image" class="img-responsive img-thumbnail lazyloaded">

                                                        @else
                                                            <img src="{{static_asset('default-image/default-100x100.png') }} " width="200"
                                                                 height="200" alt="image"
                                                                 class="img-responsive img-thumbnail">
                                                        @endif
                                                </td>
                                                @if(Sentinel::getUser()->hasAccess(['album_write']) || Sentinel::getUser()->hasAccess(['album_delete']))
                                                    <td>
                                                        <div class="dropdown">
                                                            <button
                                                                class="btn bg-primary dropdown-toggle btn-select-option"
                                                                type="button" data-toggle="dropdown">...
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu options-dropdown">
                                                                @if(Sentinel::getUser()->hasAccess(['album_write']))
                                                                    <li>
                                                                        <a href="{{ route('edit-album',['id'=>$album->id]) }}"><i
                                                                                class="fa fa-edit option-icon"></i>{{ __('edit') }}
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(Sentinel::getUser()->hasAccess(['album_delete']))
                                                                    <li>
                                                                        <a href="javascript:void(0)"
                                                                           onclick="delete_item('albums','{{ $album->id }}')"><i
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
                                            <h2>{{ __('showing')}} {{ $albums->firstItem()}} {{ __('to') }} {{ $albums->lastItem()}}
                                                of {{ $albums->total()}} {{ __('entries') }}</h2>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 text-right">
                                        <div class="table-info-pagination float-right">
                                            <nav aria-label="Page navigation example">
                                                {!! $albums->onEachSide(1)->links() !!}
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- right sidebar end -->
                    </div>
                </div>
            </div>
            <!-- page info end-->
        </div>
    </div>

    @section('script')
        <script src="{{static_asset('js/tagsinput.js')}}"></script>
        <script src="{{static_asset('js/tagsinput.js')}}"></script>
    @endsection
@endsection
