@extends($main_type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout')
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

    .img_cropper img {
        display: block;
        max-width: 100%;
    }
</style>
@stop
@section('content')
<form method="post" id="admin-form" action="{{ route(route_name_admin_vendor($main_type, 'artist.save'), ['isartist'=> $main_type === "vendor" ? true : false]) }}" enctype="multipart/form-data" data-parsley-validate="true">
    <div class="card mb-5">
        <div class="card-body">
            <div class="">
                <input type="hidden" name="id" id="cid" value="{{ $id }}">
                @csrf()

                <div class="row">


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <span id="name_label">First Name</span><b class="text-danger">*</b>
                            </label>
                            <input type="text" name="first_name" class="form-control" required data-parsley-required-message="Enter Frist Name" value="{{ $first_name }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <span id="name_label">Last Name</span><b class="text-danger">*</b>
                            </label>
                            <input type="text" name="last_name" class="form-control" required data-parsley-required-message="Enter Last Name" value="{{ $last_name }}">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email Address<b class="text-danger">*</b></label>
                            <input type="email" name="email" class="form-control" required data-parsley-required-message="Enter Email Address" value="{{ $email }}">
                        </div>
                    </div>

                    <div class="col-md-6 cnd_indvl">
                        <div class="form-group">
                            <label>Username<b class="text-danger">*</b></label>
                            <input type="text" name="username" class="form-control" required data-parsley-required-message="Enter Username" value="{{ $username }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group select2-form-group">
                            <label>Category<b class="text-danger">*</b></label>
                            <select class="form-control jqv-input product_catd select2" multiple name="category_id[]" data-role="select2" data-placeholder="Select Category" data-allow-clear="true" required data-parsley-required-message="Select Category">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{in_array($category->id,$selectedcat) ? 'selected' : ''}}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control jqv-input product_catd select2" name="type" data-role="select2" data-placeholder="Select Category" data-allow-clear="true" required data-parsley-required-message="Select Category">
                                @foreach($types as $type_id => $typeName)
                                <option value="{{ $type_id }}" {{ $type_id == $type ? 'selected' : '' }}>
                                    {{ $typeName }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group mobile-form-group">
                            <label>Phone No<b class="text-danger">*</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <select class="form-control jqv-input product_catd select2" name="dial_code" data-role="select2" data-placeholder="Select Code" data-allow-clear="true" required data-parsley-required-message="Select Code">
                                        @foreach ($countries as $cnt)
                                        <option @if($id) @if($dial_code==$cnt->dial_code) selected @endif
                                            @endif value="{{ $cnt->dial_code }}">+{{ $cnt->dial_code }}</option>
                                        @endforeach;
                                    </select>
                                </div>
                                <input id="phone" autocomplete="off" type="number" class="form-control frmt_number nmbr_no_arrow" name="phone" value="{{empty($phone) ? '': $phone}}" data-jqv-required="true" required data-parsley-required-message="Enter Phone number" data-parsley-type="digits" data-parsley-minlength="5" data-parsley-maxlength="12" data-parsley-trigger="keyup">
                            </div>
                            <span id="mob_err"></span>
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

                    <div class="col-md-4 cnd_indvl" id="date_of_birth_wrap">
                        <div class="form-group">
                            <label>Date Of Birth <span style="color:red;">*<span></span></span></label>
                            <input type="text" class="dob form-control flatpickr-input w-100" data-date-format="dd-mm-yyyy" name="date_of_birth" value="{{ empty($date_of_birth) ? '' : date('d-m-Y', strtotime($date_of_birth))}}" required data-max-date='today' data-parsley-required-message="Enter Date of Birth">
                        </div>
                    </div>


                    <div class="col-md-4 cnd_indvl" id="date_of_birth_wrap">
                        <div class="form-group">
                            <label>Availability From <span style="color:red;">*<span></span></span></label>
                            <input type="text" class="dob form-control w-100" id="from_date" data-date-format="dd-mm-yyyy" name="availability_from" value="{{ empty($availability_from) ? '' : date('d-m-Y', strtotime($availability_from))}}" required data-min-date='today' data-parsley-required-message="Enter Availability from date">
                        </div>
                    </div>

                    <div class="col-md-4 cnd_indvl" id="date_of_birth_wrap">
                        <div class="form-group">
                            <label>Availability To <span style="color:red;">*<span></span></span></label>
                            <input type="text" class="dob form-control w-100 to_date" id="to_date" data-date-format="dd-mm-yyyy" name="availability_to" value="{{ empty($availability_to) ? '' : date('d-m-Y', strtotime($availability_to))}}" required data-min-date='today' data-parsley-required-message="Enter Availability to date">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@if ($id)
                                New Password
                                @else
                                Password <b class="text-danger">*</b>
                                @endif </label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="password" name="password" data-jqv-maxlength="50" value="" data-parsley-minlength="8" autocomplete="off" data-parsley-errors-container="#p1_err" autocomplete="new-password">
                                <div class="input-group-append" style="cursor: pointer; position: absolute; right: 20px; top: 16px; margin: 0; z-index: 9;">
                                    <span class="input-group-text" onclick="password_show_hide();">
                                        <i class="fas fa-eye d-none" id="show_eye"></i>
                                        <i class="fas fa-eye-slash" id="hide_eye"></i>
                                    </span>
                                </div>
                            </div>
                            <span id="p1_err"></span>
                        </div>

                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@if ($id)
                                Confirm New Password
                                @else
                                Confirm Password <b class="text-danger">*</b>
                                @endif </label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" name="confirm_password" data-jqv-maxlength="50" value="" data-parsley-minlength="8" data-parsley-equalto="#password" autocomplete="off" data-parsley-required-message="Please re-enter your new password." data-parsley-required-if="#password" id="password2" data-parsley-errors-container="#p2_err" autocomplete="new-password">
                                <div class="input-group-append" style="cursor: pointer; position: absolute; right: 20px; top: 16px; margin: 0; z-index: 9;">
                                    <span class="input-group-text" onclick="password_show_hide2();">
                                        <i class="fas fa-eye d-none" id="show_eye2"></i>
                                        <i class="fas fa-eye-slash" id="hide_eye2"></i>
                                    </span>
                                </div>
                            </div>
                            <span id="p2_err"></span>
                        </div>

                    </div>



                </div>

            </div>

            <div class="col-xs-12 col-sm-6">
            </div>
        </div>
    </div>


    <div class="card mb-5">
        <h5 class="card-header">Payment Settings</h5>
        <div class="card-body">
            <div class="row">


                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            <span id="name_label">Hourly Rate</span><b class="text-danger">*</b>
                        </label>
                        <input type="number" name="hourly_rate" class="form-control frmt_number" required data-parsley-required-message="Hourly Rate" value="{{ $hourly_rate }}">
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            <span id="name_label">Deposit Amount</span><b class="text-danger">*</b>
                        </label>
                        <input type="number" name="deposit_amount" class="form-control frmt_number" required data-parsley-required-message="Enter Deposit Amount" value="{{ $deposit_amount }}">
                    </div>
                </div>


            </div>


        </div>
    </div>

    <div class="card mb-5">
        <h5 class="card-header">More Details</h5>
        <div class="card-body">
            <div class="row">


                <div class="col-md-12">
                    <div class="form-group">
                        <label>Bio<b class="text-danger">*</b></label>
                        <textarea name="about" class="form-control editor" required data-parsley-required-message="Enter artist bio" rows="5">{{ $about }}</textarea>
                    </div>
                </div>


                <div class="col-md-12 d-none">
                    <div class="form-group">
                        <label>Cancelation Policy<b class="text-danger">*</b></label>
                        <textarea name="c_policy" class="form-control editor" data-parsley-required-message="Enter Cancelation policy" rows="5">{{ $c_policy }}</textarea>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label>Instagram</label>
                        <input type="text" name="instagram" class="form-control" data-parsley-required-message="Enter instagram" value="{{ $instagram }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> X </label>
                        <input type="text" name="twitter" class="form-control" data-parsley-required-message="Enter twitter" value="{{ $twitter }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Facebook</label>
                        <input type="text" name="facebook" class="form-control" data-parsley-required-message="Enter facebook" value="{{ $facebook }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tiktok</label>
                        <input type="text" name="tiktok" class="form-control" data-parsley-required-message="Enter tiktok" value="{{ $tiktok }}">
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label>Threads</label>
                        <input type="text" name="thread" class="form-control" data-parsley-required-message="Enter thread" value="{{ $thread }}">
                    </div>
                </div>



                <div class="form-group col-md-12 d-none">
                    <label class="control-label">Enter the location or Drag the marker<b class="text-danger">*</b></label>
                    <input type="text" name="location_name" id="txt_location" class="form-control autocomplete" placeholder="Location" data-parsley-required-message="Enter Location" @if($location_name) value="{{$location_name}}" @endif>
                    <input type="hidden" id="location" name="location">
                </div>
                <div class="form-group col-md-12 d-none">
                    <div id="map_canvas" style="height: 200px;width:100%;"></div>
                </div>



            </div>


        </div>
    </div>


    <div class="card mb-5 d-none">
        <h5 class="card-header">Reschedule Policy</h5>
        <div class="card-body">
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <p>If you want zero penalty then please enter the 0 in the amount, if you want full deposit please enter 100%, else enter the penalty amount</p>
                        <table id="policy-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Start Day</th>
                                    <th>End Day</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <button id="add-policy" class="btn btn-primary mt-2" type="button">Add Policy</button>
                    </div>
                </div>

            </div>


        </div>
    </div>




    <div class="card mb-5">
        <h5 class="card-header">Profile Picture</h5>
        <div class="card-body">
            <div class="row">

                <div class="col-md-6 cond_comp">
                    <div class="form-group">
                        <label>Profile Picture</label><br>

                        <input type="file" name="user_image" class="form-control" data-role="file-image" accept="image/jpeg, image/png, image/gif" data-parsley-fileextension="jpg,png,gif,jpeg" data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB">
                        <p>Maximum allowed size is 5mb</p>
                        <br><br>
                        <div class="img_cropper">
                            <img id="image-preview" class="img-responsive" @if ($user_image) src="{{ get_uploaded_image_url($user_image, 'vendor_user') }}" @endif>
                            <!-- <img id="image-preview" class="img-responsive" src="https://fbi.cults3d.com/uploaders/22774656/illustration-file/733c8a0b-c6fd-4140-a81e-529662255230/IMG_0264.JPG"> -->
                        </div>

                    </div>
                </div>


            </div>


        </div>
    </div>


    <div class="card mb-5">
        <div class="card-body">
            <div class="row">

                <div class="col-md-12 mt-2">
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>

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
    const input = document.querySelector("#phone");
    input.addEventListener("input", function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
                // Prevent starting with 0
                if (value.startsWith('0')) {
                    value = value.substring(1);
                }

                // Update the input field with the cleaned value
                e.target.value = value;
    });
