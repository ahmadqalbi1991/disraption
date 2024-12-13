@extends('admin.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
<style>
    .home-section .container-fluid {
        padding-left: 0px !important;
        padding-right: 0px !important;
    }

    .action-col-3-bt .btn-primary,
    .action-col-3-bt .btn-secondary,
    .action-col-3-bt .btn-warning,
    .action-col-3-bt .btn-info,
    .action-col-3-bt .btn-danger {
        padding: 12px 20px !important;
    }
</style>
@stop

@section('content')

@php

use App\Http\Controllers\admin\VendorUsersController;

@endphp

<?php $permission_id = "vendors"; ?>
<div class="card mb-5">

    @if(!isset($_GET['reporting']) || $_GET['reporting'] != 'true' and get_user_permission($permission_id,'c'))
    <div class="card-header">
        <a href="{{ route('admin.artist.create') }}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Add New Artist</a>
    </div>
    @endif

    <div class="card-body">

        <form action="" method="get" id="filterform">
            <input type="hidden" name="reporting" value="{{ request()->get('reporting') }}">
            <div class="row">
                <div class="col-md-3 form-group mb-0">
                    <label class="w-100">Name</label>
                    <input type="search" name="name" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('name') }}">

                </div>

                <div class="col-md-3 form-group mb-0">
                    <label class="w-100">Username</label>
                    <input type="search" name="username" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('username') }}">

                </div>
                <div class="col-md-3 form-group mb-0">
                    <label class="w-100">Availability From Date:</label>
                    <input type="text" name="from_date" class="form-control form-control-sm from_date" placeholder="" aria-controls="column-filter" value="{{ request()->get('from_date') }}">

                </div>
                <div class="col-md-3 form-group mb-0">
                    <label class="w-100">Availability To Date:</label>
                    <input type="text" name="to_date" class="form-control form-control-sm to_date" placeholder="" aria-controls="column-filter" value="{{ request()->get('to_date') }}">

                </div>

                <div class="col-md-3 form-group mb-0">
                    <label class="w-100">Type</label>
                    <select class="form-control jqv-input product_catd select2" name="type" data-role="select2" data-placeholder="Select Category" data-allow-clear="true" data-parsley-required-message="Select Category">
                        <option value="">All</option>
                        @foreach($types as $type_id => $typeName)
                        <option value="{{ $type_id }}" {{ $type_id == request()->get('type') ? 'selected' : '' }}>
                            {{ $typeName }}
                        </option>
                        @endforeach
                    </select>

                </div>


                <div class="col-md-3 form-group mb-0">
                    <label>Category</label>
                    <select class="form-control jqv-input product_catd select2" name="category_id" data-role="select2" data-placeholder="Select Category" data-allow-clear="true" data-parsley-required-message="Select Category">
                        <option value="">All</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request()->get('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-3 form-group mb-0">
                    <label>Gender</label>
                    <select name="gender" class="form-control" data-parsley-required-message="Select Gender">
                        <option value="">All</option>
                        <option value="male" {{ request()->get('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request()->get('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ request()->get('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>


                <div class="col-md-2 form-group mb-0">
                    <label>Status</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">All</option>
                        <option value="1" {{ request()->get('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request()->get('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-4 p-4 action-col-3-bt">


                    <button type="submit" class="btn btn-primary fltr-btn">Filter</button>
                    <button id="reset" type="button" class="btn btn-primary ml-2 fltr_form_reset">Reset</button>
                    @if(isset($_GET['reporting']) && $_GET['reporting'] == 'true')
                    <a type="button" href="#" class="btn btn-primary btn-export fltr_form_export mt-1 ml-2">Export</a>
                    @endif
                </div>
            </div>
        </form>

        <style>
            .table-top-scroll,
            .table-responsive {
                width: 100%;
                border: none;
                overflow-x: scroll;
                overflow-y: hidden;
            }

            .table-top-scroll {
                height: 20px;
                position: sticky;
                position: -webkit-sticky;
                top: 0;
                /* required */
                z-index: 3;
                background: #36454f;
            }

            /*.table-responsive{height: 200px; }*/
            .scroller {
                height: 20px;
            }

            .table {
                overflow: auto;
            }

            body,
            .card.mb-5 {
                overflow-x: visible;
                overflow-y: visible;
            }
        </style>
        <div class="table-top-scroll mb-1">
            <div class="scroller">
            </div>
        </div>
        <div class="table-responsive mt-1">
            <table class="table table-condensed table-bordered table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">Action</th>
                        <th>Artist Details</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Sales Rating</th>
                        <th>Artist Ratings</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Working Dates</th>
                        <th>Status</th>
                        <th>Created at</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $i = $vendors->perPage() * ($vendors->currentPage() - 1); ?>
                    @foreach ($vendors as $vendor)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td class="text-center action">
                            <div class="dropdown custom-dropdown custom-dropdown-long">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuLink7">

                                    @if (get_user_permission($permission_id,'u'))
                                    <a class="dropdown-item" href="{{ url('/admin/artist/edit/' . $vendor->id) }}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif

                                    @if(get_user_permission($permission_id,'d'))
                                    <a class="dropdown-item cstm_lst_delete_itm" data-userid="dummy" data-itemid="{{$vendor->id}}" data-csrf="{{ csrf_token() }}" data-redirect="{{ route('admin.artist') }}" href="{{ route('admin.artist.delete') }}"><i class="flaticon-delete-1"></i> Delete</a>
                                    @endif

                                    @if (get_user_permission('vendors_portfolio','c'))
                                    <a class="dropdown-item" href="{{route('admin.portfolio.create', ['type'=> 'admin', 'user_id'=> $vendor->id])}}"><i class="flaticon-pencil-1"></i> Portfolio</a>
                                    @endif

                                    @if (get_user_permission('vendors_booking','r'))
                                    <a class="dropdown-item" href="{{route('admin.artist-booking.index', ['type'=> 'admin', 'user_id'=> $vendor->id])}}"><i class="flaticon-pencil-1"></i> Bookings</a>
                                    @endif

                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="ml-3">
                                    <a href="{{ url('/admin/artist/edit/' . $vendor->id) }}" class="yellow-color">{{$vendor->first_name}} {{$vendor->last_name}}</a>
                                    @if(!empty($vendor->vendor_details))
                                    <div><a href="#" class="yellow-color">{{'@' . $vendor->vendor_details->username ?? 'NIL'}}</a></div>
                                    @endif
                                    <div><a href="mailto:{{$vendor->email}}" class="yellow-color">{{$vendor->email}}</a></div>
                                    <div><a class="yellow-color" href="https://wa.me/+{{$vendor->dial_code}}{{$vendor->phone}}" target="_blank">+{{$vendor->dial_code}} {{$vendor->phone}}</a></div>
                                </span>

                            </div>
                        </td>
                        @php
                            $categories = $vendor->vendor_details->formatted_categories;
                            $rainbowColors = generateColors(count($categories));
                        @endphp
                        {{-- <td class="text-wrap">{{ implode(', ',$vendor->vendor_details->formatted_categories);}}</td> --}}
                        <td>
                            <div class="d-flex flex-wrap">
                            @foreach ($categories as $index => $category)
                                <a href="#" class="p-1" style="background-color: {{ $rainbowColors[$index] }}; font-size:12px ; color: white; margin: 3px; border-radius: 20px; ">
                                    {{ $category }}
                                </a>
                            @endforeach
                            </div>
                        </td>
                        <td>{{ ucfirst($vendor->vendor_details->type)}}</td>
                        <td>{{ VendorUsersController::salesRating($vendor)}}%</td>
                        <td>{{ ucfirst($vendor->vendor_details->total_rating)}}</td>

                        <td>{{ ucfirst($vendor->vendor_details->gender)}}</td>
                        <td>
                            @if ($vendor->vendor_details->date_of_birth)
                            {{ VendorUsersController::calculateAge($vendor->vendor_details->date_of_birth) }}
                            @else
                            N/A
                            @endif
                        </td>




                        <td>
                            @if ($vendor->vendor_details->availability_from)
                            <p>{{web_date_in_timezone($vendor->vendor_details->availability_from, 'd-m-Y')}}</p>
                            @endif
                            @if ($vendor->vendor_details->availability_to)
                            {{web_date_in_timezone($vendor->vendor_details->availability_to, 'd-m-Y')}}
                            @endif
                        </td>

                        <td>
                            @if (get_user_permission($permission_id,'u'))
                            <label class="switch s-icons s-outline  s-outline-warning mt-2 mb-2 mr-2">
                                <input type="checkbox" class="change_status" data-id="{{ $vendor->id }}" data-url="{{ route('admin.artist.change_status') }}" @if ($vendor->active) checked @endif>
                                <span class="slider round"></span>
                                @else
                                {{$vendor->active ? 'Active' : 'Inactive'}}
                            </label>
                            @endif
                        </td>
                        <td>{{web_date_in_timezone($vendor->created_at,'d-m-Y h:i A')}}</td>




                    </tr>

                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <span>Total {{ $vendors->total() }} entries</span>
                <div class="col-sm-12 col-md-12 pull-right">
                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        {!! $vendors->appends(request()->input())->links('admin.template.pagination') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script>
    $(function() {
        var widthTable = $(".table-responsive .table").outerWidth();
        $(".table-top-scroll .scroller").css("width", widthTable);
        $(".table-top-scroll").scroll(function() {
            $(".table-responsive")
                .scrollLeft($(".table-top-scroll").scrollLeft());
        });
        $(".table-responsive").scroll(function() {
            $(".table-top-scroll")
                .scrollLeft($(".table-responsive").scrollLeft());
        });
        // console.log(widthTable);
    });
</script>
<script src="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.js"></script>
<script>
    App.initFormView();

    // on document load
    $(document).ready(function() {



        // --- Get the disableSortingColumnsIndex from the server side ----
        var disableSortingColumnsIndex = <?php echo json_encode($disableSortingColumnsIndex); ?>
        // us requcer to forma the array of object {"targets": 0, "orderable": false}
        var disableSortingColumns = disableSortingColumnsIndex.map(function(index) {
            return {
                "targets": index,
                "orderable": false
            }
        });


        // ----------------------------------------------------------------


        // ready the order
        var sortIndex = <?php echo request()->get('sort_index') ?? 0; ?>;
        var sortOrder = `<?php echo request()->get('sort_order') ?? 'asc'; ?>`;


        // Disable the table sorting rows, as we are sorting from the backend
        $.fn.dataTable.ext.order['disableSort'] = function(settings, col) {
            return []; // Return an empty array to effectively disable sorting
        };


        var table = $('#example2').DataTable({
            "paging": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "order": [
                [sortIndex, sortOrder]
            ], // Default sort order
            "columnDefs": [...disableSortingColumns, {
                "targets": '_all',
                "orderDataType": 'disableSort'
            }]
        });


        // Handle the order event
        table.on('order.dt', function(e) {

            // Get the order details
            var order = table.order();
            var columnIndex = order[0][0]; // Column index
            var sortDirection = order[0][1]; // 'asc' or 'desc'

            // Get the column name from the header
            var columnName = table.column(columnIndex).header().textContent.trim();

            // Build the new URL with the query parameters
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort_index', columnIndex);
            currentUrl.searchParams.set('sort_order', sortDirection);
            currentUrl.searchParams.set('page', 1); // Reset the page number to 1

            // Redirect to the new URL
            window.location.href = currentUrl.toString();
        });

    });
    $(document).ready(function() {
    var fromDate = $('.from_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function(e) {
        var selectedDate = e.date;
        $('.to_date').datepicker('setStartDate', selectedDate);
    });

    var toDate = $('.to_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function(e) {
        var selectedDate = e.date;
        $('.from_date').datepicker('setEndDate', selectedDate);
    });
});
    </script>
@stop
