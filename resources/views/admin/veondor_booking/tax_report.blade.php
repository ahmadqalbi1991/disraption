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
                <button id="reset" type="button" class="btn btn-primary ml-2 mt-1 fltr_form_reset">Reset</button>
                @if(isset($_GET['reporting']) && $_GET['reporting'] == 'true')
                <a type="button" href="#" class="btn btn-primary btn-export mt-1 fltr_form_export ml-2">Export</a>
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
        <table class="table table-condensed  table-bordered table-striped" id="example2">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-center">Action</th>
                    <th>Date</th>
                    <th>Order Id</th>
                    <th>Reference No</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Disruption Fee</th>
                    <th>Artist commission</th>
                    <th>New oder commission</th>
                    <th>Gateway Fees</th>
                    <th>Total VAT</th>
                    <th>Total with Tax</th>




                </tr>
            </thead>
            <tbody>
                <?php $i = $bookings->perPage() * ($bookings->currentPage() - 1); ?>
                @foreach ($bookings as $booking)

                <?php $i++; ?>
                <tr>
                    <td>{{ $i }}</td>
                    <td class="text-center action">

                        @if (get_user_permission($permission_id,'u') and $viewType != 'customer')
                        <a href="{{ route(route_name_admin_vendor($type, 'artist-booking.edit'), ['type'=> $type, 'user_id'=>$booking->user->id, 'id'=> $booking->id]) }}" class="btn btn-icon btn-primary"><i class="fa-regular fa-edit"></i></a>
                        @endif

                        @if ($viewType == 'customer')
                        <a href="{{ route(route_name_admin_vendor('admin', 'booking-orders.view'), ['type'=> $type, 'user_id'=>$booking->customer->id, 'id'=> $booking->id]) }}" class="btn btn-icon btn-primary"><i class="fa-regular fa-eye"></i></a>
                        @endif

                    </td>
                    <td>{{web_date_in_timezone($booking->created_at,'d-m-Y h:i A')}}</td>

                    <td>

                        <div class="d-flex align-items-center">
                            <span class="ml-3">
                                {{$booking->order_id}}
                            </span>

                        </div>


                    </td>

                    <td>

                        <div class="d-flex align-items-center">
                            <span class="ml-3">
                                {{$booking->reference_number}}
                            </span>

                        </div>


                    </td>



                    <td>AED</td>
                    <td> {{$booking->total_without_tax}} AED</td>
                    <td>{{number_format($booking->disraption, 2, '.', '')}} AED</td>
                    <td>{{number_format($booking->artist_commission, 2, '.', '')}} AED</td>
                    <td>{{number_format($booking->neworer_commission, 2, '.', '')}} AED</td>
                    <td>{{number_format($booking->gateway, 2, '.', '')}} AED</td>

                    <td> {{ $booking->tax}} AED</td>
                    <td> {{$booking->total_with_tax}} AED</td>







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

    // ----------------------------------------------------------------

    // ready the order
    var sortIndex = <?php echo request()->get('sort_index') ?? 0; ?>;
    var sortOrder = `<?php echo request()->get('sort_order') ?? 'asc'; ?>`;


    // Disable the table sorting rows, as we are sorting from the backend
    $.fn.dataTable.ext.order['disableSort'] = function(settings, col) {

        console.log("disableSort", settings, col);
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
</script>
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

    });
    
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
</script>
@stop