</script>


<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD12qKppGVDn6Y5QPwpO5liu8326w9jNwY&v=weekly&libraries=places"> </script>
<script>
    var currentLat = <?php echo $lattitude ? $lattitude : 25.204819 ?>;
    var currentLong = <?php echo $longitude ? $longitude : 55.270931 ?>;
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


<script src="{{ asset('') }}admin-assets/image-cropper/cropper.min.js"></script>
<link rel="stylesheet" href="{{ asset('') }}admin-assets/image-cropper/cropper.min.css" />
<script>
    // Image cropper and preview script
    $('input[name="user_image"]').change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = new Image();
                img.src = e.target.result;
                img.onload = function() {
                    // Create a canvas to add the background
                    var canvas = document.createElement('canvas');
                    var context = canvas.getContext('2d');

                    // Set canvas dimensions to match the uploaded image
                    canvas.width = img.width;
                    canvas.height = img.height;

                    // Fill canvas with a white background
                    context.fillStyle = '#141414'; // You can change this to any color you like
                    context.fillRect(0, 0, canvas.width, canvas.height);

                    // Draw the uploaded image on top of the background
                    context.drawImage(img, 0, 0);

                    // Set the preview to the new image with the white background
                    $('#image-preview').attr('src', canvas.toDataURL('image/jpeg'));

                    // If the cropper is not initialized, initialize it
                    if (!window.imageCropper) {
                        window.imageCropper = new Cropper(document.getElementById('image-preview'), {
                            aspectRatio: 1,
                        });
                    } else {
                        // If already initialized, replace the image in the cropper
                        window.imageCropper.replace(canvas.toDataURL('image/jpeg'));
                    }
                };
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>


