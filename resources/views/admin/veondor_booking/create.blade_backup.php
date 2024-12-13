@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout')
@section('header')
<style>
    .form-check-input {
        width: 20px;
        height: 20px;
        /*margin-top: .25em;*/
        vertical-align: top;
        background-color: #fff;
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        border: 1px solid rgba(0, 0, 0, .25);
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
        border-radius: 50rem;
    }

    .form-check-input:checked {
        background-color: #1BD1EA;
        border-color: #1BD1EA;
    }



    .form-check {
        display: flex;
        align-items: center;
    }

    .form-check-label {
        margin-left: 10px;
    }

    .edit_row {
        border: 1px solid #525252 !important;
    }

    select {
        background: linear-gradient(90deg, #EA33C7 0%, #0507EA 100%, #0507EA 100%) !important;
    }

    span.text-muted {
        margin-bottom: 5px;
        display: inline-block;
        color: #d1d1d1 !important;
    }

    .card-body h6 {
        color: #ffffff;
        margin: 0;
        font-size: 16px;
        line-height: normal;
    }

    .del-product-img {
        cursor: pointer;
        position: absolute;
        right: 10px;
        top: 10px;
        width: 25px;
        height: 25px;
        background: red;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-size: 16px;
        z-index: 2;
    }

    .selected_customer {
        display: inline-block;
        padding: 18px 23px;
        background: linear-gradient(90deg, #EA33C7 0%, #0507EA 100%, #0507EA 100%) !important;
        text-transform: capitalize;
    }
</style>
@stop
@section('content')

@php

use App\Models\Transaction;
use App\Models\Vendor\VendorBooking;

$haveOrder = $booking ? true : false;
$orderStatus = $haveOrder ? $booking->status : "";
$disableDates = $orderStatus == 'completed' || $orderStatus == 'cancelled' ? true : false;
$rowColumnCss = $user_id == "all" ? 'col-md-4' : 'col-md-6';

$isVendor = Auth::user()->user_type_id == 3 ? true : false;

@endphp

<form method="post" id="admin-form" action="{{ route(route_name_admin_vendor($type, 'artist-booking.save'), ['type'=> $type, 'user_id'=> $user_id]) }}" enctype="multipart/form-data" data-parsley-validate="true">

    @if ($isVendor and !$id)



    <div class="card mb-5">
        <div class="card-body">
            <h5 class="card-header">Customer</h5>

            <div class="row mt-3 search_form">


                <div class="col-md-12 mb-4">
                    <div class="form-group">
                        <label>Find customer by <b class="text-danger">*</b></label>
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="option" id="option_email" checked value="email">
                                    <label class="form-check-label" for="option_email">Email</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="option" id="option_phone" value="phone">
                                    <label class="form-check-label" for="option_phone">Phone</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6 email_div">
                    <div class="form-group">
                        <label>Email <b class="text-danger">*</b></label>
                        <input id="find_cust_email" name="custm_email" type="email" class="form-control" data-parsley-required-message="Enter Email">
                    </div>

                </div>


                <div class="col-md-6 phone_div" style="display: none">
                    <div class="form-group">
                        <label>Phone No<b class="text-danger">*</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select class="form-control jqv-input product_catd select2" name="custm_dialcode" id="find_cust_dialcode" data-role="select2" data-placeholder="Select Code" data-allow-clear="true" data-parsley-required-message="Select Code">
                                    @foreach ($countries as $cnt)
                                    <option value="{{ $cnt->dial_code }}">+{{ $cnt->dial_code }}</option>
                                    @endforeach;
                                </select>
                            </div>
                            <input autocomplete="off" type="number" class="form-control frmt_number nmbr_no_arrow" name="custm_phone" id="find_cust_phone" value="" data-jqv-required="true" data-parsley-required-message="Enter Phone number" data-parsley-type="digits" data-parsley-minlength="5" data-parsley-maxlength="12" data-parsley-trigger="keyup">
                        </div>
                        <span id="mob_err"></span>
                    </div>
                </div>



                <div class="col-md-12 mt-2">
                    <div class="form-group">
                        <button id="search" type="button" class="btn btn-primary">Search</button>
                    </div>
                </div>


            </div>

            <div class="customer_found mt-4" style="display: none;">

                <div class="row">

                    <div class="col-6 details">

                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="form-group">
                            <button id="remove_user" type="button" class="btn btn-primary">Remove Customer</button>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>

    @endif


    <div class="card mb-5">
        <div class="card-body">
            <div class="">
                <input type="hidden" name="id" id="cid" value="{{ $id }}">
                @if ($user_id !== "all")
                <input type="hidden" name="user_id" id="cid" value="{{ $user_id }}">
                @endif
                @csrf()

                <div class="row">


                    <div class="{{$rowColumnCss}}">
                        <div class="form-group">
                            <label>Reference No</label>
                            <input type="text" class="form-control" disabled value="{{ $reference_number }}">
                        </div>
                    </div>

                    @if ($user_id == "all")

                    <div class="{{$rowColumnCss}}">
                        <div class="form-group">
                            <label>Artist <span style="color:red;">*<span></label>
                            <select id="vendors" class="form-control jqv-input product_catd select2" name="user_id" data-role="select2" data-placeholder="Select Artist" data-allow-clear="true" required data-parsley-required-message="Select Artist">
                                <option value=""> Select Artist </option>
                                @foreach($vendors as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @endif


                    @if (!$id and !$isVendor)


                    <div class="{{$rowColumnCss}}">
                        <div class="form-group">
                            <label>Customer <span style="color:red;">*<span></label>
                            <select class="form-control jqv-input product_catd select2" name="customer_id" data-role="select2" data-placeholder="Select Customer" data-allow-clear="true" required data-parsley-required-message="Select Customer">
                                <option value=""> Select Customer </option>
                                @foreach($customers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @endif



                    <div class="{{$rowColumnCss}}">
                        <div class="form-group">
                            <label>Remarks <span style="color:red;">*<span></label>
                            <input type="text" name="title" class="form-control" required data-parsley-required-message="Enter title" value="{{ $title }}">
                        </div>
                    </div>


                </div>

            </div>

            <div class="col-xs-12 col-sm-6">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-5">
            <div class="card h-100">
                <h5 class="card-header">Media</h5>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <!--<label>Images</label><br>-->
                                <!-- Image previews will be appended here -->

                                <!-- Input for selecting images -->
                                @if (!$disableDates)
                                <input type="file" name="newMedias[]" class="form-control" multiple accept="image/jpeg, image/png, image/gif" data-parsley-trigger="change" data-parsley-fileextension="jpg,png,gif,jpeg" data-parsley-fileextension-message="Only files with types jpg, png, gif, jpeg are supported" data-parsley-max-file-size="5120">
                                @endif

                                <span class="text-info d-none">Upload Images (Maximum 5 medias allowed)</span>
                                <div id="image-previews" class="preview-imgs mt-4">
                                    @foreach($medias as $media)
                                    <div class="img-preview-box position-relative">


                                        <a data-fancybox target="_blank" href="{{ $media->media_url }}" class="b_img_div overflow-hidden w-100">
                                            <img src="{{ $media->media_url }}" class="img-fluid w-100">
                                        </a>

                                        @if (!$disableDates)
                                        <div class="del-product-img" data-role="product-img-trash" data-rid="{{$media->id}}"><svg class="svg-inline--fa fa-trash-can" aria-hidden="true" focusable="false" data-prefix="far" data-icon="trash-can" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                                <path fill="currentColor" d="M160 400C160 408.8 152.8 416 144 416C135.2 416 128 408.8 128 400V192C128 183.2 135.2 176 144 176C152.8 176 160 183.2 160 192V400zM240 400C240 408.8 232.8 416 224 416C215.2 416 208 408.8 208 400V192C208 183.2 215.2 176 224 176C232.8 176 240 183.2 240 192V400zM320 400C320 408.8 312.8 416 304 416C295.2 416 288 408.8 288 400V192C288 183.2 295.2 176 304 176C312.8 176 320 183.2 320 192V400zM317.5 24.94L354.2 80H424C437.3 80 448 90.75 448 104C448 117.3 437.3 128 424 128H416V432C416 476.2 380.2 512 336 512H112C67.82 512 32 476.2 32 432V128H24C10.75 128 0 117.3 0 104C0 90.75 10.75 80 24 80H93.82L130.5 24.94C140.9 9.357 158.4 0 177.1 0H270.9C289.6 0 307.1 9.358 317.5 24.94H317.5zM151.5 80H296.5L277.5 51.56C276 49.34 273.5 48 270.9 48H177.1C174.5 48 171.1 49.34 170.5 51.56L151.5 80zM80 432C80 449.7 94.33 464 112 464H336C353.7 464 368 449.7 368 432V128H80V432z"></path>
                                            </svg>
                                        </div>
                                        @endif

                                    </div>

                                    @endforeach
                                </div>

                            </div>
                        </div>



                    </div>


                </div>
            </div>
        </div>

    </div>


    <div class="card mb-5">
        <h5 class="card-header">Booking Dates</h5>
        <div class="card-body">
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">

                        <table id="date-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <button id="add-date" class="btn btn-primary mt-2 @if ($disableDates)
                            d-none
                            
                        @endif" type="button">Add Date</button>
                    </div>
                </div>

            </div>


        </div>
    </div>


    


    <div class="card mt-3 mb-5" id="booking_overview" style="display: none;">
        <h5 class="card-header mb-0">Booking Overview</h5>
        <div class="card-body">
            <div class="row align-items-center">


                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span>Hourly Rate</span>
                        </div>

                        <div class="text-right">
                            <h6>AED <span class="hourly_rate">40</span></h6>
                        </div>


                    </div>
                </div>


                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span>Total Hours</span>
                        </div>

                        <div class="text-right">
                            <h6 class="total_hours">40</h6>
                        </div>


                    </div>
                </div>


                <div class="col-12">
                    <hr>
                </div>
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span>Total Amount</span>
                        </div>

                        <div class="text-right">
                            <h6>AED <span class="total_amount">400</span></h6>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span>Deposit</span>
                        </div>

                        <div class="text-right">
                            <h6>AED <span class="advance_amount">400</span></h6>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <hr>
                </div>
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span>Tax</span>
                        </div>

                        <div class="text-right">
                            <h6>AED <span class="tax">400</span> (5% VAT)</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <hr>
                </div>
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0" style="color: #be2bce;">Grand Total</h5>
                        </div>

                        <div class="text-right">
                            <h5>AED <span class="grand_total">400</span></h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    @if (!$disableDates)
    <div class="card mb-5 mt-5">
        <div class="card-body">
            <div class="row">

                <div class="col-md-12 mt-2">
                    <div class="form-group">

                        <div class="media_progress_Wrap" style="display: none;">
                            <h5>Please wait while the media is uploading.</h5>
                            <div class="progress mt-2" style="height: 20px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>

            </div>


        </div>
    </div>
    @endif

</form>


@if ($haveOrder)


<div class="row">


    <div class="col-6">

        <div class="card mb-5">
            <h5 class="card-header">Order Details</h5>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="d-flex align-items-center justify-content-between guest-hours-list">
                                    <div>
                                        <span class="text-muted">Order No</span>
                                        <h6>{{$booking->order_id}}</h6>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-muted">Booking Total Price</span>
                                        <h6>{{$booking->total_with_tax}} AED</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between guest-hours-list">
                                    <div>
                                        <span class="text-muted">Total Amount Paid by Customer</span>
                                        <h6>{{$booking->total_paid}} AED</h6>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>

                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between guest-hours-list">
                                    <div>
                                        <span class="text-muted">Expected Amount to be paid</span>
                                        <h6>{{$booking->outstanding_amount}} AED</h6>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>


                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between guest-hours-list">
                                    <div>
                                        <span class="text-muted">Order Date</span>
                                        <h6>{{web_date_in_timezone($booking->created_at,'d-m-Y h:i A')}}</h6>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-muted">Order Status</span><br>
                                        <span class="badge mb-0 text-capitalize">


                                            <select id="order_status" class="form-control" data-parsley-required-message="Select Order Status">
                                                @foreach (VendorBooking::$orderStatus as $key => $status)
                                                <option value="{{ $key }}" @if ($key==$booking->status) selected @endif>{{ $status }}</option>
                                                @endforeach
                                            </select>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


            </div>
        </div>

    </div>


    <div class="col-6">


        <div class="card mb-5">
            <h5 class="card-header mb-0">Customer Details</h5>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="">
                            <div>
                                <span class="text-muted">Name</span>
                                <h6>
                                    {{ucfirst($booking->customer->name)}}

                                </h6>
                            </div>
                            <hr>
                            <div>
                                <span class="text-muted">Email</span>
                                <h6>
                                    {{$booking->customer->email}}
                                </h6>
                            </div>
                            <hr>
                            <div>
                                <span class="text-muted">Phone Number</span>
                                <h6>
                                    +{{$booking->customer->dial_code}} {{$booking->customer->phone}}
                                </h6>
                            </div>
                        </div>
                    </div>
                    @if ($booking->customer->user_image)
                    <div class="col-lg-4">
                        <div class="text-right">
                            <img src="{{ get_uploaded_image_url($booking->customer->user_image, 'users') }}" class="img-fluid" style="border-radius:10px; width: 100px; height: 100px; border: 1px solid #1bd1ea;">
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>


</div>


<div class="card">
    <h5 class="card-header mb-0">Transactions</h5>
    <div class="card-body">
        <div class="table-responsive mt-5">
            <table class="table table-condensed table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Transaction Id</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($booking->transactions as $transaction)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>

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


                        <td>{{ ucfirst($transaction->payment_method)}}</td>
                        <td>

                        {{ ucfirst($transaction->status) }}

                            <!-- <select id="transaction_status" data-transaction-id="{{$transaction->id}}" class="form-control" data-parsley-required-message="Select Order Status">
                                @foreach (Transaction::$payment_status as $key => $status)
                                <option value="{{ $key }}" @if ($key==$transaction->status) selected @endif>{{ $status }}</option>
                                @endforeach
                            </select> -->


                        </td>

                        <td>{{web_date_in_timezone($transaction->created_at,'d-m-Y h:i A')}}</td>

                    </tr>

                    @endforeach
                </tbody>
            </table>


        </div>
    </div>
</div>

@endif




@stop
@section('script')


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.js"></script>


<script>
    // Booking dates management script

    var disableDates = '{{$disableDates}}';

    $(document).ready(function() {

        // Function to generate the mediay data row editabled with delete button
        function generateRow(id, date, start_time, end_time) {
            return `
            <tr data-id="${id}">
                <td class="edit_row date" style="max-width: 100px; overflow: hidden;" ><input ${disableDates ? 'disabled' : ''} type="text" class="dob form-control flatpickr-input w-100" data-date-format="dd-mm-yyyy" name="booking_dates[${id}][date]" value="${date}" required data-min-date='today' data-parsley-required-message="Select date"></td>
                <td class="edit_row start_time" style="max-width: 150px;"><input ${disableDates ? 'disabled' : ''} type="text" name="booking_dates[${id}][start_time]" class="form-control flatpicker-time" required data-parsley-required-message="Select start time" value="${start_time}"></td>
                <td class="edit_row end_time" style="max-width: 150px;"><input ${disableDates ? 'disabled' : ''} type="text" name="booking_dates[${id}][end_time]" class="form-control flatpicker-time" required data-parsley-required-message="Select end time" value="${end_time}"></td>
                <td style="width: 100px;"><button class="btn btn-sm btn-danger delete-row ${disableDates ? 'd-none' : ''}" type="button">Delete</button></td>
            </tr>
        `;


        }


        $('#add-date').click(function() {

            // generate random uid with new prefix
            var uid = 'new-' + Math.random().toString(36).substr(2, 9);

            $('#date-table tbody').append(generateRow(uid, '', '', ''));

            addDateAndTime();

        });

        // on dynamice element click
        $('#date-table').on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
        });



        function addDateAndTime() {

            $(".flatpicker-time").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K",
            });

            // add date flatpicker
            $('.dob').flatpickr({
                enableTime: false,
                dateFormat: "Y-m-d",
                minDate: 'today',
            });

        }


        // Generate the rows from the booking dates
        <?php
        for ($i = 0; $i < count($booking_dates); $i++) {
            echo "$('#date-table tbody').append(generateRow('" . $booking_dates[$i]['id'] . "', '" . $booking_dates[$i]['date'] . "', '" . $booking_dates[$i]['start_time'] . "', '" . $booking_dates[$i]['end_time'] . "'));";
        }
        ?>


        addDateAndTime();

    });
