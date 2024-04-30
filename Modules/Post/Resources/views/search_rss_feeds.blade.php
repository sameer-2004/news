@extends('common::layouts.master')

@section('rss')
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
                                            <h2>{{ __('rss_feeds') }}</h2>
                                        </div>
                                    </div>
                                    @if(Sentinel::getUser()->hasAccess(['rss_write']))
                                        <div class="col-6 text-right">
                                            <a href="{{ route('import-rss') }}"
                                               class="btn btn-primary btn-sm btn-add-new"><i class="mdi mdi-plus"></i>
                                                {{ __('import_rss') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="table-responsive all-pages">
                                <!-- Table Filter -->
                                <div class="row table-filter-container m-b-20">
                                    <div class="col-sm-12">
                                        {!!  Form::open(['route' => 'filter-rss','method' => 'GET']) !!}
                                        <div class="item-table-filter">
                                            <p class="text-muted"><small>{{ __('language') }}</small></p>
                                            <select class="form-control" name="language">
                                                <option value="">{{ __('all') }}</option>
                                                @foreach ($activeLang as  $lang)
                                                    <option value="{{ $lang->code }}">{{ $lang->name }} </option>
                                                @endforeach
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
                                        <th>{{ __('feed_name') }}</th>
                                        <th>{{ __('feed_url') }}</th>
                                        <th>{{ __('language') }}</th>
                                        <th>{{ __('category') }}</th>
                                        <th>{{ __('posts') }}</th>
                                        <th>{{ __('auto_update') }}</th>
                                        <th>{{ __('added_date') }}</th>
                                        @if(Sentinel::getUser()->hasAccess(['rss_write']) || Sentinel::getUser()->hasAccess(['rss_delete']))
                                            <th>{{ __('options') }}</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($feeds as $key => $feed)
                                        <tr id="row_{{ $feed->id }}">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $feed->name }}</td>
                                            <td>{{ $feed->feed_url }}</td>
                                            <td>{{ $feed->language }} </td>
                                            <td>
                                                <label class="category-label m-r-5 label-table"
                                                       id="breaking-post-bgc">
                                                    {{ @$feed->category['category_name'] }} </label>

                                            </td>
                                            <td>{{ $feed->post_limit }}</td>
                                            <td>{{ $feed->auto_update }}</td>
                                            <td>{{ $feed->created_at }}</td>
                                            @if(Sentinel::getUser()->hasAccess(['post_write']) || Sentinel::getUser()->hasAccess(['post_delete']))
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn bg-primary dropdown-toggle btn-select-option"
                                                                type="button" data-toggle="dropdown">...<span
                                                                class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu options-dropdown">
                                                            @if(Sentinel::getUser()->hasAccess(['rss_write']))
                                                                <li>
                                                                    <a href="{{ route('edit-rss',['id'=>$feed->id]) }}"><i
                                                                            class="fa fa-edit option-icon"></i>{{ __('edit') }}
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if(Sentinel::getUser()->hasAccess(['rss_delete']))
                                                                <li>
                                                                    <a href="javascript:void(0)"
                                                                       onclick="delete_item('rss_feeds','{{ $feed->id }}')"><i
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
                                        <h2>{{ __('Showing') }} {{ $feeds->firstItem()}} {{  __('to') }} {{ $feeds->lastItem()}} {{ __('of') }} {{ $feeds->total()}} {{ __('entries') }}</h2>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 text-right">
                                    <div class="table-info-pagination float-right">
                                        {!! $feeds->onEachSide(1)->links() !!}
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


@endsection
@section('script')
    <script>
        $(document).ready(function () {

            $('.dynamic').change(function () {
                if ($(this).val() != '') {
                    var select = $(this).attr("id");
                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                    var _token = "{{ csrf_token() }}";
                    $.ajax({
                        url: "{{ route('subcategory-fetch') }}",
                        method: "POST",
                        data: {select: select, value: value, _token: _token},
                        success: function (result) {
                            $('#' + dependent).html(result);
                        }

                    })
                }
            });

            $('#category').change(function () {
                $('#sub_category').val('');
            });


        });
    </script>

@endsection
