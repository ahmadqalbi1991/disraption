@extends('admin.template.layout')
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
</style>
@stop
@section('content')

@php

use App\Models\Transaction;
use App\Models\BookingOrder;

$haveOrder = $booking ? ( $booking->bookingOrder ? true : false) : false;
$orderStatus = $haveOrder ? $booking->bookingOrder->status : '';
$disableDates = $orderStatus == 'completed' || $orderStatus == 'cancelled' ? true : false;

@endphp

<form method="post" id="admin-form" action="{{ route('admin.artist-booking.save', ['type'=> $type, 'user_id'=> $user_id]) }}" enctype="multipart/form-data" data-parsley-validate="true">
    <div class="card mb-5">
        <div class="card-body">
            <div class="">
                <input type="hidden" name="id" id="cid" value="{{ $id }}">
                <input type="hidden" name="user_id" id="cid" value="{{ $user_id }}">
                @csrf()

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Reference No</label>
                            <input type="text" class="form-control" disabled value="{{ $reference_number }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Title <span style="color:red;">*<span></label>
                            <input type="text" name="title" class="form-control" @if (!$haveOrder) required @endif data-parsley-required-message="Enter title" value="{{ $title }}" @if ($haveOrder) disabled @endif>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total Amount<b class="text-danger">*</b></label>
                            <input type="number" name="total" @if (!$haveOrder) required @endif class="form-control frmt_price" value="{{ $total }}" @if ($haveOrder) disabled @endif>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Deposit<b class="text-danger">*</b></label>
                            <input type="number" name="advance" @if (!$haveOrder) required @endif class="form-control frmt_price" value="{{ $advance }}" @if ($haveOrder) disabled @endif>
                        </div>
                    </div>




                </div>

            </div>

            <div class="col-xs-12 col-sm-6">
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


    @if (!$disableDates)
    <div class="card mb-5">
        <div class="card-body">
            <div class="row">

                <div class="col-md-12 mt-2">
                    <div class="form-group">
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
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between guest-hours-list">
                                    <div>
                                        <span class="text-muted">Order No</span>
                                        <h6>{{$booking->bookingOrder->order_id}}</h6>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-muted">Booking Total Price</span>
                                        <h6>{{$booking->total}} AED</h6>
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
                                        <h6>{{web_date_in_timezone($booking->bookingOrder->date,'d-m-Y h:i A')}}</h6>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-muted">Order Status</span><br>
                                        <span class="badge mb-0 text-capitalize">


                                            <select id="order_status" class="form-control" data-parsley-required-message="Select Order Status">
                                                @foreach (BookingOrder::$orderStatus as $key => $status)
                                                <option value="{{ $key }}" @if ($key==$booking->bookingOrder->status) selected @endif>{{ $status }}</option>
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
                                    {{$booking->bookingOrder->customer->name}}


                                </h6>
                            </div>
                            <hr>
                            <div>
                                <span class="text-muted">Email</span>
                                <h6>
                                    {{$booking->bookingOrder->customer->email}}
                                </h6>
                            </div>
                            <hr>
                            <div>
                                <span class="text-muted">Phone Number</span>
                                <h6>
                                    +{{$booking->bookingOrder->customer->dial_code}} {{$booking->bookingOrder->customer->phone}}
                                </h6>
                            </div>
                        </div>
                    </div>
                    @if ($booking->bookingOrder->customer->user_image)
                    <div class="col-lg-4">
                        <div class="text-right">
                            <img src="{{ get_uploaded_image_url($booking->bookingOrder->customer->user_image, 'users') }}" class="img-fluid" style="border-radius:10px; width: 100px; height: 100px; border: 1px solid #1bd1ea;">
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
            <table class="table table-condensed table-bordered table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Transaction Id</th>
                        <th>Amount</th>
                        <th>Deposit Amount</th>
                        <th>Type</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($booking->bookingOrder->transactions as $transaction)
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

                            <select id="transaction_status" data-transaction-id="{{$transaction->id}}" class="form-control" data-parsley-required-message="Select Order Status">
                                @foreach (Transaction::$payment_status as $key => $status)
                                <option value="{{ $key }}" @if ($key==$transaction->status) selected @endif>{{ $status }}</option>
                                @endforeach
                            </select>


                        </td>

                        <td>{{web_date_in_timezone($transaction->created_at,'d-m-Y h:i A')}}</td>

                    </tr>

                    @endforeach
                </tbody>
            </table>


        </div>
    </div>
</div>

@else


<div class="card mb-5">
    <h5 class="card-header">Order Details</h5>
    <div class="card-body">
        <div class="row">

            <div class="col-md-12">
                <h6>No one boooked this booking yet!</h6>
            </div>

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
                <td class="edit_row start_time" style="max-width: 150px;"><input ${disableDates ? 'disabled' : ''} type="text" name="booking_dates[${id}][end_time]" class="form-control flatpicker-time" required data-parsley-required-message="Select end time" value="${end_time}"></td>
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
    // On status changes

    $('#order_status').change(function() {
        var status = $(this).val();
        var order_id = '{{$haveOrder ? $booking->bookingOrder->id : ""}}';
        var url = "{{ route('admin.booking-orders.update-status', ['type'=> $type, 'user_id'=> $user_id]) }}";
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
        var url = "{{ route('admin.transactions.update-status', ['type'=> $type, 'user_id'=> $user_id]) }}";
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






    $('body').off('submit', '#admin-form');
    $('body').on('submit', '#admin-form', function(e) {

        e.preventDefault();
        var $form = $(this);
        var formData = new FormData(this);
        $(".invalid-feedback").remove();


        // if total amount is less than Deposit then show the error
        if (parseFloat($form.find('input[name="total"]').val()) < parseFloat($form.find('input[name="advance"]').val())) {
            $form.find('input[name="total"]').addClass('is-invalid ');
            $form.find('input[name="advance"]').addClass('is-invalid ');
            $('<div class="invalid-feedback">Total amount should be greater than Deposit</div>').insertAfter($form.find('input[name="advance"]'));
            return false;
        }


        // -------- Loop through the date rows and check if the date is empty or start_time and end_time then insert the <div class="invalid-feedback error below that field
        var date_error = false;
        $('#date-table tbody tr').each(function() {
            var date = $(this).find('.date').find('input').val();
            var start_time = $(this).find('.start_time').find('input').val();
            var end_time = $(this).find('.end_time').find('input').val();

            // Ready the date time from the time string
            var start_time = new Date('1970-01-01T' + start_time + 'Z');
            var end_time = new Date('1970-01-01T' + end_time + 'Z');

            // If start time is greater than end time then show the error
            if (start_time > end_time) {
                date_error = true;
                $(this).find('.start_time').find('input').addClass('is-invalid ');
                $(this).find('.end_time').find('input').addClass('is-invalid  ');
                $('<div class="invalid-feedback">Start time should be less than end time</div>').insertAfter($(this).find('.end_time').find('input'));
            }


        });

        //----------------------




        App.loading(true);
        $form.find('button[type="submit"]')
            .text('Saving')
            .attr('disabled', true);



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
                            window.location.href = "{{ route('admin.artist-booking.index', ['type'=> $type, 'user_id'=> $user_id]) }}";
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

@stop