</script>




<script>
    // Booking overview div calculations

    var vendor_id = "<?php echo $user_id; ?>";

    var vendors_data = JSON.parse(`<?php echo json_encode($vendors_array); ?>`);


    function calculateAndUpdateBookingOverview() {

        var artist = vendors_data[vendor_id];


        // -------- Ready the booking date data ------

        if (artist) {

            artist.total_hours = 0;

            $('#date-table tbody tr').each(function() {
                var date = $(this).find('.date').find('input').val();
                var start_time = $(this).find('.start_time').find('input').val();
                var end_time = $(this).find('.end_time').find('input').val();

                if (!date || !start_time || !end_time) {
                    return;
                }


                // Ready the date time from the time string
                var start_time = new Date('2000-01-01 ' + start_time);
                var end_time = new Date('2000-01-01 ' + end_time);


                // Get start and end time difference in hours
                var diff = end_time - start_time;
                var hours = Math.floor(diff / 1000 / 60 / 60);

                artist.total_hours = artist.total_hours ? artist.total_hours + hours : hours;

            });

        }

        // -------------------------------------------

        if (artist && artist.total_hours) {

            var hourly_rate = parseFloat(artist.vendor_details.hourly_rate);
            var totalHours = parseFloat(artist.total_hours);
            var totalAmount = hourly_rate * totalHours;
            var advanceAmount = parseFloat(artist.vendor_details.deposit_amount);
            var tax = (totalAmount) * 0.05;
            var grandTotal = totalAmount + tax;

            $('#booking_overview').show();
            $('#booking_overview .hourly_rate').text(hourly_rate);
            $('#booking_overview .total_hours').text(totalHours);
            $('#booking_overview .total_amount').text(totalAmount);
            $('#booking_overview .advance_amount').text(advanceAmount);
            $('#booking_overview .tax').text(tax);
            $('#booking_overview .grand_total').text(grandTotal);
        } else {
            $('#booking_overview').hide();
        }


    }

    // On change of the artist select box
    $('#vendors').change(function() {

        var artist_id = $(this).val();
        vendor_id = artist_id;

        calculateAndUpdateBookingOverview();

    });

    // On #date-table tbody table html content change
    $('#date-table tbody').bind('DOMSubtreeModified', function() {
        calculateAndUpdateBookingOverview();
    });


    // on #date-table edit value change
    $('#date-table').on('change', 'input', function() {
        calculateAndUpdateBookingOverview();
    });


    calculateAndUpdateBookingOverview();
