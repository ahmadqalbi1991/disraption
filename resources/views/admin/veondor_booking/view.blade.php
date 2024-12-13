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
        color: #1D3466;
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
        background: #1bd1ea;
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


<?php

use App\Models\Vendor\YachtOrder;

?>

@section('content')

<?php $permission_id = "yatch_booking_view"; ?>


<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <img class="img-fluid" src="{{ get_uploaded_image_url($booking->yacht->photos[0]->filename, 'yatch') }}" style="width: 120px; border-radius: 8px; margin-right: 10px">
                    <h3 class="mb-0">{{$booking->yacht->name}}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">

        <div class="card">
            <h5 class="card-header mb-0">Booking Details</h5>
            <div class="card-body">
                <input type="hidden" name="user_id" id="cid" value="{{ $user_id }}">
                @csrf()
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between guest-hours-list">
                            <div>
                                <span class="text-muted">Booking Id</span>
                                <h6>#{{$booking->order_number}}</h6>
                            </div>
                            <div class="text-right">
                                <span class="text-muted">Package Price</span>
                                <h6>{{YachtOrder::GrandTotalAmount($booking)}} AED</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between guest-hours-list">
                            <div>
                                <span class="text-muted">Yacht Rate Type</span>
                                <h6>{{$booking->rates_type}}</h6>
                            </div>
                            <div class="text-right">
                                <span class="text-muted">Yacht hourly rate</span><br>
                                <span class="badge badge-primary mb-0 text-capitalize" style="padding: 2px 6px; font-size: 16px; display: block; margin-left: auto; width: fit-content;">{{$booking->rate}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between guest-hours-list">
                            <div>
                                <span class="text-muted">Booking Date</span>
                                <h6>{{web_date_in_timezone($booking->date,'d-m-Y h:i A')}}</h6>
                            </div>
                            <div class="text-right">
                                <span class="text-muted">Booking Status</span><br>
                                <span class="badge badge-primary mb-0 text-capitalize" style="padding: 2px 6px; font-size: 16px; display: block; margin-left: auto; width: fit-content;">{{$booking->status}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row mt-3 align-items-center">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between guest-hours-list">
                            <div class="small-border">
                                <hr style="margin-top: 20px;">
                            </div>
                            <div>
                                <span class="text-muted">Check in</span>
                                <h6>{{$booking->start_time}}</h6>
                            </div>
                            <div class="hours position-relative">
                                @php
                                $startTime = strtotime($booking->start_time);
                                $endTime = strtotime($booking->end_time);

                                // Round up the decimal to int
                                $hours = ceil(abs($endTime - $startTime) / 3600);
                                @endphp
                                <h6 class="position-relative mb-0 ">{{$hours}} hours</h6>

                            </div>

                            <div class="text-right">
                                <span class="text-muted">Check out</span>
                                <h6>{{$booking->end_time}}</h6>
                            </div>


                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">

                            <div>
                                <span class="text-muted">Date</span>
                                <h6>{{web_date_in_timezone($booking->date,'d-m-Y h:i A')}}</h6>
                            </div>

                            <div class="text-right">
                                <span class="text-muted">No. of Guests</span>
                                <h6>{{$booking->guests_adults}} Adults & {{$booking->guests_children}} Childern</h6>
                            </div>


                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex align-items-center mt-3">
                    <svg width="60px" height="60px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2c-4.4 0-8 3.6-8 8 0 5.4 7 11.5 7.3 11.8.2.1.5.2.7.2.2 0 .5-.1.7-.2.3-.3 7.3-6.4 7.3-11.8 0-4.4-3.6-8-8-8zm0 17.7c-2.1-2-6-6.3-6-9.7 0-3.3 2.7-6 6-6s6 2.7 6 6-3.9 7.7-6 9.7zM12 6c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z" fill="#0D0D0D" />
                    </svg>
                    <div>
                        <span class="text-muted">Starting Point</span>
                        <h6><a href="https://www.google.com/maps/place/dubai/data=!4m2!3m1!1s0x3e5f43496ad9c645:0xbde66e5084295162?sa=X&ved=1t:155783&ictx=111" target="_blank">{{$booking->location_name}}</a></h6>
                    </div>
                </div>
            </div>
        </div>





        <!--<div class="card mt-3">-->
        <!--    <div class="card-body">-->
        <!--        <div class="d-flex align-items-center">-->
        <!--            <svg width="60px" height="60px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2c-4.4 0-8 3.6-8 8 0 5.4 7 11.5 7.3 11.8.2.1.5.2.7.2.2 0 .5-.1.7-.2.3-.3 7.3-6.4 7.3-11.8 0-4.4-3.6-8-8-8zm0 17.7c-2.1-2-6-6.3-6-9.7 0-3.3 2.7-6 6-6s6 2.7 6 6-3.9 7.7-6 9.7zM12 6c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z" fill="#0D0D0D"/></svg>-->
        <!--            <div>-->
        <!--                <span class="text-muted">Pickup Point</span>-->
        <!--                <h6>Dubai - UAE</h6>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->

        <!--<div class="card mt-3">-->
        <!--    <h5 class="card-header mb-0">Chech In & Out, Guest Details</h5>-->
        <!--    <div class="card-body">-->
        <!--        <div class="row align-items-center">-->
        <!--            <div class="col-12">-->
        <!--                <div class="d-flex align-items-center justify-content-between guest-hours-list">-->
        <!--                    <div class="small-border"> <hr></div>-->
        <!--                    <div>-->
        <!--                        <span>Check in</span>-->
        <!--                        <h6>05:00 PM</h6>-->
        <!--                    </div>-->
        <!--                    <div class="hours position-relative">-->
        <!--                        <h6 class="position-relative mb-0 ">3 hours</h6>-->
        <!--                    </div>-->

        <!--                    <div class="text-right">-->
        <!--                        <span>Check out</span>-->
        <!--                        <h6>08:00 PM</h6>-->
        <!--                    </div>-->


        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="col-12">-->
        <!--                <hr>-->
        <!--            </div>-->
        <!--            <div class="col-12">-->
        <!--                <div class="d-flex align-items-center justify-content-between">-->

        <!--                    <div>-->
        <!--                        <span>Date</span>-->
        <!--                        <h6>15 Mar 2024</h6>-->
        <!--                    </div>-->

        <!--                    <div class="text-right">-->
        <!--                        <span>No. of Guests</span>-->
        <!--                        <h6>4 Adults & 0 Childern</h6>-->
        <!--                    </div>-->


        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
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
                                    @if ($booking->booking_for == "myself")
                                    {{$booking->user->name}}
                                    @else
                                    {{$booking->user->cstmr_first_name}} {{$booking->user->cstmr_last_name}}
                                    @endif

                                </h6>
                            </div>
                            <hr>
                            <div>
                                <span class="text-muted">Email</span>
                                <h6>
                                    @if ($booking->booking_for == "myself")
                                    {{$booking->user->email}}
                                    @else
                                    {{$booking->user->cstmr_email}}
                                    @endif
                                </h6>
                            </div>
                            <hr>
                            <div>
                                <span class="text-muted">Phone Number</span>
                                <h6>
                                    @if ($booking->booking_for == "myself")
                                    +{{$booking->user->dial_code}} {{$booking->user->phone}}
                                    @else
                                    +{{$booking->user->cstmr_dial_code}} {{$booking->user->cstmr_phone}}
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                    @if ($booking->user->user_image)
                    <div class="col-lg-4">
                        <div class="text-right">
                            <img src="{{ get_uploaded_image_url($booking->user->user_image, 'users') }}" class="img-fluid" style="border-radius:10px; width: 100px; height: 100px; border: 1px solid #1bd1ea;">
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    

        <div class="card mt-3">
            <h5 class="card-header mb-0">Yatch Details</h5>
            <div class="card-body">
                <input type="hidden" name="user_id" id="cid" value="{{ $user_id }}">
                @csrf()
                <div class="row align-items-center">

                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span>Name</span>
                                <h6>{{$booking->yacht->name}}</h6>
                            </div>

                            <div class="text-right">
                                <span>Size</span>
                                <h6>{{$booking->yacht->size}}</h6>
                            </div>


                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">

                            <div>
                                <span>Capacity</span>
                                <h6>{{$booking->yacht->capacity}}</h6>
                            </div>

                            <div class="text-right">
                                <span>Type</span>
                                <h6>{{$booking->yacht->yatchType->name}}</h6>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <h5 class="card-header mb-0">Captain Details</h5>
            <div class="card-body">
                <div class="row align-items-center">

                    <div class="col-lg-8">
                        <div class="">
                            <div>
                                <span class="text-muted">Name</span>
                                <h6>{{$booking->user->name}}</h6>
                            </div>
                            <hr>
                            <div>
                                <span class="text-muted">Phone Number</span>
                                <h6>+971 589632584</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 d-none">
                        <div class="text-right">
                         
                            <img src="{{ get_uploaded_image_url('', 'vendor_user') }}" class="img-fluid" style="border-radius:10px; width: 100px; height: 100px; border: 1px solid #1bd1ea;">
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="card mt-3">
            <h5 class="card-header mb-0">Price Details</h5>
            <div class="card-body">
                <div class="row align-items-center">

                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span>Yacht Price</span>
                            </div>

                            <div class="text-right">
                                <h6>AED {{$booking->total}}</h6>
                            </div>


                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>


                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span>Discount</span>
                            </div>

                            <div class="text-right">
                                <h6>AED {{$booking->discount}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span>Tax</span>
                            </div>

                            <div class="text-right">
                                <h6>AED {{$booking->tax}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0" style="color: #1D3466;">Grand Total</h5>
                            </div>

                            <div class="text-right">
                                <h5>AED {{YachtOrder::GrandTotalAmount($booking)}}</h5>
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