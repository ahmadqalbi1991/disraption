@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/assets/css/booking.css">
@stop




@section('content')
<form method="post" id="admin-form" action="{{ route(route_name_admin_vendor($type, 'yacht_booking.save'), ['user_id' => $user_id, 'type' => $type]) }}" enctype="multipart/form-data" data-parsley-validate="true">
    <input type="hidden" name="id" id="cid" value="{{ $id }}">
    <input type="hidden" name="vendor_user_id" id="cid" value="{{ $user_id }}">
    <input type="hidden" name="yacht_id" value="{{ $yacht_id }}">
    @csrf()


    <div class="card mb-5">
        <h5 class="card-header mb-0">Booking Details</h5>
        <div class="card-body">
            <div class="">

                <div class="row">


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer</label>
                            <select class="form-control jqv-input product_catd select2" name="customer_id" data-role="select2" data-placeholder="Select Customer" data-allow-clear="true" required data-parsley-required-message="Select Customer">
                                @foreach($customers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="form-group col-md-12">
                        <label class="control-label">Pickup point, Drag the marker<b class="text-danger">*</b></label>
                        <input type="text" name="txt_pickup_point" id="txt_pickup_point" class="form-control autocomplete" placeholder="Location" required data-parsley-required-message="Enter Location">
                        <input type="hidden" id="pickup_point" name="pickup_point">
                    </div>
                    <div class="form-group col-md-12">
                        <div id="map_canvas" style="height: 200px;width:100%;"></div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Date <span style="color:red;">*<span></span></span></label>
                            <input type="text" id="date" name="date" class="form-control flatpicker-future" required value="{{ empty($date) ? '' : date('Y-m-d', strtotime($date))}}" data-parsley-required-message="Enter Date">
                        </div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="form-group">
                            <label>Start Time<b class="text-danger">*</b></label>
                            <input type="text" name="start_time" class="form-control flatpicker-time" required value="">
                        </div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="form-group">
                            <label>End Time<b class="text-danger">*</b></label>
                            <input type="text" name="end_time" class="form-control flatpicker-time" required value="">
                        </div>
                    </div>


                    <div class="col-6 mb-3">
                        <div class="form-group">
                            <label>Number of Guests (Adult)<b class="text-danger">*</b></label>
                            <select class="form-control" name="guests_adults" autocomplete="off" required data-parsley-required-message="">
                                @foreach ([0,1,2,3,4,5,6,7,8,9,10] as $cnt)
                                <option @if((int)$adults==$cnt) selected @endif value="{{ $cnt }}">{{ $cnt }}</option>
                                @endforeach
                            </select>
                            </select>
                            <span id="mob_err"></span>
                        </div>
                    </div>


                    <div class="col-6 mb-3">
                        <div class="form-group">
                            <label>Number of Guests (Children)<b class="text-danger">*</b></label>
                            <select class="form-control" name="guests_children" autocomplete="off" required data-parsley-required-message="">
                                @foreach ([0,1,2,3,4,5,6,7,8,9,10] as $cnt)
                                <option @if((int)$child==$cnt) selected @endif value="{{ $cnt }}">{{ $cnt }}</option>
                                @endforeach
                            </select>
                            </select>
                            <span id="mob_err"></span>
                        </div>
                    </div>


                </div>

            </div>

        </div>
    </div>




    <div class="card mb-5">
        <h5 class="card-header mb-0">Booking</h5>
        <div class="card-body">
            <div class="">


                <div class="row">

                    <div class="col-12 mb-4">
                        <h3>Booking</h3>
                    </div>


                    <div class="col-12 mb-5">
                        <div class="form-group">
                            <label class="d-block"><strong>I am booking for</strong></label>
                            <span class="mr-3"> <input type="radio" checked name="booking_for" value="myself" required> Myself</span>
                            <span><input type="radio" name="booking_for" value="other" required> Other</span>
                        </div>
                    </div>


                    <div class="col-12 cnt_for_other" style="display: none;">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Title<b class="text-danger">*</b></label>
                                    <select name="cstmr_title" class="form-control" required>
                                        <option value="mr">Mr</option>
                                        <option value="mrs">Mrs</option>
                                        <option value="miss">Miss</option>
                                        <option value="ms">Ms</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>First Name<b class="text-danger">*</b></label>
                                    <input type="text" name="cstmr_first_name" class="form-control" required data-parsley-required-message="Enter first Name" value="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Last Name<b class="text-danger">*</b></label>
                                    <input type="text" name="cstmr_first_name" class="form-control" required data-parsley-required-message="Enter first Name" value="">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email Address<b class="text-danger">*</b></label>
                                    <input type="email" name="cstmr_email" class="form-control" required data-parsley-required-message="Enter Email Address" value="">
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone No<b class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <select class="form-control jqv-input product_catd select2" name="cstmr_dial_code" data-role="select2" data-placeholder="Select Code" data-allow-clear="true" required data-parsley-required-message="Select Code">
                                        @foreach ($countries as $cnt)
                                        <option value="{{ $cnt->dial_code }}">+{{ $cnt->dial_code }}</option>
                                        @endforeach;
                                    </select>
                                        </div>
                                        <input autocomplete="off" type="number" class="form-control frmt_number" name="cstmr_phone" value="" data-jqv-required="true" required data-parsley-required-message="Enter Phone number" data-parsley-type="digits" data-parsley-minlength="5" data-parsley-maxlength="12" data-parsley-trigger="keyup">
                                    </div>
                                    <span id="mob_err"></span>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="col-12 mb-5">
                        <div class="form-group">
                            <label class="d-block"><strong>Send it as gift email and whatsapp</strong></label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="send_as_gift" value="0">
                                <label class="form-check-label">Yes</label>
                            </div>
                        </div>


                        <div class="col-md-12 mt-5">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Create Booking</button>
                            </div>
                        </div>

                    </div>
                </div>


            </div>

        </div>

    </div>
    </div>

</form>

@stop
@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD12qKppGVDn6Y5QPwpO5liu8326w9jNwY&v=weekly&libraries=places"> </script>
<script>
    var currentLat = 25.204819;
    var currentLong = 55.270931;
    $("#pickup_point").val(currentLat + "," + currentLong);
    currentlocation = {
        "lat": currentLat,
        "lng": currentLong,
    };
    initMap();
    initAutocomplete();

    function initMap() {
        map2 = new google.maps.Map(document.getElementById('map_canvas'), {
            center: {
                lat: currentlocation.lat,
                lng: currentlocation.lng
            },
            zoom: 14,
            gestureHandling: 'greedy',
            mapTypeControl: false,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            streetViewControlOptions: {
                position: google.maps.ControlPosition.LEFT_BOTTOM
            },
        });

        geocoder = new google.maps.Geocoder();

        // geocoder2 = new google.maps.Geocoder;
        usermarker = new google.maps.Marker({
            position: {
                lat: currentlocation.lat,
                lng: currentlocation.lng
            },
            map: map2,
            draggable: true,

            animation: google.maps.Animation.BOUNCE
        });


        //map click
        google.maps.event.addListener(map2, 'click', function(event) {
            updatepostition(event.latLng, "movemarker");
            //drag end event
            usermarker.addListener('dragend', function(event) {
                // alert();
                updatepostition(event.latLng, "movemarker");

            });
        });

        //drag end event
        usermarker.addListener('dragend', function(event) {
            // alert();
            updatepostition(event.latLng);

        });
    }
    updatepostition = function(position, movemarker) {
        geocodePosition(position);
        usermarker.setPosition(position);
        map2.panTo(position);
        map2.setZoom(15);
        let createLatLong = position.lat() + "," + position.lng();
        console.log("Address Lat/long=" + createLatLong);
        $("#pickup_point").val(createLatLong);
    }

    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function(responses) {
            if (responses && responses.length > 0) {
                usermarker.formatted_address = responses[0].formatted_address;
            } else {
                usermarker.formatted_address = 'Cannot determine address at this location.';
            }
            $('#txt_pickup_point').val(usermarker.formatted_address);
        });
    }

    function initAutocomplete() {
        // Create the search box and link it to the UI element.
        var input2 = document.getElementById('txt_pickup_point');
        var searchBox2 = new google.maps.places.SearchBox(input2);

        map2.addListener('bounds_changed', function() {
            searchBox2.setBounds(map2.getBounds());
        });

        searchBox2.addListener('places_changed', function() {
            var places2 = searchBox2.getPlaces();

            if (places2.length == 0) {
                return;
            }
            $('#txt_pickup_point').val(input2.value)

            var bounds2 = new google.maps.LatLngBounds();
            places2.forEach(function(place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }

                updatepostition(place.geometry.location);

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds2.union(place.geometry.viewport);
                } else {
                    bounds2.extend(place.geometry.location);
                }
            });
            map2.fitBounds(bounds2);
        });
    }
    updatepostition = function(position, movemarker) {
        console.log(position);
        geocodePosition(position);
        usermarker.setPosition(position);
        map2.panTo(position);
        map2.setZoom(15);
        let createLatLong = position.lat() + "," + position.lng();
        // console.log("Address Lat/long="+createLatLong);
        $("#pickup_point").val(createLatLong);
    }