</script>



<script>
    // On status changes

    $('#order_status').change(function() {
        var status = $(this).val();
        var order_id = '{{$haveOrder ? $booking->id : ""}}';
        var url = "{{ route(route_name_admin_vendor($type, 'artist-booking.update-status'), ['type'=> $type, 'user_id'=> $user_id]) }}";
        var data = {
            'orderId': order_id,
            'status': status,
            '_token': '{{ csrf_token() }}'
        };

        // Set the previous value to the select
        $('#order_status').data('previous', $('#order_status').val());

        // Ask for confirmation using app.confirm
        App.confirm('Are you sure you want to change the status?', 'Are you sure you want to change the status?', function(confirmed) {
            if (confirmed) {
                App.loading(true);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'json',
                    success: function(res) {
                        App.loading(false);
                        if (res['status'].toString() == "1") {
                            App.alert(res['message'], 'Success');

                            // after 1 second reload the page
                            setTimeout(function() {
                                location.reload();
                            }, 1000);

                        } else {
                            App.alert(res['message'], 'Oops!');
                        }
                    },
                    error: function(e) {
                        App.loading(false);
                        App.alert(e.responseText, 'Oops!');
                        $('#order_status').val($('#order_status').data('previous')).trigger('change');
                    }
                });
            } else {

                // change the select value to the previous one
                $('#order_status').val($('#order_status').data('previous')).trigger('change');

            }
        });

    });

    // On transaction status changes
    $('#transaction_status').change(function() {
        var status = $(this).val();
        var transaction_id = $(this).data('transaction-id');
        var url = "{{ route(route_name_admin_vendor($type, 'transactions.update-status'), ['type'=> $type, 'user_id'=> $user_id]) }}";
        var data = {
            'transactionId': transaction_id,
            'status': status,
            '_token': '{{ csrf_token() }}'
        };
        // Set the previous value to the select
        $('#transaction_status').data('previous', $('#transaction_status').val());

        // Ask for confirmation using app.confirm
        App.confirm('Are you sure you want to change the transaction status?', 'Are you sure you want to change the transaction status?', function(confirmed) {
            if (confirmed) {
                App.loading(true);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'json',
                    success: function(res) {
                        App.loading(false);
                        if (res['status'].toString() == "1") {
                            App.alert(res['message'], 'Success');
                        } else {
                            App.alert(res['message'], 'Oops!');
                        }
                    },
                    error: function(e) {
                        App.loading(false);
                        App.alert(e.responseText, 'Oops!');
                        $('#transaction_status').val($('#transaction_status').data('previous')).trigger('change');
                    }
                });
            } else {
                // change the select value to the previous one
                $('#transaction_status').val($('#transaction_status').data('previous')).trigger('change');
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('.ret_applicable').change(function() {
            if ($(this).val() == 1) {
                $('.ret_within_div').removeClass('d-none');
                $('.ret_within_inp').attr('required', '');
            } else {
                $('.ret_within_div').addClass('d-none');
                $('.ret_within_inp').removeAttr('required');
            }
        });

    });
</script>


<script>
    $(".flatpicker-time").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",

    });
