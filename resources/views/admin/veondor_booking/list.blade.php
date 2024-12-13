@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout')

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

<?php

// use BookingOrder
use App\Models\BookingOrder;

$permission_id = "vendors_booking";

?>



<div class="card mb-5">
    <div class="card-header">
        @if(((!isset($_GET['reporting']) || !$_GET['reporting'] == 'true')) and get_user_permission($permission_id,'c') and $viewType != 'customer')
        <a href="{{ route(route_name_admin_vendor($type, 'artist-booking.create'), ['type'=> $type, 'user_id'=> $user_id, 'id'=> null]) }}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Add New Booking</a>
        @endif



    </div>
    <div class="card-body">


        <form action="" method="get" id="filterform">
            <input type="hidden" name="reporting" value="{{ request()->get('reporting') }}">
            <div class="row">
                <div class="col-md-3 form-group mb-0">
                    <label>Refrence No</label>
                    <input type="search" name="refrence_no" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('refrence_no') }}">
                </div>

                <div class="col-md-3 form-group mb-0">
                    <label>Order Id</label>
                    <input type="search" name="order_id" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('order_id') }}">
                </div>


                @if($user_id == 'all' or $viewType == 'customer')
                <div class="col-md-3 form-group mb-0">
                    <label>Artist Name</label>
                    <input type="search" name="artist_name" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('artist_name') }}">
                </div>
                @endif

                @if ($viewType != 'customer')
                <div class="col-md-3 form-group mb-0">
                    <label>Customer Name</label>
                    <input type="search" name="customer_name" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('customer_name') }}">
                </div>
                @endif

                {{--<div class="col-md-3 form-group mb-0">
                    <label>Category</label>
                    <select class="form-control jqv-input product_catd select2" name="category_id" data-role="select2" data-placeholder="Select Category" data-allow-clear="true" data-parsley-required-message="Select Category">
                        <option value="">All</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request()->get('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
                </option>
                @endforeach
                </select>
            </div>--}}


            <div class="col-md-2 form-group mb-0">
                <label>Order Status</label>
                <select name="order_status" id="status" class="form-control form-control-sm" aria-controls="column-filter">
                    <option value="">All</option>

                    @foreach (BookingOrder::$orderStatus as $key => $status)
                    <option value="{{ $key }}" {{ request()->get('order_status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group mb-0">
                <label class="w-100">From Date:</label>
                <input type="text" name="from_date" class="form-control form-control-sm from_date no-future-date" placeholder="" aria-controls="column-filter" value="{{ request()->get('from_date') }}">

            </div>
            <div class="col-md-3 form-group mb-0">
                <label class="w-100">To Date:</label>
                <input type="text" name="to_date" class="form-control form-control-sm to_date no-future-date" placeholder="" aria-controls="column-filter" value="{{ request()->get('to_date') }}">

            </div>

            <div class="col-md-4 pt-4 action-col-3-bt">


                <button type="submit" class="btn btn-primary mt-1 fltr-btn">Filter</button>
                <button id="reset" type="button" class="btn btn-primary ml-2 fltr_form_reset mt-1">Reset</button>
                @if(isset($_GET['reporting']) && $_GET['reporting'] == 'true')
                <a type="button" href="#" class="btn btn-primary btn-export fltr_form_export ml-2">Export</a>
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
                    <th data-sortname="reference_number">Refrence No</th>
                    <th data-sortname="customer">Customer</th>

                    @if ($user_id == 'all')
                    <th data-sortname="artist">Artist</th>
                    @endif
                    <th data-sortname="order_status">Order Status</th>
                    <th data-sortname="date_time">Date/Time of session</th>
                    <th data-sortname="deposit_balance">Deposit/Balance</th>
                    <th data-sortname="total_amount">Total amount</th>
                    <th data-sortname="transaction_date">Transaction Date</th>
                    <th data-sortname="order_id">Order ID</th>

{{--                    <th data-sortname="remarks">Remarks</th>



--}}
                    @if(isset($_GET['reporting']) && $_GET['reporting'] == 'true')
                    <th data-sortname="tax">Taxes and charges</th>
                    <th data-sortname="disraption_fee">Disruption Fee</th>
                    <th data-sortname="artist_commission">Artist commission</th>
                    <th data-sortname="new_order_commission">New oder commission</th>
                    <th data-sortname="gateway_fee">Gateway Fees</th>
                    <th data-sortname="net_total">Net total</th>
                    @endif


                </tr>
            </thead>
            <tbody>
                <?php $i = $bookings->perPage() * ($bookings->currentPage() - 1); ?>
                @foreach ($bookings as $booking)
                <?php
                    $transaction_date = !empty($booking->transactions) ? $booking->transactions->first() : null;
                    $i++;
                ?>

                <tr>
                    <td>{{ $i }}</td>
                    <td class="text-center action">

                        {{-- @if (get_user_permission($permission_id,'u') and $viewType != 'customer' ) --}}
                        @if(auth()->user()->user_type_id == 1)
                        <a href="{{ route(route_name_admin_vendor($type, 'artist-booking.edit'), ['type'=> $type, 'user_id'=>$booking->user->id, 'id'=> $booking->id]) }}" class="btn btn-icon btn-primary"><i class="fa-regular fa-edit"></i></a>
                        @endif

                        @if ($viewType == 'customer')
                        <a href="{{ route(route_name_admin_vendor('admin', 'booking-orders.view'), ['type'=> $type, 'user_id'=>$booking->customer->id, 'id'=> $booking->id]) }}" class="btn btn-icon btn-primary"><i class="fa-regular fa-eye"></i></a>
                        @endif

                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="ml-3">
                                <a class="yellow-color">{{$booking->reference_number}}</a>
                            </span>

                        </div>
                    </td>
                    <td>

                        @if ($booking->customer)

                        <div class="d-flex align-items-center">
                            <span class="ml-3">

                                <a class="yellow-color" href="{{ route('admin.customers.edit', ['id' => $booking->customer->id]) }}">{{$booking->customer->name}}</a>
                                @if($booking->customer->user_name)<div><a class="yellow-color" href="{{ route('admin.customers.edit', ['id' => $booking->customer->id]) }}">{{'@'.$booking->customer->user_name}}</div></a>@endif
                                    <div><a class="yellow-color" href="mailto:{{$booking->customer->email}}">{{$booking->customer->email}}</a></div>
                                    <div><a class="yellow-color" href="https://wa.me/+{{$booking->customer->dial_code}}{{$booking->customer->phone}}" target="_blank">+{{$booking->customer->dial_code}} {{$booking->customer->phone}}</a></div>

                            </span>

                        </div>

                        @endif

                    </td>

                    @if ($user_id == 'all')
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="ml-3">
                                <a href="{{ url('/admin/artist/edit/' . $booking->user->id) }}" class="yellow-color">{{$booking->user->name}}</a>
                                <div><a class="yellow-color" href="mailto:{{$booking->user->email}}">{{$booking->user->email}}</a></div>
                                <div><a class="yellow-color" href="https://wa.me/+{{$booking->user->dial_code}}{{$booking->user->phone}}" target="_blank">+{{$booking->user->dial_code}} {{$booking->user->phone}}</a></div>
                            </span>

                        </div>
                    </td>
                    @endif

                    <td>

                        <div class="d-flex align-items-center">
                            <span class="ml-3">
                                {{ucfirst(order_statuses($booking->status))}}
                            </span>

                        </div>

                    </td>
                    <td>{{web_date_in_timezone($booking->created_at,'d-m-Y h:i A')}}</td>

                    <td> {{$booking->total_paid}}/{{ $booking->outstanding_amount}} AED</td>

                    <td> {{$booking->total_with_tax}} AED</td>

                    <td>
                        {{ !empty($transaction_date) ? web_date_in_timezone($transaction_date->created_at,'d-m-Y h:i A') : '' }}
                    </td>

                    <td>

                        <div class="d-flex align-items-center">
                            <span class="ml-3">
                                {{$booking->order_id}}
                            </span>

                        </div>


                    </td>

{{--                    <td>--}}

{{--                        <div class="d-flex align-items-center">--}}
{{--                            <span class="ml-3">--}}
{{--                                {{ucfirst($booking->title)}}--}}
{{--                            </span>--}}

{{--                        </div>--}}

{{--                    </td>--}}










                    @if(isset($_GET['reporting']) && $_GET['reporting'] == 'true')
                        <td> {{ $booking->tax}} AED</td>
                        <td>{{number_format($booking->disraption, 2, '.', '')}} AED</td>
                        <td>{{number_format($booking->artist_commission, 2, '.', '')}} AED</td>
                        <td>{{number_format($booking->neworer_commission, 2, '.', '')}} AED</td>
                        <td>{{number_format($booking->gateway, 2, '.', '')}} AED</td>
                        <td> {{$booking->total_with_tax}} AED</td>
                    @endif






                </tr>

                @endforeach
            </tbody>
        </table>


        <div class="mt-4">
            <span>Total {{ $bookings->total() }} entries</span>
            <div class="col-sm-12 col-md-12 pull-right">
                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                    {!! $bookings->appends(request()->input())->links('admin.template.pagination') !!}
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


    // On Change Status
    $(document).on('change', '#status', function() {
        var status = $(this).val();
        var url = $(this).data('url');
        var bookingid = $(this).data('bookingid');
        var data = {
            status: status,
            orderId: bookingid,
            _token: '{{ csrf_token() }}'
        };
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.status == '1') {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });


    // --- Get the disableSortingColumnsIndex from the server side ----
    var disableSortingColumnsIndex = <?php echo json_encode($disableSortingColumnsIndex); ?>
    // us requcer to forma the array of object {"targets": 0, "orderable": false}
    var disableSortingColumns = disableSortingColumnsIndex.map(function(index) {
        return {
            "targets": index,
            "orderable": false
        }
    });

    function getColumnIndexByDataSort( dataSortValue) {
        var index = 0; // Default to 0 if not found

        // Loop through each column header (th) in the table
        $('#example2').find('th').each(function(i) {
            if ($(this).attr('data-sortname') === dataSortValue) {
                index = i; // Set the index if a match is found
                return false; // Exit the loop
            }
        });

        return index;
    }

    // ----------------------------------------------------------------

    // ready the order
    var sortIndex = getColumnIndexByDataSort("<?php echo request()->get('sort_index') ?? 0; ?>");
    var sortOrder = `<?php echo request()->get('sort_order') ?? 'asc'; ?>`;


    // Disable the table sorting rows, as we are sorting from the backend
    $.fn.dataTable.ext.order['disableSort'] = function(settings, col) {
        return []; // Return an empty array to effectively disable sorting
    };

    var table = $('#example2').DataTable({
        "paging": false,
        "searching": false,
        "ordering": false,
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
        var sort_name = $(table.column(columnIndex).header()).attr('data-sortname');


        // Build the new URL with the query parameters
        var currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('sort_index', sort_name);
        currentUrl.searchParams.set('sort_order', sortDirection);

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
