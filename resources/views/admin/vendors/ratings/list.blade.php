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

<?php $permission_id = "vendor_ratings"; ?>
<div class="card mb-5">

    @if(!isset($_GET['reporting']) || $_GET['reporting'] != 'true' and get_user_permission($permission_id,'c'))
    <div class="card-header">
        <a href="{{ route('admin.ratings.create') }}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Add New Rating</a>
    </div>
    @endif

    <div class="card-body">

        <form action="" method="get" id="filterform">
            <input type="hidden" name="reporting" value="{{ request()->get('reporting') }}">
            <div class="row">
                <div class="col-md-3 form-group mb-0">
                    <label>Customer Email</label>
                    <input type="email" name="customer_email" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('customer_email') }}">
                </div>
                <div class="col-md-3 form-group mb-0">
                    <label>Artist Email</label>
                    <input type="email" name="vendor_email" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('vendor_email') }}">
                </div>

                <div class="col-md-3 form-group mb-0">
                    <label>Rating</label>
                    <select name="rating" class="form-control form-control-sm">
                        <option value="">All</option>
                        @for($i=1; $i<=5; $i++) <option value="{{ $i }}" {{ request()->get('rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
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





                <div class="col-md-4 mt-4 action-col-3-bt">


                    <button type="submit" class="btn btn-primary fltr-btn">Filter</button>
                    <button id="reset" type="button" class="btn btn-primary ml-2 fltr_form_reset">Reset</button>
                    @if(isset($_GET['reporting']) && $_GET['reporting'] == 'true')
                    <a type="button" href="#" class="btn btn-primary btn-export fltr_form_export ml-2">Export</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-condensed table-bordered table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">Action</th>
                        <th>Customer Details</th>
                        <th>Artist Details</th>
                        <th>Star</th>
                        <th>Review</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = $ratings->perPage() * ($ratings->currentPage() - 1); ?>
                    @foreach ($ratings as $rating)
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
                                    <a class="dropdown-item" href="{{ route('admin.ratings.edit', ['id'=> $rating->id]) }}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif

                                    @if (get_user_permission($permission_id,'d'))
                                    <a class="dropdown-item" data-role="unlink" data-message="Do you want to remove this review?" href="{{ route('admin.ratings.delete', ['id'=>$rating->id]) }}"><i class="flaticon-delete-1"></i> Delete</a>
                                    @endif

                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="ml-3">
                                    <a class="yellow-color">{{$rating->customer->first_name}} {{$rating->customer->last_name}}</a>
                                    <div>{{$rating->customer->email}}</div>
                                    <div>+{{$rating->customer->dial_code}} {{$rating->customer->phone}}</div>
                                </span>

                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <span class="ml-3">
                                    <a class="yellow-color">{{$rating->vendor->first_name}} {{$rating->vendor->last_name}}</a>
                                    <div>{{$rating->vendor->email}}</div>
                                    <div>+{{$rating->vendor->dial_code}} {{$rating->vendor->phone}}</div>
                                </span>

                            </div>
                        </td>
                        <td>{{ $rating->rating }}</td>

                        <td>{{ substr($rating->review, 0, 50) }}</td>

                        <td>{{web_date_in_timezone($rating->created_at,'d-m-Y h:i A')}}</td>

                    </tr>

                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <span>Total {{ $ratings->total() }} entries</span>
                <div class="col-sm-12 col-md-12 pull-right">
                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        {!! $ratings->appends(request()->input())->links('admin.template.pagination') !!}
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