</script>


<script>
    const id = <?php echo $id ? $id : "''"; ?>;

    App.initFormView();
    // $(document).ready(function() {
    //     if (!$("#cid").val()) {
    //         $(".b_img_div").removeClass("d-none");
    //     }
    // });
    // $(".parent_cat").change(function() {
    //     if (!$(this).val()) {
    //         $(".b_img_div").removeClass("d-none");
    //     } else {
    //         $(".b_img_div").addClass("d-none");
    //     }
    // });



    function password_show_hide2() {
        var x2 = document.getElementById("password2");
        var show_eye2 = document.getElementById("show_eye2");
        var hide_eye2 = document.getElementById("hide_eye2");
        show_eye2.classList.remove("d-none");
        if (x2.type === "password") {
            x2.type = "text";
            hide_eye2.style.display = "none";
            show_eye2.style.display = "block";
        } else {
            x2.type = "password";
            hide_eye2.style.display = "block";
            show_eye2.style.display = "none";
        }
    }



    function validateBookingDatesTime() {

        date_error = false;

        $('#date-table tbody .invalid-feedback').remove();
        $('#date-table tbody input').removeClass('is-invalid');

        $('#date-table tbody tr').each(function() {
            var date = $(this).find('.date').find('input').val();
            var start_time = $(this).find('.start_time').find('input').val();
            var end_time = $(this).find('.end_time').find('input').val();

            // Ready the date time from the time string
            var start_time = new Date('2000-01-01 ' + start_time);
            var end_time = new Date('2000-01-01 ' + end_time);

            // If start time is greater than end time then show the error
            if (start_time >= end_time) {
                date_error = true;
                $(this).find('.start_time').find('input').addClass('is-invalid ');
                $(this).find('.end_time').find('input').addClass('is-invalid  ');
                $('<div class="invalid-feedback">Start time should be less than end time</div>').insertAfter($(this).find('.end_time').find('input'));
            }

        });

        return date_error;
    }


    // On time change validate the time
    $('#date-table').on('change', 'input', function() {
        validateBookingDatesTime();
    });


    $('body').off('submit', '#admin-form');
    $('body').on('submit', '#admin-form', function(e) {

        e.preventDefault();
        var $form = $(this);
        var formData = new FormData(this);
        var $mediaProgress = $('.media_progress_Wrap');
        $(".invalid-feedback").remove();


        // // if total amount is less than Deposit then show the error
        // if (parseFloat($form.find('input[name="total"]').val()) < parseFloat($form.find('input[name="advance"]').val())) {
        //     $form.find('input[name="total"]').addClass('is-invalid ');
        //     $form.find('input[name="advance"]').addClass('is-invalid ');
        //     $('<div class="invalid-feedback">Total amount should be greater than Deposit</div>').insertAfter($form.find('input[name="advance"]'));
        //     return false;
        // }


        // -------- Validate booking date and time
        var date_error = validateBookingDatesTime();

        if (date_error) {
            return
        }
        //----------------------



        App.loading(true);
        $form.find('button[type="submit"]')
            .text('Saving')
            .attr('disabled', true);


        // if have any media in formData.newMedias or formData. to upload then show the progress bar
        if (Object.keys(allFiles).length > 0) {

            $mediaProgress.show();

            // ------ To remove the medias entries ----
            var keysToRemove = [];
            formData.forEach(function(value, key) {
                if (key === 'newMedias[]') {
                    keysToRemove.push(key);
                }
            });

            keysToRemove.forEach(function(key) {
                formData.delete(key);
            });

            // ---------------------------------------

            // loop through the allFiles and append to the form data
            for (var key in allFiles) {
                formData.append(`newMedias[]`, allFiles[key]);
            }


        }



        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: $form.attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            dataType: 'json',
            xhr: function() {
                var xhr = new window.XMLHttpRequest();

                // Upload progress
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);

                        // Update progress bar here
                        $('.progress-bar').css('width', percentComplete + '%').text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(res) {
                App.loading(false);

                if (res['status'] == 0) {
                    if (typeof res['errors'] !== 'undefined') {
                        var error_def = $.Deferred();
                        var error_index = 0;
                        jQuery.each(res['errors'], function(e_field, e_message) {
                            if (e_message != '') {
                                $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                $('<div class="invalid-feedback">' + e_message + '</div>')
                                    .insertAfter($('[name="' + e_field + '"]').eq(0));
                                if (error_index == 0) {
                                    error_def.resolve();
                                }
                                error_index++;
                            }
                        });
                        error_def.done(function() {

                            try {
                                var error = $form.find('.is-invalid').eq(0);
                                $('html, body').animate({
                                    scrollTop: (error.offset().top - 100),
                                }, 500);
                            } catch (error) {

                            }


                        });
                    }

                    // If provided the message then show it
                    if (res['message']) {
                        App.alert(res['message'], 'Oops!');
                    }
                } else {
                    App.alert(res['message'], 'Success!');
                    setTimeout(function() {

                        //If id is provided then reload the page else redirect to the list page
                        if ('{{ $id }}') {
                            location.reload();
                        } else {
                            window.location.href = "{{ route(route_name_admin_vendor($type, 'artist-booking.index'), ['type'=> $type, 'user_id'=> $user_id]) }}";
                        }

                    }, 1500);

                }

                $form.find('button[type="submit"]')
                    .text('Save')
                    .attr('disabled', false);
            },
            error: function(e) {
                App.loading(false);
                $form.find('button[type="submit"]')
                    .text('Save')
                    .attr('disabled', false);
                App.alert(e.responseText, 'Oops!');
            }
        });
    });
