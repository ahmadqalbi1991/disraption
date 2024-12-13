@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout')
@section('header')

@stop
@section('content')

<form method="post" id="admin-form" action="{{ route(route_name_admin_vendor($type, 'yatch.save'), ['user_id' => $user_id, 'type' => $type]) }}" enctype="multipart/form-data" data-parsley-validate="true">

    <div class="card mb-5">
        <div class="card-body">
            <div class="">
                <input type="hidden" name="id" id="cid" value="{{ $id }}">
                <input type="hidden" name="user_id" id="cid" value="{{ $user_id }}">
                @csrf()

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name<b class="text-danger">*</b></label>
                            <input type="text" name="name" class="form-control" required data-parsley-required-message="Enter Yatch Name" value="{{ $name }}">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Capacity<b class="text-danger">*</b></label>
                            <input type="text" name="capacity" class="form-control" required data-parsley-required-message="Enter Yatch Capacity" value="{{ $capacity }}">
                        </div>
                    </div>


                    <div class="col-md-6 cnd_indvl">
                        <div class="form-group">
                            <label>Yatch Type<b class="text-danger">*</b></label>
                            <select class="form-control" name="yatch_type" autocomplete="off" required data-parsley-required-message="">
                                <option value="">Please chose</option>
                                @foreach ($yacht_types_master as $entry)
                                <option @if($id) @if($yatch_type==$entry->id) selected @endif
                                    @endif value="{{ $entry->id }}">{{ $entry->name }}</option>
                                @endforeach;
                            </select>
                            <span id="mob_err"></span>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Size<b class="text-danger">*</b></label>
                            <input type="text" name="size" class="form-control" required data-parsley-required-message="Enter Yatch Size" value="{{ $size }}">
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Rules<b class="text-danger">*</b></label>
                            <textarea rows="4" name="rules" class="form-control" required data-parsley-required-message="Enter rules">{{ $rules }}</textarea>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Facilities<b class="text-danger">*</b></label>
                            <select class="form-control jqv-input product_catd select2" name="facilities[]" data-role="select2" data-placeholder="Select Facility" data-allow-clear="true" multiple="multiple" required data-parsley-required-message="Select Facility">
                                @foreach($facilities_master as $key => $val)
                                <option value="{{ $val['id'] }}" {{ in_array($val['id'], $facilities) ? 'selected' : '' }}>
                                    {{ $val['name'] }}
                                </option>
                                @endforeach
                            </select>
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


                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Image</label><br>
                            <!-- Image previews will be appended here -->
                            
                            <!-- Input for selecting images -->
                            <input type="file" name="images[]" class="form-control" multiple accept="image/jpeg, image/png, image/gif" data-parsley-trigger="change" data-parsley-fileextension="jpg,png,gif,jpeg" data-parsley-fileextension-message="Only files with types jpg, png, gif, jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" data-parsley-imagedimensions="300x300">
                            <span class="text-info">Upload Images (Maximum 5 images)</span>
                            <div id="image-previews" class="d-flex">
                                @foreach($images as $image)
                                <div class="b_img_div">
                                    <img src="{{ get_uploaded_image_url($image->filename, 'yatch') }}" class="img-responsive mr-4" style="width: 100px; height: 90px;">
                                    <div class="del-product-img" data-role="product-img-trash" data-yatchId="{{$id}}" data-rid="{{$image->id}}"><svg class="svg-inline--fa fa-trash-can" aria-hidden="true" focusable="false" data-prefix="far" data-icon="trash-can" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                            <path fill="currentColor" d="M160 400C160 408.8 152.8 416 144 416C135.2 416 128 408.8 128 400V192C128 183.2 135.2 176 144 176C152.8 176 160 183.2 160 192V400zM240 400C240 408.8 232.8 416 224 416C215.2 416 208 408.8 208 400V192C208 183.2 215.2 176 224 176C232.8 176 240 183.2 240 192V400zM320 400C320 408.8 312.8 416 304 416C295.2 416 288 408.8 288 400V192C288 183.2 295.2 176 304 176C312.8 176 320 183.2 320 192V400zM317.5 24.94L354.2 80H424C437.3 80 448 90.75 448 104C448 117.3 437.3 128 424 128H416V432C416 476.2 380.2 512 336 512H112C67.82 512 32 476.2 32 432V128H24C10.75 128 0 117.3 0 104C0 90.75 10.75 80 24 80H93.82L130.5 24.94C140.9 9.357 158.4 0 177.1 0H270.9C289.6 0 307.1 9.358 317.5 24.94H317.5zM151.5 80H296.5L277.5 51.56C276 49.34 273.5 48 270.9 48H177.1C174.5 48 171.1 49.34 170.5 51.56L151.5 80zM80 432C80 449.7 94.33 464 112 464H336C353.7 464 368 449.7 368 432V128H80V432z"></path>
                                        </svg>Delete</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6 cond_comp">
                        <div class="form-group">
                            <label>Yatch Document</label><br>
                            <input type="file" name="document" class="form-control" data-parsley-trigger="change" accept="application/pdf, image/jpeg, image/png" data-parsley-fileextension="pdf,jpg,png,jpeg" data-parsley-fileextension-message="Only files with type pdf,jpg,png,jpeg are supported" data-parsley-max-file-size="10120" data-parsley-max-file-size-message="Max file size should be 10MB" onchange="updateFileName(this)">
                            @if ($document) <a href="#!" class="badge badge-primary"> {{$document}}</a> @endif
                        </div>
                        <script>
                            // On file change then update the name
                            function updateFileName(input) {
                                var fileName = input.files[0].name;
                                document.getElementById('file-name').textContent = fileName;
                            }
                        </script>
                    </div>


                </div>

            </div>
            <div class="col-xs-12 col-sm-6">
            </div>
        </div>
    </div>


    <div class="card mb-5">
        <h5 class="card-header">Captain Details</h5>
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Captain Name<b class="text-danger">*</b></label>
                        <input type="text" name="captain_name" class="form-control" required data-parsley-required-message="Enter Captain name" value="{{ $captain_name }}">
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label>Phone No<b class="text-danger">*</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <select class="form-control jqv-input product_catd select2" name="captain_dial_code" data-role="select2" data-placeholder="Select Code" data-allow-clear="true" required data-parsley-required-message="Select Code">
                                        @foreach ($countries as $cnt)
                                        <option value="{{ $cnt->dial_code }}">+{{ $cnt->dial_code }}</option>
                                        @endforeach;
                                    </select>
                            </div>
                            <input autocomplete="off" type="number" class="form-control frmt_number" name="captain_phone" value="{{empty($captain_phone) ? '': $captain_phone}}" data-jqv-required="true" required data-parsley-required-message="Enter Phone number" data-parsley-type="digits" data-parsley-minlength="5" data-parsley-maxlength="12" data-parsley-trigger="keyup">
                        </div>
                        <span id="mob_err"></span>
                    </div>
                </div>

            </div>
        </div>
    </div>


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

