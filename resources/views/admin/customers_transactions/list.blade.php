@extends('admin.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section('content')

<?php

use App\Models\Transaction;
use App\Http\Controllers\admin\VendorUsersController;

$permission_id = "customers_transactions";

?>



<div class="card mb-5">

    <div class="card-body">


        <form action="" method="get" id="filterform">
            <input type="hidden" name="reporting" value="{{ request()->get('reporting') }}">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Refrence No</label>
                    <input type="search" name="refrence_no" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('refrence_no') }}">
                </div>
                <div class="col-md-3 form-group">
                    <label class="w-100">From Date:</label>
                        <input type="text" name="from_date" class="form-control form-control-sm flatpickr-input no-future-date" placeholder="" aria-controls="column-filter" value="{{ request()->get('from_date') }}">
                    
                </div>
                <div class="col-md-3 form-group">
                    <label class="w-100">To Date:</label>
                        <input type="text" name="to_date" class="form-control form-control-sm flatpickr-input no-future-date" placeholder="" aria-controls="column-filter" value="{{ request()->get('to_date') }}">
                    
                </div>

                @if($user_id == 'all')
                <div class="col-md-3 form-group">
                    <label>Artist Name</label>
                    <input type="search" name="artist_name" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('artist_name') }}">
                </div>
                @endif


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

            <div class="table-responsive mt-5">
                <table class="table table-condensed table-striped" id="example2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-center">Action</th>
                            <th>Transaction Id</th>
                            <th>Order Id</th>
                            <th>Booking Refrence No</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Artist Details</th>
                            <th>Artist Rating</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Date</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; ?>

                        @foreach ($bookings as $booking)

                        @foreach ($booking->transactions as $transaction)
                        <?php $i++; ?>
                        <tr>
                            <td>{{ $i }}</td>
                            <td class="text-center action">
                                <div class="dropdown custom-dropdown">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i class="flaticon-dot-three"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuLink7">

                                        @if (get_user_permission('customers_booking_order','r'))
                                        <a class="dropdown-item" href="{{ route('admin.booking-orders.view', ['type'=> 'admin','user_id' => $booking->customer_id, 'id'=> $booking->id]) }}"><i class="flaticon-order"></i> View Order</a>
                                        @endif

                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="ml-3">
                                        <a class="yellow-color">{{$transaction->transaction_id}}</a>
                                    </span>

                                </div>
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
                                        <a class="yellow-color">{{$booking->reference_number}}</a>
                                    </span>

                                </div>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="ml-3">
                                        {{$transaction->amount}} AED
                                    </span>

                                </div>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="ml-3">
                                        {{Transaction::$types[$transaction->type]}}

                                    </span>

                                </div>
                            </td>


                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="ml-3">
                                        <a class="yellow-color">{{$transaction->vendor->first_name}} {{$transaction->vendor->last_name}}</a>
                                        <div>{{$transaction->vendor->email}}</div>
                                        <div>+{{$transaction->vendor->dial_code}} {{$transaction->vendor->phone}}</div>
                                    </span>

                                </div>
                            </td>
                            
                            <td>
                            {{$transaction->vendor->vendor_details->total_rating}}
                            </td>
                            <td>{{$transaction->vendor->vendor_details->date_of_birth ? VendorUsersController::calculateAge($transaction->vendor->vendor_details->date_of_birth) : "N/A" }}</td>
                            <td>{{ ucfirst($transaction->vendor->vendor_details->gender)}}</td>
                            <td>{{ ucfirst($transaction->payment_method)}}</td>
                            <td>{{ ucfirst($transaction->status)}}</td>

                            <td>{{web_date_in_timezone($transaction->created_at,'d-m-Y h:i A')}}</td>



                        </tr>

                        @endforeach
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
</div>
@stop

@section('script')
<script src="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.js"></script>
<script>
    App.initFormView();


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