</script>

<script>
    $(".flatpicker-time").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });
</script>

<script>
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


    $(".flatpicker-future").flatpickr({
        enableTime: false,
        dateFormat: "Y-m-d",
        minDate: "today",
    });


    $(document).ready(function() {
        $('.select2').select2();
    });



    // ---------- If booking_for is other then show the customer details fields ------

    // If the booking_for is other then add all input fields required and show the cnt_for_other else make all input fields not required, disabled and hide the cnt_for_other
    $('input[name="booking_for"]').change(function() {
        if ($(this).val() == 'other') {
            $('.cnt_for_other').show("slow");
            $('.cnt_for_other input, .cnt_for_other select').prop('required', true).prop('disabled', false);
        } else {
            $('.cnt_for_other').hide("slow");
            $('.cnt_for_other input, .cnt_for_other select').prop('required', false).prop('disabled', true);
        }
    });

    // Triger change
    $('input[name="booking_for"]:checked').trigger('change');

    // --------------------------------------------------------------------------------


    // ----------- On products tick box change update the input field value if checked then 1 else 0 ------------
    var products_changed = false;
    $('body').off('change', '.tick_prod');
    $('body').on('change', '.tick_prod', function(e) {

        products_changed = true;

        var _this = $(this);
        var checked = _this.prop('checked');
        var actl_val = _this.parents('.wrap').find('.actl_val');
        if (checked) {
            actl_val.val("1");
        } else {
            actl_val.val("0");
        }
    });

    // Trigger change
    $('.tick_prod').trigger('change');

    // ------------------------------------------------------------------------------------------------------------


    $('body').off('submit', '#admin-form');
    $('body').on('submit', '#admin-form', function(e) {
        e.preventDefault();


        // If end time is less than start time then show error
        var start_time = $('input[name="start_time"]').val();
        var end_time = $('input[name="end_time"]').val();
        if (start_time && end_time) {
            if (start_time >= end_time) {
                App.alert('End time should be greater than start time', 'Oops!');
                return false;
            }
        }

        // If adults and guest value is 0 then show error
        var adults = $('select[name="guests_adults"]').val();
        var children = $('select[name="guests_children"]').val();
        if (adults == 0 && children == 0) {
            App.alert('Please select atleast 1 adult or 1 child', 'Oops!');
            return false;
        }

        var $form = $(this);
        var formData = new FormData(this);
        $(".invalid-feedback").remove();

        App.loading(true);
        $form.find('button[type="submit"]')
            .text('Saving')
            .attr('disabled', true);

        var parent_tree = $('option:selected', "#parent_id").attr('data-tree');
        formData.append("parent_tree", parent_tree);

        // If send as gift is checked then 1 else 0
        var send_as_gift = $('input[name="send_as_gift"]').prop('checked') ? 1 : 0;
        formData.append("send_as_gift", send_as_gift);


        // @todo, remove the below two lines after implementing the google map api
        formData.set('pickup_point', '234235, 34365645');
        formData.set('txt_pickup_point', 'Al Safa St Downtown Dubai - Dubai United Arab Emirates');

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
                    // If errors not defined or if it's array and it's empty
                    if (typeof res['errors'] !== 'undefined' && res['errors'] !== null && res['errors'].length > 0) {
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
                    } else {
                        var m = res['message'] ||
                            'Unable to save category. Please try again later.';
                        App.alert(m, 'Oops!');
                    }
                } else {
                    App.alert(res['message'], 'Success!');
                    setTimeout(function() {

                        // If id is provided then reload the page else redirect to the list page
                        if ('{{ $id }}') {
                            location.reload();
                        } else {
                            window.location.href = "{{ route(route_name_admin_vendor($type, 'yacht_booking.create'), ['user_id' => $user_id, 'type' => $type, 'id' => $yacht_id]) }}";
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
<style>
    .del-product-img {
        cursor: pointer;
        color: red;
        font-size: 12px;
        margin-top: 5px;
        font-size: large;
        transition: all 0.3s;
    }

    .del-product-img:hover {
        color: black
    }

    .del-product-img svg {
        margin-right: 5px;
    }
</style>
@stop