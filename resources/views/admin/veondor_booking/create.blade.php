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

        .fc-license-message {
            display: none;
        }
    </style>
@stop
@section('content')

    @php

        use App\Models\Transaction;
        use App\Models\Vendor\VendorBooking;
        use App\Models\Vendor\VendorBookingMedia;

        $haveOrder = $booking ? true : false;
        $orderStatus = $haveOrder ? $booking->status : "";
        $disableDates = $orderStatus == 'completed' || $orderStatus == 'cancelled' ? true : false;
        $rowColumnCss = $user_id == "all" ? 'col-md-4' : 'col-md-6';

        $isVendor = Auth::user()->user_type_id == 3 ? true : false;

    @endphp

    <form method="post" id="admin-form"
          action="{{ route(route_name_admin_vendor($type, 'artist-booking.save'), ['type'=> $type, 'user_id'=> $user_id]) }}"
          enctype="multipart/form-data" data-parsley-validate="true">

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
                                            <input class="form-check-input" type="radio" name="option" id="option_email"
                                                   checked value="email">
                                            <label class="form-check-label" for="option_email">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="option" id="option_phone"
                                                   value="phone">
                                            <label class="form-check-label" for="option_phone">Phone</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 email_div">
                            <div class="form-group">
                                <label>Email <b class="text-danger">*</b></label>
                                <input id="find_cust_email" name="custm_email" type="email" class="form-control"
                                       data-parsley-required-message="Enter Email">
                            </div>

                        </div>


                        <div class="col-md-6 phone_div" style="display: none">
                            <div class="form-group">
                                <label>Phone No<b class="text-danger">*</b></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <select class="form-control jqv-input product_catd select2"
                                                name="custm_dialcode" id="find_cust_dialcode" data-role="select2"
                                                data-placeholder="Select Code" data-allow-clear="true"
                                                data-parsley-required-message="Select Code">
                                            @foreach ($countries as $cnt)
                                                <option value="{{ $cnt->dial_code }}">+{{ $cnt->dial_code }}</option>
                                            @endforeach;
                                        </select>
                                    </div>
                                    <input autocomplete="off" type="number"
                                           class="form-control frmt_number nmbr_no_arrow" name="custm_phone"
                                           id="find_cust_phone" value="" data-jqv-required="true"
                                           data-parsley-required-message="Enter Phone number" data-parsley-type="digits"
                                           data-parsley-minlength="5" data-parsley-maxlength="12"
                                           data-parsley-trigger="keyup">
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
                                    <button id="remove_user" type="button" class="btn btn-primary">Remove Customer
                                    </button>
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
                                <input type="text" name="reference_no" id="reference_no" disabled class="form-control"
                                       value="{{ $reference_number }}">
                            </div>
                        </div>

                        @if ($user_id == "all")

                            <div class="{{$rowColumnCss}}">
                                <div class="form-group">
                                    <label>Artist <span style="color:red;">*<span></label>
                                    <select id="vendors" class="form-control jqv-input product_catd select2"
                                            name="user_id" data-role="select2" data-placeholder="Select Artist"
                                            data-allow-clear="true" required
                                            data-parsley-required-message="Select Artist">
                                        <option value=""> Select Artist</option>
                                        @foreach($vendors as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }} ({{ $user->vendor_details->username}})
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
                                    <select class="form-control jqv-input product_catd select2" name="customer_id"
                                            data-role="select2" data-placeholder="Select Customer"
                                            data-allow-clear="true" required
                                            data-parsley-required-message="Select Customer">
                                        <option value=""> Select Customer</option>
                                        @foreach($customers as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }} {{ $user->user_name ? '- ' . $user->user_name : '' }}
                                                ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        @endif


                        <div class="{{$rowColumnCss}}">
                            <div class="form-group">
                                <label>Remarks <span style="color:red;">*<span></label>
                                <input type="text" name="title" class="form-control" required
                                       data-parsley-required-message="Enter title" value="{{ $title }}">
                            </div>
                        </div>

                        <div class="{{$rowColumnCss}}">
                            <div class="form-group">
                                <label>Duration</label>
                                <select class="form-control jqv-input product_catd select2" name="duration"
                                        id="duration" data-role="select2" data-placeholder="Select duration"
                                        data-allow-clear="true" required
                                        data-parsley-required-message="Select Duration">

                                    @foreach($durationArray as $iDuration)
                                        <option
                                            value="{{ $iDuration['value'] }}" {{ $duration == $iDuration['value'] ? 'selected' : '' }}>
                                            {{ $iDuration['text'] }}
                                        </option>
                                    @endforeach


                                </select>
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
                                        <input type="file" name="newMedias[]" class="form-control" multiple
                                               accept="image/jpeg, image/png, image/gif" data-parsley-trigger="change"
                                               data-parsley-fileextension="jpg,png,gif,jpeg"
                                               data-parsley-fileextension-message="Only files with types jpg, png, gif, jpeg are supported"
                                               data-parsley-max-file-size="5120">
                                    @endif

                                    <span class="text-info d-none">Upload Images (Maximum 5 medias allowed)</span>
                                    <div id="image-previews" class="preview-imgs mt-4">
                                        @foreach($medias as $media)
                                            <div class="img-preview-box position-relative">


                                                <a data-fancybox target="_blank" href="{{ $media->media_url }}"
                                                   class="b_img_div overflow-hidden w-100">
                                                    <img src="{{ $media->media_url }}" class="img-fluid w-100">
                                                </a>

                                                @if (!$disableDates)
                                                    <div class="del-product-img" data-role="product-img-trash"
                                                         data-rid="{{$media->id}}">
                                                        <svg class="svg-inline--fa fa-trash-can" aria-hidden="true"
                                                             focusable="false" data-prefix="far" data-icon="trash-can"
                                                             role="img" xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 448 512" data-fa-i2svg="">
                                                            <path fill="currentColor"
                                                                  d="M160 400C160 408.8 152.8 416 144 416C135.2 416 128 408.8 128 400V192C128 183.2 135.2 176 144 176C152.8 176 160 183.2 160 192V400zM240 400C240 408.8 232.8 416 224 416C215.2 416 208 408.8 208 400V192C208 183.2 215.2 176 224 176C232.8 176 240 183.2 240 192V400zM320 400C320 408.8 312.8 416 304 416C295.2 416 288 408.8 288 400V192C288 183.2 295.2 176 304 176C312.8 176 320 183.2 320 192V400zM317.5 24.94L354.2 80H424C437.3 80 448 90.75 448 104C448 117.3 437.3 128 424 128H416V432C416 476.2 380.2 512 336 512H112C67.82 512 32 476.2 32 432V128H24C10.75 128 0 117.3 0 104C0 90.75 10.75 80 24 80H93.82L130.5 24.94C140.9 9.357 158.4 0 177.1 0H270.9C289.6 0 307.1 9.358 317.5 24.94H317.5zM151.5 80H296.5L277.5 51.56C276 49.34 273.5 48 270.9 48H177.1C174.5 48 171.1 49.34 170.5 51.56L151.5 80zM80 432C80 449.7 94.33 464 112 464H336C353.7 464 368 449.7 368 432V128H80V432z"></path>
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

                    @if(count($booking_dates) > 0)
                        <div class="col-md-12 mb-3">

                            @php
                                // Initialize an empty array to store booking dates
                                $booking_dates_array = [];

                                // Loop through the $booking_dates array and add dates to the $booking_dates_array
                                foreach ($booking_dates as $date) {
                                $booking_dates_array[] = $date["date"];
                                }

                                // Remove duplicate dates
                                $booking_dates_array = array_unique($booking_dates_array);

                                // Sort the dates in ascending order
                                sort($booking_dates_array);
                            @endphp

                            <p><strong>Event found on the following Dates:</strong>
                                @foreach($booking_dates_array as $date)
                                    <span>{{$date}}, </span>
                                @endforeach
                            </p>
                        </div>
                    @endif

                    <div class="col-md-12">
                        <div id="calendar" class="fc fc-media-screen fc-direction-ltr fc-theme-standard">
                            <div class="fc-header-toolbar fc-toolbar fc-toolbar-ltr">
                                <div class="fc-toolbar-chunk">
                                    <button type="button" title="Today" disabled="" aria-pressed="false"
                                            class="fc-today-button fc-button fc-button-primary">today
                                    </button>
                                </div>
                                <div class="fc-toolbar-chunk">
                                    <div class="">
                                        <button type="button" title="Previous day" aria-pressed="false"
                                                class="fc-prev-button fc-button fc-button-primary"><span
                                                class="fc-icon fc-icon-chevron-left" role="img"></span></button>
                                        <h2 class="fc-toolbar-title" id="fc-dom-1">June 7, 2024</h2>
                                        <button type="button" title="Next day" aria-pressed="false"
                                                class="fc-next-button fc-button fc-button-primary"><span
                                                class="fc-icon fc-icon-chevron-right" role="img"></span></button>
                                    </div>
                                </div>
                                <div class="fc-toolbar-chunk">
                                    <button type="button" title="day view" aria-pressed="true"
                                            class="fc-resourceTimelineDay-button fc-button fc-button-primary fc-button-active">
                                        day
                                    </button>
                                </div>
                            </div>
                            <div aria-labelledby="fc-dom-1" class="fc-view-harness fc-view-harness-active"
                                 style="height: 614.573px;">
                                <div
                                    class="fc-resourceTimelineDay-view fc-view fc-resource-timeline fc-resource-timeline-flat fc-timeline fc-timeline-overlap-enabled">
                                    <table role="grid" class="fc-scrollgrid  fc-scrollgrid-liquid">
                                        <colgroup>
                                            <col style="width: 30%;">
                                            <col>
                                            <col>
                                        </colgroup>
                                        <thead role="rowgroup">
                                        <tr role="presentation"
                                            class="fc-scrollgrid-section fc-scrollgrid-section-header ">
                                            <th role="presentation">
                                                <div class="fc-scroller-harness">
                                                    <div class="fc-scroller" style="overflow: hidden;">
                                                        <table role="presentation"
                                                               class="fc-datagrid-header fc-scrollgrid-sync-table"
                                                               style="width: 365px;">
                                                            <colgroup>
                                                                <col>
                                                            </colgroup>
                                                            <thead role="presentation">
                                                            <tr role="row">
                                                                <th role="columnheader" class="fc-datagrid-cell">
                                                                    <div class="fc-datagrid-cell-frame"
                                                                         style="height: 37px;">
                                                                        <div
                                                                            class="fc-datagrid-cell-cushion fc-scrollgrid-sync-inner">
                                                                            <span
                                                                                class="fc-datagrid-expander fc-datagrid-expander-placeholder"><span
                                                                                    class="fc-icon"></span></span><span
                                                                                class="fc-datagrid-cell-main">Resources</span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </th>
                                            <td role="presentation"
                                                class="fc-resource-timeline-divider fc-cell-shaded"></td>
                                            <th role="presentation">
                                                <div class="fc-scroller-harness">
                                                    <div class="fc-scroller"
                                                         style="overflow: scroll hidden; margin-bottom: -17px;">
                                                        <div class="fc-timeline-header">
                                                            <table aria-hidden="true" class="fc-scrollgrid-sync-table"
                                                                   style="min-width: 600px; width: 851px;">
                                                                <colgroup>
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                    <col style="min-width: 30px;">
                                                                </colgroup>
                                                                <tbody>
                                                                <tr class="fc-timeline-header-row fc-timeline-header-row-chrono">
                                                                    <th colspan="2" data-date="2024-06-07T10:00:00"
                                                                        class="fc-timeline-slot fc-timeline-slot-label fc-slot fc-slot-fri fc-slot-today fc-slot-past">
                                                                        <div class="fc-timeline-slot-frame"
                                                                             style="height: 37px;"><a
                                                                                class="fc-timeline-slot-cushion fc-scrollgrid-sync-inner">10am</a>
                                                                        </div>
                                                                    </th>
                                                                    <th colspan="2" data-date="2024-06-07T11:00:00"
                                                                        class="fc-timeline-slot fc-timeline-slot-label fc-slot fc-slot-fri fc-slot-today fc-slot-past">
                                                                        <div class="fc-timeline-slot-frame"
                                                                             style="height: 37px;"><a
                                                                                class="fc-timeline-slot-cushion fc-scrollgrid-sync-inner">11am</a>
                                                                        </div>
                                                                    </th>
                                                                    <th colspan="2" data-date="2024-06-07T12:00:00"
                                                                        class="fc-timeline-slot fc-timeline-slot-label fc-slot fc-slot-fri fc-slot-today fc-slot-past">
                                                                        <div class="fc-timeline-slot-frame"
                                                                             style="height: 37px;"><a
                                                                                class="fc-timeline-slot-cushion fc-scrollgrid-sync-inner">12pm</a>
                                                                        </div>
                                                                    </th>
                                                                    <th colspan="2" data-date="2024-06-07T13:00:00"
                                                                        class="fc-timeline-slot fc-timeline-slot-label fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                        <div class="fc-timeline-slot-frame"
                                                                             style="height: 37px;"><a
                                                                                class="fc-timeline-slot-cushion fc-scrollgrid-sync-inner">1pm</a>
                                                                        </div>
                                                                    </th>
                                                                    <th colspan="2" data-date="2024-06-07T14:00:00"
                                                                        class="fc-timeline-slot fc-timeline-slot-label fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                        <div class="fc-timeline-slot-frame"
                                                                             style="height: 37px;"><a
                                                                                class="fc-timeline-slot-cushion fc-scrollgrid-sync-inner">2pm</a>
                                                                        </div>
                                                                    </th>
                                                                    <th colspan="2" data-date="2024-06-07T15:00:00"
                                                                        class="fc-timeline-slot fc-timeline-slot-label fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                        <div class="fc-timeline-slot-frame"
                                                                             style="height: 37px;"><a
                                                                                class="fc-timeline-slot-cushion fc-scrollgrid-sync-inner">3pm</a>
                                                                        </div>
                                                                    </th>
                                                                    <th colspan="2" data-date="2024-06-07T16:00:00"
                                                                        class="fc-timeline-slot fc-timeline-slot-label fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                        <div class="fc-timeline-slot-frame"
                                                                             style="height: 37px;"><a
                                                                                class="fc-timeline-slot-cushion fc-scrollgrid-sync-inner">4pm</a>
                                                                        </div>
                                                                    </th>
                                                                    <th colspan="2" data-date="2024-06-07T17:00:00"
                                                                        class="fc-timeline-slot fc-timeline-slot-label fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                        <div class="fc-timeline-slot-frame"
                                                                             style="height: 37px;"><a
                                                                                class="fc-timeline-slot-cushion fc-scrollgrid-sync-inner">5pm</a>
                                                                        </div>
                                                                    </th>
                                                                    <th colspan="2" data-date="2024-06-07T18:00:00"
                                                                        class="fc-timeline-slot fc-timeline-slot-label fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                        <div class="fc-timeline-slot-frame"
                                                                             style="height: 37px;"><a
                                                                                class="fc-timeline-slot-cushion fc-scrollgrid-sync-inner">6pm</a>
                                                                        </div>
                                                                    </th>
                                                                    <th colspan="2" data-date="2024-06-07T19:00:00"
                                                                        class="fc-timeline-slot fc-timeline-slot-label fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                        <div class="fc-timeline-slot-frame"
                                                                             style="height: 37px;"><a
                                                                                class="fc-timeline-slot-cushion fc-scrollgrid-sync-inner">7pm</a>
                                                                        </div>
                                                                    </th>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody role="rowgroup">
                                        <tr role="presentation"
                                            class="fc-scrollgrid-section fc-scrollgrid-section-body  fc-scrollgrid-section-liquid">
                                            <td role="presentation">
                                                <div class="fc-scroller-harness fc-scroller-harness-liquid">
                                                    <div class="fc-scroller fc-scroller-liquid-absolute"
                                                         style="overflow: hidden scroll; right: -17px;">
                                                        <table role="presentation"
                                                               class="fc-datagrid-body fc-scrollgrid-sync-table"
                                                               style="width: 365px;">
                                                            <colgroup>
                                                                <col>
                                                            </colgroup>
                                                            <tbody role="presentation">
                                                            <tr role="row">
                                                                <td role="gridcell" data-resource-id="a"
                                                                    class="fc-datagrid-cell fc-resource">
                                                                    <div class="fc-datagrid-cell-frame"
                                                                         style="height: 37px;">
                                                                        <div
                                                                            class="fc-datagrid-cell-cushion fc-scrollgrid-sync-inner">
                                                                            <span
                                                                                class="fc-datagrid-expander fc-datagrid-expander-placeholder"><span
                                                                                    class="fc-icon"></span></span><span
                                                                                class="fc-datagrid-cell-main">Workstation A</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr role="row">
                                                                <td role="gridcell" data-resource-id="b"
                                                                    class="fc-datagrid-cell fc-resource">
                                                                    <div class="fc-datagrid-cell-frame"
                                                                         style="height: 37px;">
                                                                        <div
                                                                            class="fc-datagrid-cell-cushion fc-scrollgrid-sync-inner">
                                                                            <span
                                                                                class="fc-datagrid-expander fc-datagrid-expander-placeholder"><span
                                                                                    class="fc-icon"></span></span><span
                                                                                class="fc-datagrid-cell-main">Workstation B</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr role="row">
                                                                <td role="gridcell" data-resource-id="c"
                                                                    class="fc-datagrid-cell fc-resource">
                                                                    <div class="fc-datagrid-cell-frame"
                                                                         style="height: 37px;">
                                                                        <div
                                                                            class="fc-datagrid-cell-cushion fc-scrollgrid-sync-inner">
                                                                            <span
                                                                                class="fc-datagrid-expander fc-datagrid-expander-placeholder"><span
                                                                                    class="fc-icon"></span></span><span
                                                                                class="fc-datagrid-cell-main">Workstation C</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr role="row">
                                                                <td role="gridcell" data-resource-id="d"
                                                                    class="fc-datagrid-cell fc-resource">
                                                                    <div class="fc-datagrid-cell-frame"
                                                                         style="height: 37px;">
                                                                        <div
                                                                            class="fc-datagrid-cell-cushion fc-scrollgrid-sync-inner">
                                                                            <span
                                                                                class="fc-datagrid-expander fc-datagrid-expander-placeholder"><span
                                                                                    class="fc-icon"></span></span><span
                                                                                class="fc-datagrid-cell-main">Workstation D</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr role="row">
                                                                <td role="gridcell" data-resource-id="e"
                                                                    class="fc-datagrid-cell fc-resource">
                                                                    <div class="fc-datagrid-cell-frame"
                                                                         style="height: 37px;">
                                                                        <div
                                                                            class="fc-datagrid-cell-cushion fc-scrollgrid-sync-inner">
                                                                            <span
                                                                                class="fc-datagrid-expander fc-datagrid-expander-placeholder"><span
                                                                                    class="fc-icon"></span></span><span
                                                                                class="fc-datagrid-cell-main">Workstation E</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr role="row">
                                                                <td role="gridcell" data-resource-id="f"
                                                                    class="fc-datagrid-cell fc-resource">
                                                                    <div class="fc-datagrid-cell-frame"
                                                                         style="height: 37px;">
                                                                        <div
                                                                            class="fc-datagrid-cell-cushion fc-scrollgrid-sync-inner">
                                                                            <span
                                                                                class="fc-datagrid-expander fc-datagrid-expander-placeholder"><span
                                                                                    class="fc-icon"></span></span><span
                                                                                class="fc-datagrid-cell-main">Workstation F</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </td>
                                            <td role="presentation"
                                                class="fc-resource-timeline-divider fc-cell-shaded"></td>
                                            <td role="presentation">
                                                <div class="fc-scroller-harness fc-scroller-harness-liquid">
                                                    <div class="fc-scroller fc-scroller-liquid-absolute"
                                                         style="overflow: auto;">
                                                        <div class="fc-timeline-body " style="min-width: 600px;">
                                                            <div class="fc-timeline-slots">
                                                                <table aria-hidden="true" class=""
                                                                       style="min-width: 600px; width: 851px;">
                                                                    <colgroup>
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                        <col style="min-width: 30px;">
                                                                    </colgroup>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td data-date="2024-06-07T10:00:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-major fc-slot fc-slot-fri fc-slot-today fc-slot-past">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T10:30:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-minor fc-slot fc-slot-fri fc-slot-today fc-slot-past">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T11:00:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-major fc-slot fc-slot-fri fc-slot-today fc-slot-past">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T11:30:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-minor fc-slot fc-slot-fri fc-slot-today fc-slot-past">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T12:00:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-major fc-slot fc-slot-fri fc-slot-today fc-slot-past">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T12:30:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-minor fc-slot fc-slot-fri fc-slot-today fc-slot-past">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T13:00:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-major fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T13:30:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-minor fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T14:00:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-major fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T14:30:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-minor fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T15:00:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-major fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T15:30:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-minor fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T16:00:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-major fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T16:30:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-minor fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T17:00:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-major fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T17:30:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-minor fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T18:00:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-major fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T18:30:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-minor fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T19:00:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-major fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                        <td data-date="2024-06-07T19:30:00"
                                                                            class="fc-timeline-slot fc-timeline-slot-lane fc-timeline-slot-minor fc-slot fc-slot-fri fc-slot-today fc-slot-future">
                                                                            <div class=""></div>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="fc-timeline-bg"></div>
                                                            <table aria-hidden="true" class="fc-scrollgrid-sync-table "
                                                                   style="min-width: 600px; width: 851px;">
                                                                <tbody>
                                                                <tr>
                                                                    <td data-resource-id="a"
                                                                        class="fc-timeline-lane fc-resource">
                                                                        <div class="fc-timeline-lane-frame"
                                                                             style="height: 37px;">
                                                                            <div class="fc-timeline-lane-misc"></div>
                                                                            <div class="fc-timeline-bg"></div>
                                                                            <div
                                                                                class="fc-timeline-events fc-scrollgrid-sync-inner"
                                                                                style="height: 0px;"></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td data-resource-id="b"
                                                                        class="fc-timeline-lane fc-resource">
                                                                        <div class="fc-timeline-lane-frame"
                                                                             style="height: 37px;">
                                                                            <div class="fc-timeline-lane-misc"></div>
                                                                            <div class="fc-timeline-bg"></div>
                                                                            <div
                                                                                class="fc-timeline-events fc-scrollgrid-sync-inner"
                                                                                style="height: 0px;"></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td data-resource-id="c"
                                                                        class="fc-timeline-lane fc-resource">
                                                                        <div class="fc-timeline-lane-frame"
                                                                             style="height: 37px;">
                                                                            <div class="fc-timeline-lane-misc"></div>
                                                                            <div class="fc-timeline-bg"></div>
                                                                            <div
                                                                                class="fc-timeline-events fc-scrollgrid-sync-inner"
                                                                                style="height: 0px;"></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td data-resource-id="d"
                                                                        class="fc-timeline-lane fc-resource">
                                                                        <div class="fc-timeline-lane-frame"
                                                                             style="height: 37px;">
                                                                            <div class="fc-timeline-lane-misc"></div>
                                                                            <div class="fc-timeline-bg"></div>
                                                                            <div
                                                                                class="fc-timeline-events fc-scrollgrid-sync-inner"
                                                                                style="height: 0px;"></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td data-resource-id="e"
                                                                        class="fc-timeline-lane fc-resource">
                                                                        <div class="fc-timeline-lane-frame"
                                                                             style="height: 37px;">
                                                                            <div class="fc-timeline-lane-misc"></div>
                                                                            <div class="fc-timeline-bg"></div>
                                                                            <div
                                                                                class="fc-timeline-events fc-scrollgrid-sync-inner"
                                                                                style="height: 0px;"></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td data-resource-id="f"
                                                                        class="fc-timeline-lane fc-resource">
                                                                        <div class="fc-timeline-lane-frame"
                                                                             style="height: 37px;">
                                                                            <div class="fc-timeline-lane-misc"></div>
                                                                            <div class="fc-timeline-bg"></div>
                                                                            <div
                                                                                class="fc-timeline-events fc-scrollgrid-sync-inner"
                                                                                style="height: 0px;"></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="fc-license-message"
                                     style="position: absolute; z-index: 99999; bottom: 1px; left: 1px; background: rgb(238, 238, 238); border-color: rgb(221, 221, 221); border-style: solid; border-width: 1px 1px 0px 0px; padding: 2px 4px; font-size: 12px; border-top-right-radius: 3px;">
                                    Your license key is invalid. <a
                                        href="https://fullcalendar.io/docs/schedulerLicenseKey#invalid">More Info</a>
                                </div>
                            </div>
                        </div>

                        <ul class="indicators">
                            <li><span class="clr pending"></span>Deposit Pending</li>
                            <li><span class="clr paid"></span>Deposit Paid</li>
                            <li><span class="clr selct"></span>Selected Artist Session</li>
                            <li><span class="clr propose"></span>Proposed Slot</li>
                        </ul>

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
                                            <div
                                                class="d-flex align-items-center justify-content-between guest-hours-list">
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
                                            <div
                                                class="d-flex align-items-center justify-content-between guest-hours-list">
                                                <div>
                                                    <span
                                                        class="text-muted">Total Regular Amount Paid by Customer</span>
                                                    <h6>{{$booking->total_paid}} AED</h6>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <hr>
                                        </div>
                                        <div class="col-12">
                                            <div
                                                class="d-flex align-items-center justify-content-between guest-hours-list">
                                                <div>
                                                    <span
                                                        class="text-muted">Total Rescheduled Amount Paid by Customer</span>
                                                    <h6>{{$booking->total_rschdl_paid}} AED</h6>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <hr>
                                        </div>

                                        <div class="col-12">
                                            <div
                                                class="d-flex align-items-center justify-content-between guest-hours-list">
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
                                            <div
                                                class="d-flex align-items-center justify-content-between guest-hours-list">
                                                <div>
                                                    <span class="text-muted">Order Date</span>
                                                    <h6>{{web_date_in_timezone($booking->created_at,'d-m-Y h:i A')}}</h6>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-muted">Order Status</span><br>
                                                    <span class="badge mb-0 text-capitalize">

                                        @if ($booking && $booking->status == "cancelled")
                                                            <span class="badge badge-danger">Cancelled</span>
                                                        @else

                                                            <select id="order_status" class="form-control"
                                                                    data-parsley-required-message="Select Order Status">
                                                <option readonly value="{{ $booking->status }}"
                                                        selected>{{ order_statuses($booking->status) }}</option>
                                                @php
                                                    $nextStatus = next_order_statuses($booking->status);
                                                @endphp
                                                                @if (!empty($nextStatus))
                                                                    <option
                                                                        value="{{ $nextStatus['key'] }}">{{ $nextStatus['value'] }}</option>
                                                                @endif
                                                <option value="cancelled">Cancelled</option>
                                            </select>

                                                        @endif


                                        </span>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($booking && $booking->status == "cancelled" && $booking->cancel_remarks)

                                            <div class="col-12">
                                                <div
                                                    class="d-flex align-items-center justify-content-between guest-hours-list">
                                                    <div>
                                                        <span class="text-muted">Cancel Details</span>
                                                        <p class="mb-0">
                                                            <span
                                                                class="d-inline-block font-weight-bold">Remarks:&nbsp; </span><span>{{$booking->cancel_remarks}}</span>
                                                        </p>
                                                        <p class="mb-0">
                                                            <span class="d-inline-block font-weight-bold">Is refund made:&nbsp; </span>{{$booking->is_refund_made ? "Yes" : "No"}}
                                                        </p>
                                                        <p class="mb-0">
                                                            <span class="d-inline-block font-weight-bold">Attachment:&nbsp; </span><a
                                                                style="color: #02db6d;" target="_blank"
                                                                href="{{get_uploaded_image_url($booking->refund_file, VendorBookingMedia::$mediaFolderName)}}">{{$booking->refund_file}}</a>
                                                        </p>
                                                    </div>


                                                </div>
                                            </div>

                                        @endif

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
                                                <a class="yellow-color"
                                                   href="{{ route('admin.customers.edit', ['id' => $booking->customer->id]) }}">{{ucfirst($booking->customer->name)}}</a>

                                            </h6>
                                        </div>
                                        <hr>
                                        <div>
                                            <span class="text-muted">Email</span>
                                            <h6>
                                                <div><a class="yellow-color"
                                                        href="mailto:{{$booking->customer->email}}">{{$booking->customer->email}}</a>
                                                </div>


                                            </h6>
                                        </div>
                                        <hr>
                                        <div>
                                            <span class="text-muted">Phone Number</span>
                                            <h6>
                                                <div><a class="yellow-color"
                                                        href="https://wa.me/+{{$booking->customer->dial_code}}{{$booking->customer->phone}}"
                                                        target="_blank">+{{$booking->customer->dial_code}} {{$booking->customer->phone}}</a>
                                                </div>

                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                @if ($booking->customer->user_image_url)
                                    <div class="col-lg-4">
                                        <div class="text-right">
                                            <img src="{{ $booking->customer->user_image_url }}" class="img-fluid"
                                                 style="border-radius:10px; width: 100px; height: 100px; border: 1px solid #1bd1ea;">
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>


                    <div class="card mb-5">
                        <h5 class="card-header mb-0">Artist Details</h5>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="">
                                        <div>
                                            <span class="text-muted">Name</span>
                                            <h6>
                                                <a href="{{ url('/admin/artist/edit/' . $booking->user->id) }}"
                                                   class="yellow-color">{{ucfirst($booking->vendor->name)}}</a>


                                            </h6>
                                        </div>
                                        <hr>
                                        <div>
                                            <span class="text-muted">Email</span>
                                            <h6>
                                                <div><a class="yellow-color"
                                                        href="mailto:{{$booking->user->email}}">{{$booking->user->email}}</a>
                                                </div>

                                            </h6>
                                        </div>
                                        <hr>
                                        <div>
                                            <span class="text-muted">Phone Number</span>
                                            <h6>
                                                <div><a class="yellow-color"
                                                        href="https://wa.me/+{{$booking->user->dial_code}}{{$booking->user->phone}}"
                                                        target="_blank">+{{$booking->user->dial_code}} {{$booking->user->phone}}</a>
                                                </div>


                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                @if ($booking->vendor->user_image_url)
                                    <div class="col-lg-4">
                                        <div class="text-right">
                                            <img src="{{ $booking->vendor->user_image_url }}" class="img-fluid"
                                                 style="border-radius:10px; border: 1px solid #1bd1ea;">
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
                                <th>Customer name</th>
                                <th>{{ $booking->status === 'confirmed' ? 'Deposit ' : '' }}Deposit Payment</th>
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

                                    <td>{{$transaction->customer->name??''}}</td>

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
                                    {{ (Transaction::$types[$transaction->type]==' Booking Advance Payment ')?'Deposit PaymenT':Transaction::$types[$transaction->type] }}


                                </span>

                                        </div>
                                    </td>


                                    <td>{{ ucfirst($transaction->payment_method)}}</td>
                                    <td>

                                        {{ ucfirst($transaction->status) }}

                                        <!-- <select id="transaction_status" data-transaction-id="{{$transaction->id}}" class="form-control" data-parsley-required-message="Select Order Status">
                                @foreach (Transaction::$payment_status as $key => $status)
                                            <option value="{{ $key }}" @if ($key==$transaction->status)
                                                selected

                                            @endif>{{ $status }}</option>


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
        @if (!$disableDates)
            <div class="card mb-5 mt-5">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12 mt-2">
                            <div class="form-group text-center">

                                <div class="media_progress_Wrap" style="display: none;">
                                    <h5>Please wait while the media is uploading.</h5>
                                    <div class="progress mt-2" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%;"
                                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%
                                        </div>
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



    <div class="modal fade" id="cancelBookingModal" tabindex="-1" role="dialog"
         aria-labelledby="cancelBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelBookingModalLabel">Cancel Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="cancelBookingForm">
                    @csrf
                    <div class="modal-body text-left">
                        <div class="form-group mb-3">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control" name="remarks" id="cancel_remarks" rows="3"></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="is_refund">Is refund made?</label>
                            <input type="checkbox" name="is_refund" id="is_refund" value="0">
                        </div>

                        <div class="form-group mb-3" id="attachFile" style="display: none;">
                            <label for="file">Attach file</label>
                            <input type="file" name="file" id="file" class="form-control" accept=".pdf,.jpeg,.jpg,.png"
                                   data-parsley-max-file-size="10mb" data-parsley-required-message="Select file">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="cancelBookingSubmit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop
