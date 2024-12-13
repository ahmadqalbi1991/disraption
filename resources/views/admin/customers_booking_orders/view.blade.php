@extends('admin.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">

<style>
    .card {
        /* border: #f9af14 5px solid; */
        border: #1BD1EA 1px solid;
        border-radius: 15px;
        overflow: hidden;
        /*height: 100%;*/
    }

    .addon-list hr:last-child {
        display: none;
    }

    .addon-list h6,
    .card-body h6 {
        color: #ffffff;
        margin: 0;
        font-size: 16px;
        line-height: normal;
    }

    .guest-hours-list .hours {
        line-height: normal;
        padding: 10px 14px;
        border: 1px solid #eee;
        background: #eee;
        display: block;
        border-radius: 50rem;
    }

    .guest-hours-list .hours h6 {
        color: black
    }

    .guest-hours-list .small-border {
        content: '';
        position: absolute;
        z-index: 0;
        top: 0;
        left: 0;
        margin: auto;
        right: auto;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .guest-hours-list .small-border hr {
        width: 200px;
        margin-left: 0;
        border-top: 1.5px dashed #e3e2e2;
    }

    .card-header {
        background: linear-gradient(90deg, #EA33C7 0%, #0507EA 100%, #0507EA 100%) !important;
        color: #fff;
    }

    span.text-muted {
        margin-bottom: 5px;
        display: inline-block;
    }

    hr {
        margin-top: 15px;
        margin-bottom: 15px;
        border-top: 1px solid #f1f3f1;
    }
</style>
@stop



@section('content')

<?php
use App\Models\Transaction;
?>

<?php $permission_id = "customers_booking_order"; ?>


<div class="row">

    <div class="col-12 mb-4">

        <div class="card">
            <h5 class="card-header mb-0">Booking Details</h5>
            <div class="card-body">
         
                <div class="row">
                    <div class="col-12">
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
                        <hr>
                    </div>


                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between guest-hours-list">
                            <div>
                                <span class="text-muted">Order Date</span>
                                <h6>{{web_date_in_timezone($booking->date,'d-m-Y h:i A')}}</h6>
                            </div>
                            <div class="text-right">
                                <span class="text-muted">Order Status</span><br>
                                <span class="badge badge-primary mb-0 text-capitalize" style="padding: 2px 6px; font-size: 16px; display: block; margin-left: auto; width: fit-content;">{{$booking->status}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row mt-3 align-items-center">
                    <div class="col-12">

                        @foreach($booking->dates as $date)
                        <div class="d-flex align-items-center justify-content-between guest-hours-list mt-4">

                            <div>
                                <span class="text-muted">Booking Date</span>
                                <h6>{{web_date_in_timezone($date->date,'d-m-Y')}}</h6>
                            </div>

                            <div>
                                <span class="text-muted">Start time</span>
                                <h6>{{$date->start_time}}</h6>
                            </div>
                            <div class="hours position-relative">
                                @php
                                $startTime = strtotime($date->start_time);
                                $endTime = strtotime($date->end_time);

                                // Round up the decimal to int
                                $hours = ceil(abs($endTime - $startTime) / 3600);
                                @endphp
                                <h6 class="position-relative mb-0 ">{{$hours}} hours</h6>

                            </div>

                            <div class="text-right">
                                <span class="text-muted">End time</span>
                                <h6>{{$date->end_time}}</h6>
                            </div>


                        </div>
                        @endforeach

                    </div>


                </div>

                <hr>


            </div>
        </div>


    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <h5 class="card-header mb-0">Customer Details</h5>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="">
                            <div>
                                <span class="text-muted">Name</span>
                                <h6>
                                    {{$booking->customer->name}}


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

    <div class="col-lg-6 mb-4">
        <div class="card">
            <h5 class="card-header mb-0">Artist Details</h5>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="">
                            <div>
                                <span class="text-muted">Name</span>
                                <h6>
                                    {{$booking->vendor->name}}


                                </h6>
                            </div>
                            <hr>
                            <div>
                                <span class="text-muted">Email</span>
                                <h6>
                                    {{$booking->vendor->email}}
                                </h6>
                            </div>
                            <hr>
                            <div>
                                <span class="text-muted">Phone Number</span>
                                <h6>
                                    +{{$booking->vendor->dial_code}} {{$booking->vendor->phone}}
                                </h6>
                            </div>
                        </div>
                    </div>
                    @if ($booking->vendor->user_image)
                    <div class="col-lg-4">
                        <div class="text-right">
                            <img src="{{ get_uploaded_image_url($booking->vendor->user_image, 'vendor_user') }}" class="img-fluid" style="border-radius:10px; width: 100px; height: 100px; border: 1px solid #1bd1ea;">
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>


    <div class="card mt-3 mb-5" id="booking_overview">
        <h5 class="card-header mb-0">Price Details</h5>
        <div class="card-body">
            <div class="row align-items-center">


                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span>Hourly Rate</span>
                        </div>

                        <div class="text-right">
                            <h6>AED <span class="hourly_rate">{{$booking->hourly_rate}}</span></h6>
                        </div>


                    </div>
                </div>



                <div class="col-12">
                    <hr>
                </div>
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span>Total Hours Amount</span>
                        </div>

                        <div class="text-right">
                            <h6>AED <span class="total_amount">{{$booking->total}}</span></h6>
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
                            <h6>AED <span class="advance_amount">{{$booking->advance}}</span></h6>
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
                            <h6>AED <span class="tax">{{$booking->tax}}</span> (5% VAT)</h6>
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
                            <h5>AED <span class="grand_total">{{$booking->total_with_tax}}</span></h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <div class="col-12 mt-5">

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
                                <td>{{ ucfirst($transaction->status)}}</td>

                                <td>{{web_date_in_timezone($transaction->created_at,'d-m-Y h:i A')}}</td>

                            </tr>

                            @endforeach
                        </tbody>
                    </table>


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



    $('body').off('submit', '#admin-form');
    $('body').on('submit', '#admin-form', function(e) {
        e.preventDefault();
        var $form = $(this);
        var formData = new FormData(this);
        $(".invalid-feedback").remove();

        App.loading(true);
        $form.find('button[type="submit"]')
            .text('Saving')
            .attr('disabled', true);

        var parent_tree = $('option:selected', "#parent_id").attr('data-tree');
        formData.append("parent_tree", parent_tree);

        // @todo, remove the below two lines after implementing the google map api
        formData.set('location', '234235, 34365645');
        formData.set('location_name', 'Al Safa St Downtown Dubai - Dubai United Arab Emirates');

        // Add_represent_details if selected then set the value 1 else set 0
        formData.set('is_social', $("#is_social").is(':checked') ? 1 : 0);


        // Save form data
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
                            var error = $form.find('.is-invalid').eq(0);
                            $('html, body').animate({
                                scrollTop: (error.offset().top - 100),
                            }, 500);
                        });
                    }

                    // If provided the message then show it
                    if (res['message']) {
                        App.alert(res['message'], 'Oops!');
                    }
                } else {
                    App.alert(res['message'], 'Success!');

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


    $('#example2').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
    });
</script>
@stop