@extends('admin.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section('content')

<?php

// Booking Order model
use App\Models\BookingOrder;

$permission_id = "customers_booking_order";

?>



<div class="card mb-5">
    <div class="card-header d-none">
        @if( ((!isset($_GET['reporting']) || !$_GET['reporting'] == 'true')) and get_user_permission($permission_id,'c') )
        <a href="{{ route('admin.booking-orders.create', ['type'=> $type, 'user_id'=> $user_id, 'id'=> null]) }}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Add New Booking</a>
        @endif

    </div>
    <div class="card-body">


        <form action="" method="get" id="filterform">
            <input type="hidden" name="reporting" value="{{ request()->get('reporting') }}">
            <div class="row">

                <div class="col-md-3 form-group">
                    <label>Order Id</label>
                    <input type="search" name="order_id" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('order_id') }}">
                </div>
                
                <div class="col-md-3 form-group">
                    <label>Booking Refrence No</label>
                    <input type="search" name="booking_refrence_no" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('booking_refrence_no') }}">
                </div>
            
                @if($user_id == 'all')
                <div class="col-md-3 form-group">
                    <label>Customer Name</label>
                    <input type="search" name="customer_name" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('customer_name') }}">
                </div>
                @endif


                <div class="col-md-3 form-group">
                    <label>Artist Name</label>
                    <input type="search" name="artist_name" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('artist_name') }}">
                </div>

      
                <div class="col-md-3 form-group">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control form-control-sm" aria-controls="column-filter">
                        <option value="">All</option>
                        @foreach (BookingOrder::$orderStatus as $key => $status)
                        <option value="{{ $key }}" {{ request()->get('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 form-group">
                    <label class="w-100">From Date:</label>
                        <input type="text" name="from_date" class="form-control form-control-sm flatpickr-input no-future-date" placeholder="" aria-controls="column-filter" value="{{ request()->get('from_date') }}">
                    
                </div>
                <div class="col-md-3 form-group">
                    <label class="w-100">To Date:</label>
                        <input type="text" name="to_date" class="form-control form-control-sm flatpickr-input no-future-date" placeholder="" aria-controls="column-filter" value="{{ request()->get('to_date') }}">
                    
                </div>

                <div class="col-md-3 mt-2">


                    <button type="submit" class="btn btn-primary fltr-btn">Filter</button>
                    <button id="reset" type="button" class="btn btn-primary ml-2 fltr_form_reset">Reset</button>
                    @if(isset($_GET['reporting']) && $_GET['reporting'] == 'true')
                    <a type="button" href="#" class="btn btn-primary btn-export fltr_form_export ml-2">Export</a>
                    @endif
                </div>
            </div>
        </form>


        <div class="table-responsive mt-5">
            <table class="table table-condensed table-bordered table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">Action</th>
                        <th>Order No</th>
                        <th>Booking Reference No</th>
                        @if ($user_id == 'all')
                        <th>Customer</th>
                        @endif
                        <th>Artist</th>
                        <th>Amount Paid</th>
                        <th>Booking Total Amount</th>
                        <th>Status</th>
                        <th>Creation Date</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php $i = $bookings->perPage() * ($bookings->currentPage() - 1); ?>
                    @foreach ($bookings as $booking)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td class="text-center action">

@if (get_user_permission($permission_id,'u'))
<a href="{{ route('admin.booking-orders.view', ['type'=> $type, 'user_id'=>$booking->customer->id, 'id'=> $booking->id]) }}" class="btn btn-icon btn-primary"><i class="fa-regular fa-eye"></i></a>
@endif

</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="ml-3">
                                    <a class="yellow-color">{{$booking->order_id}}</a>
                                </span>

                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <span class="ml-3">
                                    {{$booking->booking->reference_number}}
                                </span>

                            </div>
                        </td>

                        @if ($user_id == 'all')
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="ml-3">
                                    <a class="yellow-color">{{$booking->customer->name}}</a>
                                    <div>{{$booking->customer->email}}</div>
                                </span>

                            </div>
                        </td>
                        @endif

                        <td>
                            <div class="d-flex align-items-center">
                                <span class="ml-3">
                                    <a class="yellow-color">{{$booking->vendor->name}}</a>
                                    <div>{{$booking->vendor->email}}</div>
                                </span>

                            </div>
                        </td>


                        <td>{{ $booking->total_paid}} AED</td>
                        <td>{{ $booking->booking->total}} AED</td>
                        <td>{{ ucfirst($booking->status)}}</td>

                        <td>{{web_date_in_timezone($booking->created_at,'d-m-Y h:i A')}}</td>


                      

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


    $('#example2').DataTable({
        "paging": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "responsive": true,
    });
</script>
@stop