</form>
@stop
@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD12qKppGVDn6Y5QPwpO5liu8326w9jNwY&v=weekly&libraries=places">
</script>
<script>
    var currentLat = <?php echo  $lattitude ?  $lattitude : 25.204819 ?>;
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


    $(document).ready(function() {
        $('.select2').select2();
    });


    // ------------- On images selected ---------------

    const files = {};

    document.addEventListener("DOMContentLoaded", function() {
        const imagePreviews = document.getElementById('image-previews');
        const inputImages = document.querySelector('input[name="images[]"]');

        inputImages.addEventListener('change', function() {

            const maxIamges = 5;
            const PrevImages = imagePreviews.querySelectorAll('img').length;

            if (maxIamges - PrevImages <= 0) return App.alert(`You can only upload ${maxIamges} images`, 'Oops!');

            const files = Array.from(this.files).slice(0, 5 - PrevImages); // Max 5 images

            files.forEach(file => {
                const reader = new FileReader();

                // Generate UID
                const uid = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);

                files[uid] = file;

                reader.onload = function(e) {
                    imagePreviews.innerHTML += ` <div class="b_img_div">
                                    <img src="${e.target.result}" class="img-responsive mr-4" style="width: 100px; height: 90px;">
                                    <div class="del-product-img local" data-role="product-img-trash" data-yatchId="{{$id}}" data-rid="${uid}"><svg class="svg-inline--fa fa-trash-can" aria-hidden="true" focusable="false" data-prefix="far" data-icon="trash-can" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M160 400C160 408.8 152.8 416 144 416C135.2 416 128 408.8 128 400V192C128 183.2 135.2 176 144 176C152.8 176 160 183.2 160 192V400zM240 400C240 408.8 232.8 416 224 416C215.2 416 208 408.8 208 400V192C208 183.2 215.2 176 224 176C232.8 176 240 183.2 240 192V400zM320 400C320 408.8 312.8 416 304 416C295.2 416 288 408.8 288 400V192C288 183.2 295.2 176 304 176C312.8 176 320 183.2 320 192V400zM317.5 24.94L354.2 80H424C437.3 80 448 90.75 448 104C448 117.3 437.3 128 424 128H416V432C416 476.2 380.2 512 336 512H112C67.82 512 32 476.2 32 432V128H24C10.75 128 0 117.3 0 104C0 90.75 10.75 80 24 80H93.82L130.5 24.94C140.9 9.357 158.4 0 177.1 0H270.9C289.6 0 307.1 9.358 317.5 24.94H317.5zM151.5 80H296.5L277.5 51.56C276 49.34 273.5 48 270.9 48H177.1C174.5 48 171.1 49.34 170.5 51.56L151.5 80zM80 432C80 449.7 94.33 464 112 464H336C353.7 464 368 449.7 368 432V128H80V432z"></path></svg>Delete</div>
                                </div>`;
                };

                reader.readAsDataURL(file);
            });
        });
    });

    // ------------------------------------------------


    // --------- On server image delete click then delete the image ----------

    $(document).on('click', '.del-product-img:not(.local)', function(e) {
        var rid = $(this).data('rid');
        var yatchId = $(this).data('yatchid');
        var _this = $(this);
        App.confirm('Confirm Delete', 'You will not be able to undo if you delete the image!', function() {
            // Gather form data
            var formData = {
                "_token": "{{ csrf_token() }}",
                "imageId": rid,
                "yatchId": yatchId,
                "user_id": "{{ $user_id }}"
                // Add other form fields as needed
            };

            // Perform AJAX request
            var ajxReq = $.ajax({
                url: "{{ route(route_name_admin_vendor($type, 'yatch.delete_image'), ['user_id' => $user_id, 'type' => $type]) }}",
                type: 'post',
                dataType: 'json',
                data: formData, // Pass form data to the request
                success: function(res) {
                    if (res['status'] == 1) {
                        // Add sucess message alert
                        App.alert(res['message'], 'Success!');
                        _this.closest('.b_img_div').remove();
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
        console.log("triggered");
        var rid = $(this).data('rid');
        delete files[rid];
        $(this).closest('.b_img_div').remove();
    });


    // --------------------------------------------------------------------------




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
        //formData.set('location_name', 'Al Safa St Downtown Dubai - Dubai United Arab Emirates');

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
                            window.location.href = "{{ route(route_name_admin_vendor($type, 'yatch.index'), ['user_id' => $user_id, 'type' => $type]) }}";
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