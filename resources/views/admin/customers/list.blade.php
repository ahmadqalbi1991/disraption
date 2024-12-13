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

<?php $permission_id = "customers"; ?>
<div class="card mb-5">


    @if(!isset($_GET['reporting']) || $_GET['reporting'] != 'true' and get_user_permission($permission_id,'c'))
    <div class="card-header">
        <a href="{{ route('admin.customers.create') }}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Add New Customer</a>
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
                    <label class="w-100">From Date:</label>
                    <input type="text" name="from_date" class="form-control form-control-sm from_date no-future-date" placeholder="" aria-controls="column-filter" value="{{ request()->get('from_date') }}">
                </div>
                <div class="col-md-3 form-group mb-0">
                    <label class="w-100">To Date:</label>
                    <input type="text" name="to_date" class="form-control form-control-sm to_date no-future-date" placeholder="" aria-controls="column-filter" value="{{ request()->get('to_date') }}">

                </div>
                <div class="col-md-3 form-group mb-0">
                    <label>Status</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">All</option>
                        <option value="1" {{ request()->get('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request()->get('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 form-group mb-0" style="display:none;">
                    <label class="w-100">Is Verified</label>
                    <select name="verified" class="form-control form-control-sm">
                        <option value="">All</option>
                        <option value="1" {{ request()->get('verified') === '1' ? 'selected' : '' }}>Verified</option>
                        <option value="0" {{ request()->get('verified') === '0' ? 'selected' : '' }}>Un-Verified</option>
                    </select>
                </div>
                <div class="col-md-4 mt-4 action-col-3-bt">

                    <button type="submit" class="btn btn-primary fltr-btn mt-1">Filter</button>
                    <button id="reset" type="button" class="btn btn-primary mt-1 ml-2 fltr_form_reset">Reset</button>
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
                        <th>User Details</th>
                        <th>Customer Rating</th>
                        <th>No of Bookings</th>
                        <th>Total Sales</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Artist Ratings Provided</th>
                        <th>Active</th>
                        <th>Last Logged in</th>
                        <th>Created at</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $i = $users->perPage() * ($users->currentPage() - 1); ?>
                    @foreach ($users as $user)
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
                                    <a class="dropdown-item" href="{{ route('admin.customers.edit', ['id' => $user->id]) }}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif

                                    @if(get_user_permission($permission_id,'d'))
                                    <a class="dropdown-item cstm_lst_delete_itm" data-userid="dummy" data-itemid="{{$user->id}}" data-csrf="{{ csrf_token() }}" data-redirect="{{ route('admin.customers.index') }}" href="{{ route('admin.customers.delete') }}"><i class="flaticon-delete-1"></i> Delete</a>
                                    @endif

                                    @if (get_user_permission('customers_transactions','r'))
                                    <a class="dropdown-item" href="{{ route('admin.transactions.index', ['type'=> 'admin','user_id' => $user->id]) }}"><i class="flaticon-pencil-1"></i> Transactions </a>
                                    @endif

                                    @if (get_user_permission('customers_booking_order','r'))
                                    <a class="dropdown-item" href="{{ route('admin.booking-orders.index', ['type'=> 'admin','user_id' => $user->id]) }}"><i class="flaticon-pencil-1"></i> Bookings</a>
                                    @endif



                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="ml-3">
                                    <a class="yellow-color" href="{{ route('admin.customers.edit', ['id' => $user->id]) }}">{{ucfirst($user->first_name)}} {{ ucfirst($user->last_name) }}</a>
                                    @if($user->user_name)<div><a class="yellow-color" href="{{ route('admin.customers.edit', ['id' => $user->id]) }}">{{ '@'. $user->user_name }}</a></div>@endif
                                    <div><a class="yellow-color" href="mailto:{{$user->email}}">{{$user->email}}</a></div>
                                    <div><a class="yellow-color" href="https://wa.me/+{{$user->dial_code}}{{$user->phone}}" target="_blank">+{{$user->dial_code}} {{$user->phone}}</a></div>
                                </span>

                            </div>
                        </td>
                        <td>{{$user->vendor_to_customer_ratings_avg_rating ? round($user->vendor_to_customer_ratings_avg_rating, 2) : 0}}</td>
                        <td>{{$user->customer_user_bookings_count}}</td>
                        <td>{{$user->customer_user_bookings_sum_total_paid ? round($user->customer_user_bookings_sum_total_paid, 2) : 0}}</td>
                        <td>{{$user->customerUserDetail->date_of_birth ? VendorUsersController::calculateAge($user->customerUserDetail->date_of_birth) : "N/A"}}</td>
                        <td>{{$user->customerUserDetail->gender ? ucfirst($user->customerUserDetail->gender) : "N/A"}}</td>
                        <td>{{$user->customer_ratings_to_vendor_count}}</td>
                        <td>
                            @if (get_user_permission($permission_id,'u'))
                            <label class="switch s-icons s-outline  s-outline-warning mt-2 mb-2 mr-2">
                                <input type="checkbox" class="change_status" data-id="{{ $user->id }}" data-url="{{ route('admin.customers.change_status') }}" @if ($user->active) checked @endif>
                                <span class="slider round"></span>
                            </label>
                            @else
                                {{$user->active ? 'Yes' : 'No'}}
                            @endif
                        </td>
                        <td>{{$user->last_login ? web_date_in_timezone($user->last_login,'d-m-Y h:i A') : "N/A"}}</td>
                        <td>{{web_date_in_timezone($user->created_at,'d-m-Y h:i A')}}</td>




                    </tr>

                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <span>Total {{ $users->total() }} entries</span>
                <div class="col-sm-12 col-md-12 pull-right">
                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        {!! $users->appends(request()->input())->links('admin.template.pagination') !!}
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
