@extends('admin.template.layout')
@section('header')

@stop
@section('content')
<form method="post" id="admin-form" action="{{ route('admin.customers.save') }}" enctype="multipart/form-data" data-parsley-validate="true">
    <div class="card mb-5">
        <div class="card-body">
            <div class="">
                <input type="hidden" name="id" id="cid" value="{{ $id }}">
                @csrf()

                <div class="row">

                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label>Is Social Account</label>
                            <div class="form-check">
                                <input id="is_social" type="checkbox" class="form-check-input" name="is_social" value="1" <?php echo $is_social == 1 ? 'checked' : ''; ?> <?php echo $id ? 'disabled' : ''; ?>>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <span id="name_label">First Name</span><b class="text-danger">*</b>
                            </label>
                            <input type="text" name="first_name" class="form-control" required data-parsley-required-message="Enter Finst Name" value="{{ $first_name }}">
                        </div>
                    </div>

                    <div class="col-md-6 cnd_indvl">
                        <div class="form-group">
                            <label>Last Name<b class="text-danger">*</b></label>
                            <input type="text" name="last_name" class="form-control" required data-parsley-required-message="Enter Last Name" value="{{ $last_name }}">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email Address<b class="text-danger">*</b></label>
                            <input type="email" name="email" class="form-control" required data-parsley-required-message="Enter Email Address" value="{{ $email }}" @if($id) disabled @endif>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone No<b class="text-danger">*</b></label>
                            <select class="form-control jqv-input product_catd select2" name="dial_code" data-role="select2" data-placeholder="Select dial code" data-allow-clear="true" required data-parsley-required-message="Select Code">
                            @foreach ($countries as $cnt)
                                        <option @if($id) @if($dial_code==$cnt->dial_code) selected @endif
                                            @endif value="{{ $cnt->dial_code }}">+{{ $cnt->dial_code }}</option>
                                        @endforeach;
                            </select>
                        </div>
                    </div>



                    <div class="col-md-6 cnd_indvl" id="gender_wrap">
                        <div class="form-group">
                            <label>Gender<b class="text-danger">*</b></label>
                            <select name="gender" class="form-control" required data-parsley-required-message="Select Gender">
                                <option value="male" {{ $gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $gender == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $gender == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-6 cnd_indvl" id="date_of_birth_wrap">
                        <div class="form-group">
                            <label>Date Of Birth <span style="color:red;">*<span></span></span></label>
                            <input type="text" class="dob form-control flatpickr-input w-100" data-date-format="dd-mm-yyyy" name="date_of_birth" value="{{ empty($date_of_birth) ? '' : date('Y-m-d', strtotime($date_of_birth))}}" required data-max-date='today' data-parsley-required-message="Enter Date of Birth">
                        </div>
                    </div>



                    <div class="col-md-6" id="pass_W">
                        <div class="form-group">
                            <label>Password </label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="password" name="password" data-jqv-maxlength="50" value="" data-parsley-minlength="8" autocomplete="off" data-parsley-errors-container="#p1_err" autocomplete="new-password">
                                <div class="input-group-append" style="cursor: pointer">
                                    <span class="input-group-text" onclick="password_show_hide();">
                                        <i class="fas fa-eye d-none" id="show_eye"></i>
                                        <i class="fas fa-eye-slash" id="hide_eye"></i>
                                    </span>
                                </div>
                            </div>
                            <span id="p1_err"></span>
                        </div>

                    </div>


                    <div class="col-md-6" id="cnfrm_pass_W">
                        <div class="form-group">
                            <label>Confirm Password </label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" name="confirm_password" data-jqv-maxlength="50" value="" data-parsley-minlength="8" data-parsley-equalto="#password" autocomplete="off" data-parsley-required-message="Please re-enter your new password." data-parsley-required-if="#password" id="password2" data-parsley-errors-container="#p2_err" autocomplete="new-password">
                                <div class="input-group-append" style="cursor: pointer">
                                    <span class="input-group-text" onclick="password_show_hide2();">
                                        <i class="fas fa-eye d-none" id="show_eye2"></i>
                                        <i class="fas fa-eye-slash" id="hide_eye2"></i>
                                    </span>
                                </div>
                            </div>
                            <span id="p2_err"></span>
                        </div>

                    </div>


                    <div class="form-group col-md-12">
                        <label class="control-label">Enter the location or Drag the marker<b class="text-danger">*</b></label>
                        <input type="text" name="location_name" id="txt_location" class="form-control autocomplete" placeholder="Location" required data-parsley-required-message="Enter Location" @if($location_name) value="{{$location_name}}" @endif>
                        <input type="hidden" id="location" name="location">
                    </div>
                    <div class="form-group col-md-12">
                        <div id="map_canvas" style="height: 200px;width:100%;"></div>
                    </div>


                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>


                </div>

            </div>

            <div class="col-xs-12 col-sm-6">
            </div>
        </div>
    </div>


</form>
@stop
@section('script')


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD12qKppGVDn6Y5QPwpO5liu8326w9jNwY&v=weekly&libraries=places">
</script>
<script>
    var currentLat = <?php echo isset($location->lattitude) ? $location->lattitude : 25.204819 ?>;
    var currentLong = <?php echo isset($location->longitude) ? $location->longitude : 55.270931 ?>;
    $("#location").val(currentLat + "," + currentLong);
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
        $("#location").val(createLatLong);
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
            $('#txt_location').val(usermarker.formatted_address);
        });
    }

    function initAutocomplete() {
        // Create the search box and link it to the UI element.
        var input2 = document.getElementById('txt_location');
        var searchBox2 = new google.maps.places.SearchBox(input2);

        map2.addListener('bounds_changed', function() {
            searchBox2.setBounds(map2.getBounds());
        });

        searchBox2.addListener('places_changed', function() {
            var places2 = searchBox2.getPlaces();

            if (places2.length == 0) {
                return;
            }
            $('#txt_location').val(input2.value)

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
        $("#location").val(createLatLong);
    }
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



    // On is_company change then hide/show fields
    $(document).ready(function() {



        const disableFields = ($selector) => {
            $selector.find("select, input").each(function() {
                if ($(this).attr('required')) {
                    $(this).attr('temp-required', 'true');
                    $(this).removeAttr('required');
                }


                $(this).prop("disabled", true);
            });
        }

        const enableFields = ($selector) => {
            $selector.find("select, input").each(function() {
                if ($(this).attr('temp-required')) {
                    $(this).attr('required', 'true');
                    $(this).removeAttr('temp-required');
                }

                // if it dit not have temp-disabled attr then dsiable it
                $(this).prop("disabled", false);

                // if have the id then disable the username field
                if ($(this).attr('name') == 'username') {

                    // if the id is set then disable the username field
                    if (id) {
                        $(this).prop("disabled", true);
                    }
                }

            });
        }


        // On is_company change show/hide the company name field
        // on account_type radio input field change
        const $is_social = $(`#is_social`);
        $is_social.change(function() {

            $fields = $(`#cnfrm_pass_W, #pass_W`);

            if ($(this).is(':checked')) {

                $fields.each(function() {
                    $(this).hide();

                    // Disable the field
                    disableFields($(this))

                });



            } else {

                $fields.each(function() {
                    $(this).show();

                    // enable the field
                    enableFields($(this))

                });


            }

        });

        // Trigger the change event
        $is_social.trigger('change');


    });


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

        App.loading(true);
        $form.find('button[type="submit"]')
            .text('Saving')
            .attr('disabled', true);

        var parent_tree = $('option:selected', "#parent_id").attr('data-tree');
        formData.append("parent_tree", parent_tree);
        

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
                    setTimeout(function() {

                        // If id is provided then reload the page else redirect to the list page
                        if ('{{ $id }}') {
                            location.reload();
                        } else {
                            window.location.href = "{{ route('admin.customers.index') }}";
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