@section('script')

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.js"></script>


    <script>

        $(document).ready(function () {
            var selectedValue = $('#order_status').val();
            $('#order_status option[value="' + selectedValue + '"]').not(':selected').remove();
        });

        // Booking dates management script

        var disableDates = '{{$disableDates}}';

        $(document).ready(function () {

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


            $('#add-date').click(function () {

                // generate random uid with new prefix
                var uid = 'new-' + Math.random().toString(36).substr(2, 9);

                $('#date-table tbody').append(generateRow(uid, '', '', ''));

                addDateAndTime();

            });

            // on dynamice element click
            $('#date-table').on('click', '.delete-row', function () {
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

        var future_bookings = JSON.parse(`<?php echo json_encode($vendor_future_bookings); ?>`);


        let times = <?php echo json_encode($booking_dates); ?>;
        let futureTimes = future_bookings;
        var datesEvents = [];
        var resources = [];


        function calculateAndUpdateBookingOverview() {

            var artist = vendors_data[vendor_id];

            // -------- Ready the booking date data ------

            if (artist) {

                artist.total_hours = 0;

                var datesEvents = GetDbFormatedCalendarEvents();

                // Loop through dbDatesData and calculate total hours for each artist
                datesEvents.forEach(event => {
                    var date = event.date;
                    var start_time = event.start_time;
                    var end_time = event.end_time;

                    if (!date || !start_time || !end_time) {
                        return;
                    }

                    // Parse the start and end time
                    var start_time_date = new Date('2000-01-01 ' + start_time);
                    var end_time_date = new Date('2000-01-01 ' + end_time);

                    // Calculate the difference in hours
                    var diff = end_time_date - start_time_date;
                    var hours = diff / 1000 / 60 / 60;

                    // Assuming 'artist' is defined somewhere in the context
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
        $('#vendors').change(function () {

            var artist_id = $(this).val();
            vendor_id = artist_id;

            calculateAndUpdateBookingOverview();

            updateEevents(true);

            // Add the loading class to the calendar
            // $('#calendar').addClass('loading');

            // // Call the ajax to get the future booking date
            // $.ajax({
            //     url: "{{ route('admin.artist-booking.future_dates') }}",
            //     type: 'POST',
            //     dataType: 'json',
            //     data: {
            //         vendor_id: artist_id,
            //         _token: "{{ csrf_token() }}"
            //     },
            //     success: function(response) {


            //         if (response.status == 0) {
            //             alert("Erro while loading the future booking of the selected artist, please try to change the artist!");
            //             $('#calendar').removeClass('loading');
            //             return;
            //         }

            //         futureTimes = response.data;

            //         updateEevents(true);

            //         $('#calendar').removeClass('loading');

            //     },
            //     error: function(error) {
            //         alert("Error while loading the future booking of the selected artist, please try to change the artist!");
            //         $('#calendar').removeClass('loading');
            //     }
            // });

        });


        // ==============

        /**
         * Our db store the in the following format
         * {
    date: "2024-06-27",
    start_time: "12:00 PM",
    end_time: "08:00 PM"
}
         * For calendar we need in the following format
         {
    start: '2024-05-29T15:00:00',
    end: '2024-05-29T17:30:00',
}

         So this function will convert to calendar format

         */
        function dateTimeConvertToCalendarFormat(date, time) {
            let [timeStr, modifier] = time.split(' ');
            let [hours, minutes] = timeStr.split(':');

            if (hours === '12') {
                hours = '00';
            }

            if (modifier === 'PM') {
                hours = parseInt(hours, 10) + 12;
            }

            let formatedTime = `${hours}:${minutes}:00`;

            return `${date}T${formatedTime}`;
        }


        // This function is used to format the time to 12 hours format
        function formatTime(date) {
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;
            return hours + ':' + minutes + ' ' + ampm;
        }


        // Get json decode the times data
        let bookingResources = <?php echo json_encode($booking_resources); ?>;


        function updateEevents(updateToCalendar = false) {

            datesEvents = [];

            let booking_status = "{{ !empty($booking) ? $booking->status : 'created' }}";
            let status = booking_status;

            if (times.length === 0) {
                var startCurrentDate = new Date();
                var endCurrentDate = new Date();
                startCurrentDate.setMinutes(0, 0, 0);
                endCurrentDate.setMinutes(0, 0, 0);
                startCurrentDate.setHours(startCurrentDate.getHours() + 1);
                endCurrentDate.setHours(endCurrentDate.getHours() + 2);
                var start_hours = startCurrentDate.getHours();
                var end_hours = endCurrentDate.getHours();
                var start_period = start_hours >= 12 ? 'PM' : 'AM'; // Determine AM or PM
                var end_period = end_hours >= 12 ? 'PM' : 'AM'; // Determine AM or PM
                start_hours = start_hours % 12 || 12; // Convert to 12-hour format (handle midnight as 12)
                end_hours = end_hours % 12 || 12; // Convert to 12-hour format (handle midnight as 12)
                start_hours = start_hours + ':00 ' + start_period;
                end_hours = end_hours + ':00 ' + end_period;
                var year = startCurrentDate.getFullYear();
                var month = (startCurrentDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed, so add 1
                var day = startCurrentDate.getDate().toString().padStart(2, '0'); // Add leading zero if needed

                var formattedDate = year + '-' + month + '-' + day;

                times = [
                    {
                        booking_id: 0,
                        date: formattedDate,
                        end_time: end_hours,
                        id: 0,
                        resource_id: 0,
                        start_time: start_hours,
                        status: status
                    }
                ]
            }

            times.forEach((time, index) => {
                let start = dateTimeConvertToCalendarFormat(time.date, time.start_time);
                let end = dateTimeConvertToCalendarFormat(time.date, time.end_time);

                datesEvents.push({
                    id: time.id,
                    resourceId: time.resource_id || 1,
                    start: start,
                    end: end,
                    title: `{{isset($booking->customer)?ucfirst($booking->customer->name):''}} - {{isset($booking->vendor)?ucfirst($booking->vendor->name):''}}`,
                    classNames: [time.status],
                    isCurrent: true
                });

            });


            // loop through the future times and convert to calendar format
            // futureTimes.forEach((time, index) => {
            //     let start = dateTimeConvertToCalendarFormat(time.date, time.start_time);
            //     let end = dateTimeConvertToCalendarFormat(time.date, time.end_time);
            //
            //     // ___ Ready current date in exact format _____
            //
            //     // Get current date and time
            //     let currentDate = new Date();
            //     let currentDateString = currentDate.toISOString().split('T')[0]; // Current date in YYYY-MM-DD format
            //     let currentTimeString = currentDate.toTimeString().split(' ')[0]; // Current time in HH:MM:SS format
            //
            //     // Combine current date and time to match the format used
            //     let currentDateTime = `${currentDateString}T${currentTimeString}`;
            //
            //     // _____________________________________________
            //
            //     // Convert to Date objects for comparison
            //     let endDateTime = new Date(end);
            //     let currentDateTimeObj = new Date(currentDateTime);
            //
            //     // Check if the event is in the future
            //     if (endDateTime < currentDateTimeObj) {
            //         return;
            //     }
            //
            //     // class name based on the status
            //     let className = (time.status === 'created') ? 'pending' : 'paid';
            //
            //     if (time.resource_id) {
            //         console.log(time)
            //         datesEvents.push({
            //             id: time.id,
            //             resourceId: time.resource_id || 1,
            //             start: start,
            //             end: end,
            //             title: time.customer_name + ' - ' + time.user_name,
            //             classNames: [className],
            //             isEditable: false,
            //             noCalculate: true
            //         });
            //     }
            //
            // });

            if (updateToCalendar) {
                window.calendar.removeAllEvents();
                window.calendar.addEventSource(datesEvents);
            }

        }


        // Loop through the resources and add to the resources array
        if (bookingResources.length > 0) {
            bookingResources.forEach((resource, index) => {
                resources.push({
                    id: resource.id.toString(),
                    db_id: resource.id,
                    title: resource.name
                });
            });
        }

        function GetDbFormatedCalendarEvents() {

            var dbDatesData = [];

            window.calendar.getEvents().forEach(event => {

                var resources = event.getResources();
                var resource = resources.length > 0 ? resources[0] : null;
                var resourceId = resource ? resource.id : 1; // Use the first resource's ID or default to 1

                var db_id = resource && resource.extendedProps && resource.extendedProps.db_id ? resource.extendedProps.db_id : 1;

                // if event noCalculate is set then skip the calculation
                if (event.extendedProps.noCalculate) {
                    return;
                }


                var startDate = new Date(event.start);
                var endDate = event.end ? new Date(event.end) : null;

                var formattedEvent = {
                    id: event.id,
                    title: event.title,
                    resource_id: db_id,
                    date: startDate.toISOString().split('T')[0],
                    start_time: formatTime(startDate),
                    end_time: endDate ? formatTime(endDate) : null
                };

                dbDatesData.push(formattedEvent);

            });

            return dbDatesData;

        }


        function onCalendarEventChange(calendar) {

            calculateAndUpdateBookingOverview();

        }

        // ===============


        // console.log(
        //     "Resources",
        //     resources,
        //     [{
        //                 id: "1",
        //                 title: 'Workstation A'
        //             },
        //             {
        //                 id: "2",
        //                 title: 'Workstation B'
        //             },
        //             {
        //                 id: "3",
        //                 title: 'Workstation C'
        //             },
        //             {
        //                 id: "4",
        //                 title: 'Workstation D'
        //             }
        //         ]

        // );

        var bookingStatus = "<?php echo $booking ? $booking->status : "none"; ?>";

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var slotMinTime = '10:00:00';
            var slotMaxTime = '20:00:00';

            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'today',
                    center: 'prev,title,next',
                    right: 'resourceTimelineDay'
                },
                slotMinTime: slotMinTime,
                slotMaxTime: slotMaxTime,
                locale: 'en-GB', // British English, which uses the d-m-Y format
                titleFormat: {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                },
                slotLabelFormat: { // Format for the time labels on the table
                    hour: 'numeric',
                    omitZeroMinute: false,
                    meridiem: "short", // Use 24-hour time format
                    hour12: true
                },
                allDaySlot: false,
                //aspectRatio: 3.1,
                contentHeight: 240,
                initialView: 'resourceTimelineDay',
                editable: true, // enable drag and drop
                selectable: true, // enable selecting time slots
                eventOverlap: false,
                eventResizableFromStart: false, // Disable resizing from the start of the event
                eventDurationEditable: false, // Disable resizing of event duration
                datesSet: function (info) {
                    adjustTimeSlotsForToday(info);
                },
                // On date changes then then hide the past time
                // Event that triggers after the view is initially rendered or updated
                viewDidMount: function (info) {
                    adjustTimeSlotsForToday(info);
                    adjustNavigationDates();
                },

                dateClick: function (info) {

                    // If bookingStatus is cancelled then do not allow to add new event
                    if (bookingStatus == 'cancelled') {
                        return alert('You can not add new booking as the booking is already cancelled!');
                    }

                    // Check if a time slot was clicked (not an all-day slot)
                    if (info.allDay) {
                        alert('Please click on a specific time slot to add an event.');
                        return;
                    }

                    // Get current date and time
                    var currentDate = new Date();
                    var clickedDate = new Date(info.dateStr);

                    // Check if the clicked date is today or in the future
                    if (clickedDate < currentDate) {
                        alert('Please select future date and time!');
                        return;
                    }


                    // Get the duration from the #duration select selected option
                    var duration = $('#duration').val();

                    // convert to minutes
                    var durationInMinutes = parseFloat(duration) * 60;

                    // Restrict end time to a maximum of 7 PM
                    var maxEndTime = new Date(info.dateStr);
                    maxEndTime.setHours(20, 0, 0, 0); // Set to 7:00 PM

                    var endTime = new Date(new Date(info.dateStr).getTime() + durationInMinutes * 60000);

                    // Check if the end time is after 7 PM
                    if (endTime > maxEndTime) {
                        return alert('Please select the valid time!');
                    }

                    // Get all events
                    var events = calendar.getEvents();

                    // loop through the events and remove isNew event
                    events.forEach(event => {
                        if (event.extendedProps.isNew || event.id == '0') {
                        // if (event.extendedProps.isNew || event.extendedProps.isCurrent) {
                            event.remove();
                        }
                    });

                    // // Check for overlapping events
                    events = calendar.getEvents();
                    var isOverlap = events.some(function (event) {

                        var eventStart = new Date(event.start);
                        var eventEnd = new Date(event.end || event.start);

                        var resources = event.getResources();
                        var resource = resources.length > 0 ? resources[0] : null;
                        var resourceId = resource ? resource.id : 1; // Use the first resource's ID or default to 1


                        // Check if the clicked time slot overlaps with an existing event
                        return resourceId === info.resource.id &&
                            ((clickedDate >= eventStart && clickedDate < eventEnd) ||
                                (endTime > eventStart && endTime <= eventEnd) ||
                                (clickedDate <= eventStart && endTime >= eventEnd));
                    });

                    if (isOverlap) {
                        alert('The selected time slot overlaps with an existing event.');
                        return;
                    }

                    var title = `New`;
                    if (title) {
                        calendar.addEvent({
                            className: ['propose'],
                            id: 'new-' + Math.random().toString(36).substr(2, 9),
                            title: title,
                            start: info.dateStr, // use the clicked time slot
                            // plus 30 min to the start time
                            end: endTime,
                            allDay: false, // ensure it is a time-based event
                            resourceId: info.resource.id, // set the clicked resource
                            isNew: true
                        });
                    }
                },
                resources: resources,
                events: datesEvents,
                eventClick: function (info) {

                    // Prevent the non editable events from being edited
                    if (info.event.extendedProps.isEditable === false) {
                        return;
                    }

                    // If bookingStatus is cancelled then do not allow to do any action
                    if (bookingStatus == 'cancelled') {
                        return alert('You can not add new booking as the booking is already cancelled!');
                    }

                    if (confirm('Are you sure you want to remove this event?')) {
                        info.event.remove(); // Remove the event from FullCalendar
                    }
                },
                eventAdd: function (info) {
                    onCalendarEventChange(calendar);
                },
                eventChange: function (info) {
                    onCalendarEventChange(calendar);
                },
                eventRemove: function (info) {
                    onCalendarEventChange(calendar);
                },
                eventAllow: function (dropLocation, draggedEvent) {
                    return draggedEvent.extendedProps.isEditable !== false;
                },
                eventAllow: function (dropLocation, draggedEvent) {
                    // Check if the new date is in the future
                    var currentDate = new Date();
                    var newStartDate = new Date(dropLocation.start);

                    // Allow only if the new start date is today or in the future
                    return newStartDate >= currentDate;
                }

            });

            // function to adjust the navigation dats
            function adjustNavigationDates() {


                let prevButton = document.querySelector('.fc-prev-button');
                let nextButton = document.querySelector('.fc-next-button');

                // On prev button click
                prevButton.addEventListener('click', function () {

                    // get current date of the calendar
                    let currentCalenderDate = calendar.getDate();

                    // Add 1 day to the current date
                    currentCalenderDate.setDate(currentCalenderDate.getDate() + 1);

                    // check if there is any event the past date than currentCalenderDate then set that event date
                    let events = calendar.getEvents();
                    for (let i = 0; i < events.length; i++) {
                        let event = events[i];
                        let eventDate = new Date(event.start);

                    }


                    // if the current date is less than the current date then do nothing as we will allow to move backword to the past date
                    if (currentCalenderDate.getTime() >= new Date().getTime()) {
                        return;
                    }


                    // keep the current date
                    calendar.gotoDate(currentCalenderDate);


                });

                // on next button click
                // on next button click
                nextButton.addEventListener('click', function () {
                    // get the current date of the calendar
                    let currentCalenderDate = calendar.getDate();

                    // Add 1 day to the current date
                    currentCalenderDate.setDate(currentCalenderDate.getDate() + 1);

                    // on next button click
                    nextButton.addEventListener('click', function () {
                        // get the current date of the calendar
                        let currentCalenderDate = calendar.getDate();

                        // Add 1 day to the current date
                        currentCalenderDate.setDate(currentCalenderDate.getDate() + 1);

                        // Get all events from the calendar
                        let events = calendar.getEvents();

                        // Sort the events by start date in ascending order (earliest first)
                        events.sort(function (a, b) {
                            return new Date(a.start).getTime() - new Date(b.start).getTime();
                        });

                        // Loop through the sorted events
                        for (let i = 0; i < events.length; i++) {
                            let event = events[i];
                            let eventDate = new Date(event.start);

                            // If the event date is after the current calendar date, navigate to that event's date

                        }

                        // Get the current date
                        let nowDate = new Date();

                        // If the current calendar date is greater than or equal to today's date, do nothing
                        if (currentCalenderDate.getTime() >= nowDate.getTime()) {
                            return;
                        }

                        // If no events are found after the current calendar date, navigate to today's date
                        calendar.gotoDate(nowDate);
                    });

                });

            }

            // Function to adjust time slots
            function adjustTimeSlotsForToday(info) {
                let today = new Date();
                today.setHours(0, 0, 0, 0); // Reset time to midnight for accurate comparison

                let viewStartDate = new Date(info.view.currentStart);
                viewStartDate.setHours(0, 0, 0, 0); // Reset time to midnight for accurate comparison

                if (viewStartDate.getTime() === today.getTime()) {
                    // Get the current time
                    let currentTime = new Date();

                    let hours = currentTime.getHours();
                    let minutes = 0;

                    // add to one hour to hide the current hour
                    hours += 1;

                    // if hourse less than 10
                    if (hours < 10) {
                        hours = 10;
                    }
                    // // round up tht hours to the next 1 hour
                    // if (minutes > 1) {
                    //     hours += 1;
                    //     minutes = 0;
                    // }

                    // if there is any event in the past time then set the slotMinTime to that event start time
                    let isEventInCurrentTime = false;
                    let events = calendar.getEvents();
                    events.forEach(event => {
                        let eventStart = new Date(event.start);
                        // Check if the event is on the same day as currentTime
                        if (
                            eventStart.getFullYear() === currentTime.getFullYear() &&
                            eventStart.getMonth() === currentTime.getMonth() &&
                            eventStart.getDate() === currentTime.getDate() &&
                            eventStart.getTime() <= currentTime.getTime()
                        ) {
                            isEventInCurrentTime = true;
                            currentTime = eventStart;
                        }
                    });

                    // if it have the event then don't do nothing
                    if (isEventInCurrentTime) return;


                    // Format the current time as `HH:MM:SS`
                    let formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:00`;

                    // Set the slotMinTime to the current time to prevent selection of past times
                    calendar.setOption('slotMinTime', formattedTime);
                } else {
                    // Reset to the default times if not today
                    calendar.setOption('slotMinTime', '10:00:00');
                    calendar.setOption('slotMaxTime', '20:00:00');
                }
            }

            calendar.render();

            window.calendar = calendar;

            calculateAndUpdateBookingOverview();

            // Set the initial date to the first event date
            try {
                var bookingDatesArray = <?php echo json_encode($booking_dates); ?>;

                if (bookingDatesArray.length > 0) {

                    // bookingDatesArray sort the object by the .date
                    bookingDatesArray.sort(function (a, b) {
                        return new Date(a.date) - new Date(b.date);
                    });

                    var firstEventDate = new Date(bookingDatesArray[0].date);
                    calendar.gotoDate(firstEventDate);
                }
            } catch (error) {
            }


            // On duration change remove the calendar events which isNew and isCurrent
            $('#duration').change(function () {
                var events = calendar.getEvents();
                events.forEach(event => {
                    if (event.extendedProps.isNew || event.extendedProps.isCurrent) {
                        event.remove();
                    }
                });
            });


            updateEevents(true);

        });


    </script>



    <script>
        // On status changes

        $('#order_status').change(function () {
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

            // If the status is cancelled then show the modal
            if (status == "cancelled") {
                $('#cancelBookingModal').modal('show');
                return;
            }

            // Ask for confirmation using app.confirm
            App.confirm('Are you sure you want to change the status?', 'Are you sure you want to change the status?', function (confirmed) {
                if (confirmed) {
                    App.loading(true);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: 'json',
                        success: function (res) {
                            App.loading(false);
                            if (res['status'].toString() == "1") {
                                App.alert(res['message'], 'Success');

                                // after 1 second reload the page
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);

                            } else {
                                App.alert(res['message'], 'Oops!');
                            }
                        },
                        error: function (e) {
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


        // on is_refund change if is_refund is checked then show the file input
        $('#is_refund').change(function () {
            if ($(this).is(':checked')) {
                $('#attachFile').show();
            } else {
                $('#attachFile').hide();
            }
        });

        // on cancelBookingSubmit
        $('#cancelBookingForm').submit(function (e) {
            e.preventDefault();
            var order_id = '{{$haveOrder ? $booking->id : ""}}';
            var url = "{{ route(route_name_admin_vendor($type, 'artist-booking.cancel'), ['type'=> $type, 'user_id'=> $user_id]) }}";

            // create form data
            var formData = new FormData(this);

            // append the order id
            formData.append('orderId', order_id);

            // append the token
            formData.append('_token', '{{ csrf_token() }}');

            // if is_refund is checked then check the file is selected or not
            if ($('#is_refund').is(':checked')) {
                if (!$('#file').val()) {
                    App.alert('Please select the file', 'Oops!');
                    return;
                }
            } else {

                // as the refund is not made so remove the file

                // remove the file from the form data
                formData.delete('file');
            }

            // append the is_refund value based on the checkbox
            formData.append('is_refund', $('#is_refund').is(':checked') ? 1 : 0);


            // disable the submit button and change the text
            $('#cancelBookingSubmit').attr('disabled', 'disabled').text('Submitting...');

            // function to reset the button
            function resetButton(clearRemarks = true, closeModal = true) {
                $('#cancelBookingSubmit').removeAttr('disabled').text('Submit');

                // clear the remarks
                if (clearRemarks) $('#cancel_remarks').val('');

                // close the modal
                if (closeModal) $('#cancelBookingModal').modal('hide');
            }

            App.loading(true);
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                dataType: 'json',
                success: function (res) {

                    if (res['status'].toString() == "1") {
                        App.alert(res['message'], 'Success');

                        resetButton();

                        // Reload the page
                        setTimeout(function () {
                            location.reload();
                        }, 1000);


                    } else {
                        resetButton(false, false);
                        App.alert(res['message'], 'Oops!');
                    }
                },
                error: function (e) {
                    resetButton(false, false);
                    App.alert(e.responseText, 'Oops!');
                }
            });

        });

        // On transaction status changes
        $('#transaction_status').change(function () {
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
            App.confirm('Are you sure you want to change the transaction status?', 'Are you sure you want to change the transaction status?', function (confirmed) {
                if (confirmed) {
                    App.loading(true);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: 'json',
                        success: function (res) {
                            App.loading(false);
                            if (res['status'].toString() == "1") {
                                App.alert(res['message'], 'Success');
                            } else {
                                App.alert(res['message'], 'Oops!');
                            }
                        },
                        error: function (e) {
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
        $(document).ready(function () {
            $('.select2').select2();
            $('.ret_applicable').change(function () {
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
        $('body').on('submit', '#admin-form', function (e) {

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

            // ---------- Loop through the dates and append to the form data ------------


            var dbDatesData = GetDbFormatedCalendarEvents(); // Get the formatted calendar events

            // Append each event object as an array item in the form data
            dbDatesData.forEach((event, index) => {
                formData.append(`booking_dates[${event.id}][title]`, event.title);
                formData.append(`booking_dates[${event.id}][resource_id]`, event.resource_id);
                formData.append(`booking_dates[${event.id}][date]`, event.date);
                formData.append(`booking_dates[${event.id}][start_time]`, event.start_time);
                formData.append(`booking_dates[${event.id}][end_time]`, event.end_time);
            });

            // ------------------------


            App.loading(true);
            $form.find('button[type="submit"]')
                .text('Saving')
                .attr('disabled', true);


            // if have any media in formData.newMedias or formData. to upload then show the progress bar
            if (Object.keys(allFiles).length > 0) {

                $mediaProgress.show();

                // ------ To remove the medias entries ----
                var keysToRemove = [];
                formData.forEach(function (value, key) {
                    if (key === 'newMedias[]') {
                        keysToRemove.push(key);
                    }
                });

                keysToRemove.forEach(function (key) {
                    formData.delete(key);
                });

                // ---------------------------------------

                // loop through the allFiles and append to the form data
                for (var key in allFiles) {
                    formData.append(`newMedias[]`, allFiles[key]);
                }


            }


            //  get the disabled field reference_no value and add to form
            var reference_no = $('#reference_no').val();
            formData.append('reference_no', reference_no);


            const reSetProgress = () => {
                $mediaProgress.hide();
                $('.progress-bar').css('width', '0%').text('0%');
            };

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
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();

                    // Upload progress
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = Math.round((evt.loaded / evt.total) * 100);

                            // Update progress bar here
                            $('.progress-bar').css('width', percentComplete + '%').text(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function (res) {
                    App.loading(false);
                    reSetProgress();

                    if (res['status'] == 0) {
                        if (typeof res['errors'] !== 'undefined') {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(res['errors'], function (e_field, e_message) {
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
                            error_def.done(function () {

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

                            // if it's have the errors array and have the element booking_dates
                            if (res['errors'] && res['errors']['booking_dates']) {
                                App.alert('Please select the date and time for the booking', 'Oops!');
                            } else {

                                App.alert(res['message'], 'Oops!');
                            }
                        }
                    } else {
                        App.alert(res['message'], 'Success!');
                        setTimeout(function () {

                            //If id is provided then reload the page else redirect to the list page
                            if ('{{ $id }}') {
                                // go back to the list page using window go back
                                window.location.href = "{{ route(route_name_admin_vendor($type, 'artist-booking.index'), ['type'=> $type, 'user_id'=> $type == 'admin' ? 'all' : $user_id]) }}";
                                //window.location.href = "{{ route(route_name_admin_vendor($type, 'artist-booking.index'), ['type'=> $type, 'user_id'=> $user_id]) }}";
                            } else {
                                window.location.href = "{{ route(route_name_admin_vendor($type, 'artist-booking.index'), ['type'=> $type, 'user_id'=> $user_id]) }}";
                            }

                        }, 1500);

                    }

                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                },
                error: function (e) {
                    App.loading(false);
                    reSetProgress();
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

        document.addEventListener("DOMContentLoaded", function () {
            const imagePreviews = document.getElementById('image-previews');
            const inputImages = document.querySelector('input[name="newMedias[]"]');

            inputImages.addEventListener('change', function () {

                const maxIamges = <?php echo $maxImgsAllowed; ?>;
                const PrevImages = imagePreviews.querySelectorAll('img').length;

                if (maxIamges - PrevImages <= 0) return App.alert(`You can only upload ${maxIamges} medias`, 'Oops!');

                const files = Array.from(this.files).slice(0, 5 - PrevImages); // Max 5 images

                files.forEach(file => {
                    const reader = new FileReader();

                    // Generate UID
                    const uid = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);

                    allFiles[uid] = file;

                    reader.onload = function (e) {
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

        $(document).on('click', '.del-product-img:not(.local)', function (e) {
            var rid = $(this).data('rid');
            var orderId = "<?php echo $id; ?>";
            var _this = $(this);
            App.confirm('Confirm Delete', 'You will not be able to undo if you click the yes!', function () {
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
                    success: function (res) {
                        if (res['status'] == 1) {
                            // Add sucess message alert
                            App.alert(res['message'], 'Success!');
                            _this.closest('.img-preview-box').remove();
                        } else {
                            App.alert(res['message'] || 'Unable to delete the image.', 'Oops!');
                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        App.alert(errorMessage || 'An error occurred while deleting the image.', 'Oops!');
                    }
                });
            });
        });

        // ----------------------------------------------------


        // --------- On local del-product-img delete click then delete the image ----------

        $(document).on('click', '.del-product-img.local', function (e) {

            var rid = $(this).data('rid');
            delete allFiles[rid];
            $(this).closest('.b_img_div').remove();
        });


        // --------------------------------------------------------------------------
    </script>


    <script>
        // Find customer management

        // On Find customer by radio option change if the option is emaail then show the email div and hide the phone div and vice versa
        $('input[name="option"]').change(function () {

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
                success: function (res) {
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
                error: function (e) {

                    resetButton();

                    App.alert(e.responseText, 'Oops!');
                }
            });

        }

        // On email field and phone number press enter then call the doSearch function
        $('#find_cust_email, #find_cust_phone').keypress(function (e) {
            if (e.which == 13) {
                doSearch();

                e.preventDefault();
            }
        });

        // On search button click if the email is provided then validate if the email is valid or not else validate the phone number
        $('#search').click(function () {

            doSearch()

        });

        // On remove user button click hide the customer details and show the search form
        $('#remove_user').click(function () {
            $('.search_form').show();
            $('.customer_found').hide();
        });
    </script>



    <script src="{{ asset('') }}admin-assets/full-calendar.min.js"></script>
    <!-- <script src='https://unpkg.com/@fullcalendar/interaction/main.js'></script> -->



    <style>
        .fc-header-toolbar .fc-toolbar-chunk > div {
            display: flex;
            justify-content: center;
        }

        .fc-header-toolbar .fc-toolbar-title {
            margin: 0px 10px;
            line-height: 1.75em;
        }

        .fc-h-event .fc-event-title {
            font-size: 13px;
        }

        .fc-h-event .fc-event-main-frame {
            align-items: center;
            justify-content: center;
        }

        .fc-h-event .fc-event-title-container {
            text-align: center;
        }

        .fc-timeline-event {
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-selection--single {
            border-radius: 10px !important;
        }

        .indicators {
            display: flex;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .indicators li {
            list-style: none;
            display: flex;
            align-items: center;
            margin-right: 12px;
            font-size: 14px;
        }

        .clr {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 10px;
            border-radius: 4px;
        }

        .clr.pending {
            background-color: #00A1FE;
            border: 1px solid #00A1FE;
        }

        .clr.paid {
            background-color: #1CB000;
            border: 1px solid #1CB000;
        }

        .clr.selct {
            background-color: #F8B900;
            border: 1px solid #F8B900;
        }

        .clr.propose {
            background-color: #640E46;
            border: 1px solid #640E46;
        }

        .fc-h-event.pending {
            border-color: #00A1FE;
            background-color: #00A1FE;
        }

        .fc-h-event.paid, .fc-h-event.confirmed, .fc-h-event.completed {
            border-color: #1CB000;
            background-color: #1CB000;
        }

        .fc-h-event.selected {
            border-color: #F8B900;
            background-color: #F8B900;
        }

        .fc-h-event.cancelled {
            border-color: #e7515a;
            background-color: #e7515a;
        }

        .fc-h-event.propose {
            border-color: #640E46;
            background-color: #640E46;
        }

        #calendar.loading {
            pointer-events: none;
            opacity: 0.4;
        }

        .fc-h-event .fc-event-title {
            font-size: 13px;
        }
    </style>

@stop