</script>


<script>
    // ------------- On medias selected ---------------

    var allFiles = {};

    document.addEventListener("DOMContentLoaded", function() {
        const imagePreviews = document.getElementById('image-previews');
        const inputImages = document.querySelector('input[name="newMedias[]"]');

        inputImages.addEventListener('change', function() {

            const maxIamges = <?php echo $maxImgsAllowed; ?>;
            const PrevImages = imagePreviews.querySelectorAll('img').length;

            if (maxIamges - PrevImages <= 0) return App.alert(`You can only upload ${maxIamges} medias`, 'Oops!');

            const files = Array.from(this.files).slice(0, 5 - PrevImages); // Max 5 images

            files.forEach(file => {
                const reader = new FileReader();

                // Generate UID
                const uid = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);

                allFiles[uid] = file;

                reader.onload = function(e) {
                    imagePreviews.innerHTML += ` <div class="img-preview-box b_img_div"><div class="position-relative">
                                <img src="${e.target.result}" class="img-fluid w-100">
                                <div class="del-product-img local" data-role="product-img-trash" data-yatchId="{{$id}}" data-rid="${uid}"><svg class="svg-inline--fa fa-trash-can" aria-hidden="true" focusable="false" data-prefix="far" data-icon="trash-can" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M160 400C160 408.8 152.8 416 144 416C135.2 416 128 408.8 128 400V192C128 183.2 135.2 176 144 176C152.8 176 160 183.2 160 192V400zM240 400C240 408.8 232.8 416 224 416C215.2 416 208 408.8 208 400V192C208 183.2 215.2 176 224 176C232.8 176 240 183.2 240 192V400zM320 400C320 408.8 312.8 416 304 416C295.2 416 288 408.8 288 400V192C288 183.2 295.2 176 304 176C312.8 176 320 183.2 320 192V400zM317.5 24.94L354.2 80H424C437.3 80 448 90.75 448 104C448 117.3 437.3 128 424 128H416V432C416 476.2 380.2 512 336 512H112C67.82 512 32 476.2 32 432V128H24C10.75 128 0 117.3 0 104C0 90.75 10.75 80 24 80H93.82L130.5 24.94C140.9 9.357 158.4 0 177.1 0H270.9C289.6 0 307.1 9.358 317.5 24.94H317.5zM151.5 80H296.5L277.5 51.56C276 49.34 273.5 48 270.9 48H177.1C174.5 48 171.1 49.34 170.5 51.56L151.5 80zM80 432C80 449.7 94.33 464 112 464H336C353.7 464 368 449.7 368 432V128H80V432z"></path></svg></div>
                            </div></div>`;
                };

                reader.readAsDataURL(file);
            });
        });
    });


    // ------------------------------------------------


    // --------- On server image delete click then delete the image ----------

    $(document).on('click', '.del-product-img:not(.local)', function(e) {
        var rid = $(this).data('rid');
        var orderId = "<?php echo $id; ?>";
        var _this = $(this);
        App.confirm('Confirm Delete', 'You will not be able to undo if you click the yes!', function() {
            // Gather form data
            var formData = {
                "_token": "{{ csrf_token() }}",
                "imageId": rid,
                "orderId": orderId,
                "user_id": "{{ $user_id }}",
                // Add other form fields as needed
            };

            // Perform AJAX request
            var ajxReq = $.ajax({
                url: "{{ route(route_name_admin_vendor($type, 'artist-booking.delete_image'), ['user_id' => $user_id, 'type' => $type]) }}",
                type: 'post',
                dataType: 'json',
                data: formData, // Pass form data to the request
                success: function(res) {
                    if (res['status'] == 1) {
                        // Add sucess message alert
                        App.alert(res['message'], 'Success!');
                        _this.closest('.img-preview-box').remove();
                    } else {
                        App.alert(res['message'] || 'Unable to delete the image.', 'Oops!');
                    }
                },
                error: function(jqXhr, textStatus, errorMessage) {
                    App.alert(errorMessage || 'An error occurred while deleting the image.', 'Oops!');
                }
            });
        });
    });

    // ----------------------------------------------------


    // --------- On local del-product-img delete click then delete the image ----------

    $(document).on('click', '.del-product-img.local', function(e) {

        var rid = $(this).data('rid');
        delete allFiles[rid];
        $(this).closest('.b_img_div').remove();
    });


    // --------------------------------------------------------------------------
