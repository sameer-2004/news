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
@section('all-images-active')
    active
@endsection

@section('content')

    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">
            <!-- page info start-->
            <div class="admin-section">
                <div class="row clearfix m-t-30">
                    <div class="col-12">
                        <div class="navigation-list bg-white p-20">
                            <div class="add-new-header clearfix m-b-20">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="block-header">
                                            <h2>{{ __('gallery_images') }}</h2>
                                        </div>
                                    </div>
                                    @if(Sentinel::getUser()->hasAccess(['album_write']))
                                        <div class="col-6 text-right">
                                            <a href="{{ route('add-gallery-image') }}"
                                               class="btn btn-primary btn-sm btn-add-new"><i class="mdi mdi-plus"></i>
                                                {{ __('add_image') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="table-responsive all-pages">
                                <!-- Table Filter -->
                                <div class="row table-filter-container m-b-20">
                                    <div class="col-sm-12">
                                        {!!  Form::open(['route' => 'filter-image','method' => 'GET']) !!}
                                        <div class="item-table-filter">
                                            <p class="text-muted"><small>{{ __('language') }}</small></p>
                                            <select class="form-control dynamic-album" id="language" name="language" data-dependent="album_id">
                                                @foreach ($activeLang as  $lang)
                                                    <option
                                                        @if(App::getLocale()==$lang->code) Selected
                                                        @endif value="{{ $lang->code }}">{{ $lang->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="item-table-filter">
                                            <p class="text-muted"><small>{{ __('album') }}</small></p>
                                            <select class="form-control dynamic-album-tab text-capitalize" id="album_id" name="album_id"
                                                    data-dependent="album_tab" >
                                                <option value="">{{ __('all') }}</option>
                                                @foreach ($albums as $album)
                                                    <option value="{{ $album->id }}">{{ $album->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="item-table-filter">
                                            <p class="text-muted"><small>{{ __('tab') }}</small></p>
                                            <select class="form-control dynamic text-capitalize" id="album_tab" name="tab">
                                                <option value="">{{ __('all') }}</option>
                                            </select>
                                        </div>

                                        <div class="item-table-filter">
                                            <p class="text-muted"><small>{{__('search')}}</small></p>
                                            <input name="search_key" class="form-control" placeholder="{{__('search')}}"
                                                   type="search"  value="">
                                        </div>

                                        <div class="item-table-filter md-top-10 item-table-style">
                                            <p>&nbsp;</p>
                                            <button type="submit" class="btn bg-primary">{{ __('filter') }}</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                                <!-- Table Filter -->
                                <table class="table table-bordered table-striped" role="grid">
                                    <thead>
                                    <tr role="row">
                                        <th>#</th>
                                        <th>{{ __('image') }}</th>
                                        <th>{{ __('language') }}</th>
                                        <th>{{ __('album') }}</th>
                                        <th>{{ __('tab') }}</th>
                                        <th>{{ __('added_date') }}</th>
                                        @if(Sentinel::getUser()->hasAccess(['album_write']) || Sentinel::getUser()->hasAccess(['album_delete']))
                                            <th>{{ __('options') }}</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($galleryImages as $key=> $item)
                                            <tr id="row_{{ $item->id }}">
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <div class="post-image">
                                                    @if(isFileExist(@$item, $result = @$item->thumbnail))
                                                        <img
                                                            src=" {{basePath($item)}}/{{ $result }} "
                                                            data-src="{{basePath($item)}}/{{ $result }}"
                                                            alt="image" class="img-responsive img-thumbnail lazyloaded">

                                                    @else
                                                        <img src="{{static_asset('default-image/default-100x100.png') }} " width="200"
                                                             height="200" alt="image"
                                                             class="img-responsive img-thumbnail">
                                                    @endif
                                                </div> {{ $item->title }}
                                            </td>
                                            <td> {{ @$item->album['language'] }} </td>
                                            <td class="td-post-type">{{ @$item->album['name'] }}</td>
                                            <td class="td-post-type text-capitalize">{{ @$item->tab}}</td>
                                            <td>{{ $item->created_at }}</td>
                                            @if(Sentinel::getUser()->hasAccess(['album_write']) || Sentinel::getUser()->hasAccess(['album_delete']))
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn bg-primary dropdown-toggle btn-select-option"
                                                                type="button" data-toggle="dropdown">...<span
                                                                class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu options-dropdown">
                                                           
                                                            @if(Sentinel::getUser()->hasAccess(['album_write']))
                                                                <li>
                                                                    <a href="{{ route('edit-gallery-image',['id'=>$item->id]) }}"><i
                                                                            class="fa fa-edit option-icon"></i>{{ __('edit') }}
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if(Sentinel::getUser()->hasAccess(['album_delete']))
                                                                <li>
                                                                    <a href="javascript:void(0)"
                                                                       onclick="delete_item('gallery_images','{{ $item->id }}')"><i
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
                                        <h2>{{ __('Showing') }} {{ $galleryImages->firstItem()}} {{  __('to') }} {{ $galleryImages->lastItem()}} {{ __('of') }} {{ $galleryImages->total()}} {{ __('entries') }}</h2>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 text-right">
                                    <div class="table-info-pagination float-right">
                                        {!! $galleryImages->onEachSide(1)->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- page info end-->
        </div>
    </div>

    @section('script')
        <script>
            $(document).ready(function () {

                $('.dynamic-album').change(function () {
                    if ($(this).val() != '') {
                        var select = $(this).attr("id");
                        var value = $(this).val();
                        var dependent = $(this).data('dependent');
                        var _token = "{{ csrf_token() }}";
                        $.ajax({
                            url: "{{ route('album-fetch') }}",
                            method: "POST",
                            data: {select: select, value: value, _token: _token},
                            success: function (result) {
                                $('#' + dependent).html(result);
                            }

                        })
                    }
                });


                $('.dynamic-album-tab').change(function () {
                    if ($(this).val() != '') {
                        var select = $(this).attr("id");
                        var value = $(this).val();
                        var dependent = $(this).data('dependent');
                        var _token = "{{ csrf_token() }}";
                        $.ajax({
                            url: "{{ route('album-tabs-fetch') }}",
                            method: "POST",
                            data: {select: select, value: value, _token: _token},
                            success: function (result) {
                                $('#' + dependent).html(result);
                            }

                        })
                    }
                });

                $('#language').change(function () {
                    $('#album_tab').val('');
                    $('#album_id').val('');
                });
            });

            function set_cover(row_id) {
                var table_row = '#row_' + row_id
                var token =  "{{ csrf_token() }}";
                url = "{{ route('set-cover') }}"

                swal({
                    title: "{{ __('are_you_sure?') }}",
                    text: "{{ __('it_will_be_set_as_album_cover') }}",
                    icon: "info",
                    buttons: true,
                    buttons: ["{{ __('cancel') }}", "{{ __('add') }}"],
                    dangerMode: false,
                    closeOnClickOutside: false
                })
                    .then(function(confirmed){
                        if (confirmed){
                            $.ajax({
                                url: url,
                                type: 'post',
                                data: 'image_id=' + row_id +'&_token='+token,
                                dataType: 'json'
                            })
                                .done(function(response){
                                    swal.stopLoading();
                                    if(response.status == "success"){
                                        console.log(response);
                                        swal("{{ __('added') }}!", response.message, response.status);
                                        window.location.reload();
                                    }else{
                                        swal("Error!", response.message, response.status);
                                    }
                                })
                                .fail(function(){
                                    swal('Oops...', '{{ __('something_went_wrong_with_ajax') }}', 'error');
                                })
                        }
                    })
            }
        </script>
    @endsection
@endsection