<script>
    // Return policy management script

    $(document).ready(function() {

        // Function to generate the return policy data row
        function generatePolicyRow(day, dayEnd, amount) {
            return `<tr><td class="edit_row" contenteditable="true">${day}</td><td class="edit_row" contenteditable="true">${dayEnd}</td><td class="edit_row" contenteditable="true">${amount}</td><td><button class="btn btn-sm btn-danger delete-row" type="button">Delete</button></td></tr>`;
        }

        const return_policies = @json($return_policies);

        // Loop through the return policies and append the rows to the table
        return_policies.forEach(policy => {
            $('#policy-table tbody').append(generatePolicyRow(policy.dayStart, policy.dayEnd, policy.amount));
        });

        $('#add-policy').click(function() {
            $('#policy-table tbody').append(generatePolicyRow('', '', ''));
        });

        $('#policy-table').on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
        });


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


        // If both password and confirm password are not matched then show the error
        if ($('#password').val() != $('#password2').val()) {
            $('#password2').addClass('is-invalid');
            $('<div class="invalid-feedback">Password and Confirm Password does not match</div>')
                .insertAfter($('#password2'));

            // scroll to the first error
            var error = $form.find('.is-invalid').eq(0);
            $('html, body').animate({
                scrollTop: (error.offset().top - 100),
            }, 500);

            return false
        }

        App.loading(true);
        $form.find('button[type="submit"]')
            .text('Saving')
            .attr('disabled', true);



        // Loop through the return policy rows and add them to the form data

        var errorFound = false;
        $('#policy-table tbody tr').each(function(index) {
            const day = $(this).find('td').eq(0).text();
            const dayEnd = $(this).find('td').eq(1).text();
            const amount = $(this).find('td').eq(2).text();

            // If any of the field is empty then skip the row
            if (!day || !dayEnd || !amount) return;

            // If the day is not number then show the error
            if (!/^\d+$/.test(day)) {
                $(this).find('td').eq(0).addClass('is-invalid text-danger');
                $('<div class="invalid-feedback">Please enter a valid day</div>')
                    .insertAfter($(this).find('td').eq(0));
                errorFound = true;
                return;
            }

            if (!/^\d+$/.test(dayEnd)) {
                $(this).find('td').eq(0).addClass('is-invalid text-danger');
                $('<div class="invalid-feedback">Please enter a valid day</div>')
                    .insertAfter($(this).find('td').eq(1));
                errorFound = true;
                return;
            }

            // If the amount is not number or number with percentage then show the error
            if (!/^\d+(\.\d+)?%?$/.test(amount)) {
                $(this).find('td').eq(1).addClass('is-invalid text-danger');
                $('<div class="invalid-feedback">Please enter a valid amount</div>')
                    .insertAfter($(this).find('td').eq(2));

                errorFound = true;
                return;
            }

            formData.append('return_policies[' + index + ']', JSON.stringify({
                dayStart: day,
                dayEnd: dayEnd,
                amount: amount
            }));
        });


        // If error found then scroll to the first error
        if (errorFound) {
            var error = $form.find('.is-invalid').eq(0);
            $('html, body').animate({
                scrollTop: (error.offset().top - 100),
            }, 500);
            App.loading(false);
            $form.find('button[type="submit"]')
                .text('Save')
                .attr('disabled', false);
            return false;
        }


        // -------------- Function to submit the form data ------------------
        var submitForm = function() {
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
                                //  location.reload();
                                window.location.href = "{{ route('admin.artist') }}";
                            } else {
                                window.location.href = "{{ route('admin.artist') }}";
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
        }

        // --------------------------------------------------------------------

        // add the window.imageCropper data to the form data
        if (window.imageCropper) {
            window.imageCropper.getCroppedCanvas().toBlob(function(blob) {
                //formData.append('user_image', blob, 'cropped_image.jpg');

                // create the blob file
                var file = new File([blob], 'cropped_image.jpg', {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });
                formData.append('user_image', file);

                // submit the form
                submitForm();

            }, 'image/jpeg', 0.8);
        } else {
            // submit the form
            submitForm();
        }



    });