</script>


<script>
    // Find customer management

    // On Find customer by radio option change if the option is emaail then show the email div and hide the phone div and vice versa
    $('input[name="option"]').change(function() {

        if ($(this).val() == 'email') {
            $('.email_div').show();
            $('.phone_div').hide();
        } else {
            $('.email_div').hide();
            $('.phone_div').show();
        }
    });


    function doSearch() {

        var option = $('input[name="option"]:checked').val();
        var email = $('#find_cust_email').val();
        var phone = $('#find_cust_phone').val();
        var dialcode = $('#find_cust_dialcode').val();

        if (option == 'email') {
            if (email == '') {
                App.alert('Please enter email', 'Oops!');
                return false;
            }

            // Validate the email without any function
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                App.alert('Please enter valid email', 'Oops!');
                return false;
            }
        } else {
            if (phone == '') {
                App.alert('Please enter phone number', 'Oops!');
                return false;
            }
            if (dialcode == '') {
                App.alert('Please select dial code', 'Oops!');
                return false;
            }
        }

        // Disable the search button and show the loading text
        $('#search').attr('disabled', true).text('Searching ...');

        // functuon to reset the button text and enable the button
        function resetButton() {
            $('#search').attr('disabled', false).text('Search');
        }

        // If all the validations are passed then make the ajax call to find the customer
        $.ajax({
            url: "{{route('vendor.artist-booking.search_user')}}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                type: option,
                email: email,
                phone: phone,
                dialcode: dialcode
            },
            dataType: 'json',
            success: function(res) {
                console.log(res);
                resetButton();
                if (res.status == 1) {

                    $('.search_form').hide();
                    $('.customer_found').show();
                    $('.details').html(
                        ` <div class="selected_customer">
                            <h5>${res.data.name}</h5>
                            <p><strong>Email:</strong> ${res.data.email}</p>
                            <p><strong>Phone:</strong> +${res.data.dial_code} - ${res.data.phone}</p>
                            <input type="hidden" name="custom_user_id" value="${res.data.id}">
                        </div>`
                    );

                } else {
                    // If the customer is not found then show the error message
                    App.alert(res.message, 'Oops!');
                }
            },
            error: function(e) {

                resetButton();

                App.alert(e.responseText, 'Oops!');
            }
        });

    }

    // On email field and phone number press enter then call the doSearch function
    $('#find_cust_email, #find_cust_phone').keypress(function(e) {
        if (e.which == 13) {
            doSearch();

            e.preventDefault();
        }
    });

    // On search button click if the email is provided then validate if the email is valid or not else validate the phone number
    $('#search').click(function() {

        doSearch()

    });

    // On remove user button click hide the customer details and show the search form
    $('#remove_user').click(function() {
        $('.search_form').show();
        $('.customer_found').hide();
    });
</script>

@stop