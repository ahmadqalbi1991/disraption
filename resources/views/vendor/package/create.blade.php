@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout')
@section('header')

@stop
@section('content')

<?php
// If function not exist
if (!function_exists('ifProductIncluded')) {
    function ifProductIncluded($product_id, $products)
    {
        if (array_key_exists($product_id, $products)) {
            return $products[$product_id]['included'];
        }
        return 0;
    }
}

if (!function_exists('ifProductActive')) {
    function ifProductActive($product_id, $products)
    {
        if (array_key_exists($product_id, $products)) {
            return 1;
        }
        return 0;
    }
}

?>


<form method="post" id="admin-form" action="{{ route(route_name_admin_vendor($type, 'package.save'), ['user_id' => $user_id, 'type' => $type]) }}" enctype="multipart/form-data" data-parsley-validate="true">

    <div class="card mb-5">
        <div class="card-body">
            <div class="">

                <input type="hidden" name="id" id="cid" value="{{ $id }}">
                <input type="hidden" name="user_id" value="{{ $user_id }}">
                
                @csrf()

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name<b class="text-danger">*</b></label>
                            <input type="text" name="name" class="form-control" required data-parsley-required-message="Enter Package Name" value="{{ $name }}">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Price (inclusive of tax)<b class="text-danger">*</b></label>
                            <input type="number" name="price" class="form-control frmt_price" required data-parsley-required-message="Enter price" value="{{ $price }}">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Offer Price (inclusive of tax)</label>
                            <input type="number" name="offer_price" class="form-control frmt_price" data-parsley-required-message="Enter offer price" value="{{ $offer_price }}">
                        </div>
                    </div>


                    <div class="col-md-6 cnd_indvl">
                        <div class="form-group">
                            <label>Yatch<b class="text-danger">*</b></label>
                            <select class="form-control" name="yatch_id" autocomplete="off" required data-parsley-required-message="">
                                <option value="">Please chose</option>
                                @foreach ($yatches as $yatch)
                                <option @if($id) @if($yatch_id==$yatch->id) selected @endif
                                    @endif value="{{ $yatch->id }}">{{ $yatch->name }}</option>
                                @endforeach;
                            </select>
                            <span id="mob_err"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Package Category<b class="text-danger">*</b></label>
                            <select class="form-control jqv-input product_catd select2" data-jqv-required="true" name="categories[]" data-role="select2" data-placeholder="Select Categories" data-allow-clear="true" multiple="multiple" required data-parsley-required-message="Select Category">
                                @foreach($package_categories as $key => $val)
                                @if ($val->sub->count() > 0)
                                <optgroup label="{{  $val->name }}">
                                    @foreach($val->sub as $sub)
                                    <option data-style="background-color: #ff0000;" value="{{ $sub->id }}" {{ in_array($sub->id, $categories) ? 'selected' : '' }}>
                                        {!! str_repeat('&nbsp;', 4) !!} {{ $sub->name }}
                                    </option>
                                    @endforeach
                                </optgroup>
                                @else
                                <option value="{{ $val->id }}" {{ in_array($val->id, $categories) ? 'selected' : '' }}>
                                    {{ $val->name }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Addons</label>
                            <select class="form-control jqv-input product_catd select2" name="addons[]" data-role="select2" data-placeholder="Select Addons" data-allow-clear="true" multiple="multiple" required data-parsley-required-message="Select Addon">
                                @foreach($addons_master as $key => $val)
                                <option value="{{ $val['id'] }}" {{ in_array($val['id'], $addons) ? 'selected' : '' }}>
                                    {{ $val['name'] }}
                                </option>
                                @endforeach
                            </select>
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

                    <div class="col-12">
                        <div class="form-group">
                            <label>What includes<b class="text-danger">*</b></label>
                            <textarea rows="4" name="what_includes" class="form-control" placeholder="Example 20 baloons (10 white | 10 pink)" required data-parsley-required-message="Enter what includes">{{ $what_includes }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Package Details<b class="text-danger">*</b></label>
                            <textarea rows="4" name="package_details" placeholder="Example: Celebrate a spcial event, or do the perfect night time wedding proposal" class="form-control" required data-parsley-required-message="Enter what includes">{{ $package_details }}</textarea>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="form-group">
                            <label>Departure Info<b class="text-danger">*</b></label>
                            <textarea rows="4" name="departure_info" class="form-control" required data-parsley-required-message="Enter what includes">{{ $departure_info }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Return info<b class="text-danger">*</b></label>
                            <textarea rows="4" name="return_info" class="form-control" required data-parsley-required-message="Enter what includes">{{ $return_info }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Additional Info<b class="text-danger">*</b></label>
                            <textarea rows="4" name="additional_info" class="form-control" required data-parsley-required-message="Enter what includes">{{ $additional_info }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Cancellation Policy<b class="text-danger">*</b></label>
                            <textarea rows="4" name="cancellation_policy" class="form-control" required data-parsley-required-message="Enter what includes">{{ $cancellation_policy }}</textarea>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="form-group">
                            <label>Faq Info<b class="text-danger">*</b></label>
                            <textarea rows="4" name="faq_info" class="form-control" required data-parsley-required-message="Enter what includes">{{ $faq_info }}</textarea>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="form-group">
                            <label>Help Info<b class="text-danger">*</b></label>
                            <textarea rows="4" name="help_info" class="form-control" required data-parsley-required-message="Enter what includes">{{ $help_info }}</textarea>
                        </div>
                    </div>


                    <div class="form-group col-md-12">
                        <label class="control-label">Starting point location or Drag the marker<b class="text-danger">*</b></label>
                        <input type="text" name="txt_location" id="txt_location" class="form-control autocomplete" placeholder="Location" required data-parsley-required-message="Enter Location" @if(isset($location)) value="{{$location}}" @endif>
                        <input type="hidden" id="location" name="location">
                    </div>
                    <div class="form-group col-md-12">
                        <div id="map_canvas" style="height: 200px;width:100%;"></div>
                    </div>



                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Duration Hours<b class="text-danger">*</b></label>
                            <input type="number" name="duration" class="form-control frmt_number" required data-parsley-required-message="Enter Duration" value="{{ $duration }}">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Start Date & Time<b class="text-danger">*</b></label>
                            <input type="text" class="dob form-control flatpickr-input-wtime w-100" data-date-format="dd-mm-yyyy" name="start_date" value="{{ empty($start_date) ? '' : date('Y-m-d H:i', strtotime($start_date))}}" required data-max-date='today' data-parsley-required-message="Enter start date">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>End Date & Time<b class="text-danger">*</b></label>
                            <input type="text" class="dob form-control flatpickr-input-wtime w-100" data-date-format="dd-mm-yyyy" name="end_date" value="{{ empty($end_date) ? '' : date('Y-m-d H:i', strtotime($end_date))}}" required data-parsley-required-message="Enter end date">
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Images</label><br>
                            <!-- Image previews will be appended here -->
                            
                            <!-- Input for selecting images -->
                            <input type="file" name="newMedias[]" class="form-control" multiple accept="image/jpeg, image/png, image/gif" data-parsley-trigger="change" data-parsley-fileextension="jpg,png,gif,jpeg" data-parsley-fileextension-message="Only files with types jpg, png, gif, jpeg are supported" data-parsley-max-file-size="5120">
                            <span class="text-info">Upload Images (Maximum 5 medias allowed)</span>
                            <div id="image-previews" class="d-flex">
                                @foreach($medias as $media)
                                <div class="b_img_div">
                                    <img src="{{ get_uploaded_image_url($media->filename, 'packages') }}" class="img-responsive mr-4" style="width: 100px; height: 90px;">
                                    <div class="del-product-img" data-role="product-img-trash" data-packageid="{{$id}}" data-rid="{{$media->id}}"><svg class="svg-inline--fa fa-trash-can" aria-hidden="true" focusable="false" data-prefix="far" data-icon="trash-can" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                            <path fill="currentColor" d="M160 400C160 408.8 152.8 416 144 416C135.2 416 128 408.8 128 400V192C128 183.2 135.2 176 144 176C152.8 176 160 183.2 160 192V400zM240 400C240 408.8 232.8 416 224 416C215.2 416 208 408.8 208 400V192C208 183.2 215.2 176 224 176C232.8 176 240 183.2 240 192V400zM320 400C320 408.8 312.8 416 304 416C295.2 416 288 408.8 288 400V192C288 183.2 295.2 176 304 176C312.8 176 320 183.2 320 192V400zM317.5 24.94L354.2 80H424C437.3 80 448 90.75 448 104C448 117.3 437.3 128 424 128H416V432C416 476.2 380.2 512 336 512H112C67.82 512 32 476.2 32 432V128H24C10.75 128 0 117.3 0 104C0 90.75 10.75 80 24 80H93.82L130.5 24.94C140.9 9.357 158.4 0 177.1 0H270.9C289.6 0 307.1 9.358 317.5 24.94H317.5zM151.5 80H296.5L277.5 51.56C276 49.34 273.5 48 270.9 48H177.1C174.5 48 171.1 49.34 170.5 51.56L151.5 80zM80 432C80 449.7 94.33 464 112 464H336C353.7 464 368 449.7 368 432V128H80V432z"></path>
                                        </svg>Delete</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="col-xs-12 col-sm-6">
            </div>
        </div>
    </div>



    <div class="card mb-5">
        <h5 class="card-header">Products</h5>
        <div class="card-body">
            <div class="">

                <div class="row">

                    <!--<div class="col-12">-->
                    <!--    <h3>Products</h3>-->
                    <!--</div>-->

                    @foreach ($productCategories as $prodCat)

                    <h3 class="text-center w-100">{{$prodCat->name}}</h3>

                    <div class="table-responsive mx-auto mb-4" style="max-width:800px">
                        <table class="table table-condensed table-striped" id="example2">
                            <thead>
                                <tr>
                                    <th>Product Detail</th>
                                    <th>Price</th>
                                    <th>Included in Package</th>
                                    <th>Active</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($prodCat->products as $product)
                                <tr>
                                    <td>
                                        <span>
                                            @if ($product->image != '')
                                            <img id="image-preview" style="width:100px; height:90px;" class="img-responsive mb-2" data-image="{{ get_uploaded_image_url($product->image, 'products') }}" src="{{ get_uploaded_image_url($product->image, 'products') }}">
                                            @endif
                                            {{$product->name}}
                                        </span>
                                    </td>
                                    <td>{{$product->price}}</td>
                                    <td>

                                        <div class="form-check wrap">
                                            <input class="form-check-input tick_prod" type="checkbox" value="{{$product->id}}" @if (ifProductIncluded($product->id, $products)) checked @endif>
                                            <input type="hidden" class="actl_val" name="products[{{$product->id}}][included]" value="{{$product->price}}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check wrap">
                                            <input class="form-check-input tick_prod" type="checkbox" value="{{$product->id}}" @if (ifProductActive($product->id, $products)) checked @endif>
                                            <input type="hidden" class="actl_val" name="products[{{$product->id}}][active]" value="{{$product->price}}">

                                        </div>
                                    </td>
                                </tr>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <hr />
                    @endforeach

                </div>

            </div>

        </div>
    </div>


    <div class="card mb-5">
        <div class="card-body">
            <div class="">

                <div class="row">

                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>


</form>
@stop
@section('script')

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD12qKppGVDn6Y5QPwpO5liu8326w9jNwY&v=weekly&libraries=places">
</script>

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


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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


    // ------------- On medias selected ---------------

    const files = {};

    document.addEventListener("DOMContentLoaded", function() {
        const imagePreviews = document.getElementById('image-previews');
        const inputImages = document.querySelector('input[name="newMedias[]"]');

        inputImages.addEventListener('change', function() {

            const maxIamges = 5;
            const PrevImages = imagePreviews.querySelectorAll('img').length;

            if (maxIamges - PrevImages <= 0) return App.alert(`You can only upload ${maxIamges} medias`, 'Oops!');

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
        var packageId = $(this).data('packageid');
        var _this = $(this);
        App.confirm('Confirm Delete', 'You will not be able to undo if you delete the media!', function() {
            // Gather form data
            var formData = {
                "_token": "{{ csrf_token() }}",
                "imageId": rid,
                "packageId": packageId,
                "user_id": "{{ $user_id }}",
                // Add other form fields as needed
            };

            // Perform AJAX request
            var ajxReq = $.ajax({
                url: "{{ route(route_name_admin_vendor($type, 'package.delete_image'), ['user_id' => $user_id, 'type' => $type]) }}",
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
        var $form = $(this);
        var formData = new FormData(this);

        $(".invalid-feedback").remove();

        App.loading(true);
        $form.find('button[type="submit"]')
            .text('Saving')
            .attr('disabled', true);

        var parent_tree = $('option:selected', "#parent_id").attr('data-tree');
        formData.append("parent_tree", parent_tree);
        formData.append("products_changed", products_changed);

        // if offer price is greater than price
        var price = $('[name="price"]').val();
        var offer_price = $('[name="offer_price"]').val();
        if (parseInt(offer_price) > parseInt(price)) {
            App.loading(false);
            App.alert('Offer price should be less than to the price', 'Oops!');
            $form.find('button[type="submit"]')
                .text('Save')
                .attr('disabled', false);
            return false;
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
                            window.location.href = "{{ route(route_name_admin_vendor($type, 'package.index'), ['user_id' => $user_id, 'type' => $type]) }}";
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