</script>

<script src="{{ asset('admin-assets/plugins/editors/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('admin-assets/plugins/editors/tinymce/editor_tinymce.js') }}"></script>
<script>
    tinymce.init({
        mode: "specific_textareas",
        editor_selector: "editor",
        plugins: ' fullscreen autolink lists link',
        toolbar: ' fullscreen fontcolor code pageembed numlist bullist link',
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'MODA',
        images_upload_url: '{{url("admin/editorImageUpload")}}',
        setup: function(editor) {
            editor.on('change', function() {
                tinymce.triggerSave();
            });
        }
    });
    function reformatDate(dateStr) {
            const [day, month, year] = dateStr.split('-');
            return `${year}-${month}-${day}`;
        }

    var fromDate = flatpickr("#from_date", {
            dateFormat: "d-m-Y",
            minDate: "today",
            maxDate: "{{!empty($availability_to) ? date('d-m-Y', strtotime($availability_to)) : "";}}",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    toDate.set('minDate', selectedDates[0]);
                } else {
                    toDate.set('minDate', null);
                }
            }
        });

        var toDate = flatpickr("#to_date", {
            dateFormat: "d-m-Y",
            minDate: "{{!empty($availability_from) ? date('d-m-Y', strtotime($availability_from)) : "";}}",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    fromDate.set('maxDate', selectedDates[0]);
                } else {
                    fromDate.set('maxDate', null);
                }
            }
        });
</script>

@stop
