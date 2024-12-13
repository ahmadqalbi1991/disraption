@extends('vendor.template.layout')

@section('content')
<div class="card mb-5">
    <div class="card-body">

        <form method="post" id="admin-form" action="{{ route('vendor.facilities.save') }}" enctype="multipart/form-data" data-parsley-validate="true">
            <input type="hidden" name="id" value="{{ $id }}">
            @csrf()
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="form-group">
                        <label>Name<b class="text-danger">*</b></label>
                        <input type="text" name="name" class="form-control" required data-parsley-required-message="Enter Facility Name" value="{{ $name }}">
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" required data-parsley-required-message="Enter Facility description">{{ $description }}</textarea>
                    </div>
                </div>


                <div class="col-md-6 mb-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>


        </form>

        <div class="col-xs-12 col-sm-6">

        </div>
    </div>
</div>
@stop


@section('after_content')
<div class="container mt-5">
    <div class="card mb-5">


        <?php /* @if(get_user_permission('country','c') && $id) */ ?>
        @if($id)
        <div class="card-header"><a href="#" id="add_facility" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Add Facility</a></div>
        @endif
        <?php /* @endif */ ?>

        <div class="card-body">

            <h5 class="text-center mb-4">Facility Items</h5>

            @if(!$id)
            <p class="text-center text-danger">Please save this facility in order to add the facility items!</p>
            @endif


            @if($id)
            <table class="table table-condensed table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <!-- <th>Category Name</th>
                            
                            <th>Image</th> -->
                        <th>Name</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($facilityItems as $facility_item)
                    <?php $i++; ?>
                    <tr id="facilItm-{{$i}}">
                        <td>{{ $i }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span>
                                    {{ $facility_item->name }}
                                </span>
                            </div>
                        </td>
                        <td>{{ $facility_item->description }}</td>
                        <td><img style="width: 60px" src="{{get_uploaded_image_url($facility_item->icon, 'facility_item')}}"></td>

                        <td class="text-center">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink7">

                                    <a class="dropdown-item delete_facility" data-rowId="{{$i}}" data-facilityId="{{$facility_item->id}}" href="#"><i class="flaticon-delete-1"></i> Delete</a>

                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>


            @endif

            <div class="col-xs-12 col-sm-6">

            </div>
        </div>
    </div>
</div>
@stop


<div class="modal fade" id="addFacilityModal" tabindex="-1" role="dialog" aria-labelledby="addFacilityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="addFacilityModalLabel">Add Facility Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="post" id="addFacilityForm" action="{{ route('vendor.facilities.store_facility_item') }}" enctype="multipart/form-data" data-parsley-validate="true">
                    <input type="hidden" name="facilityGroupId" id="fcid" value="{{ $id }}">
                    @csrf()
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name <b class="text-danger">*</b></label>
                                <input type="text" name="name" class="form-control" required data-parsley-required-message="Enter facility name" value="">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Extra Info</label>
                                <input type="text" name="extra_info" class="form-control" data-parsley-required-message="Enter facility extra info" value="">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Image <b class="text-danger">*</b></label><br>
                                <img id="image-preview" style="width:100px; height:90px;" class="img-responsive m-auto">
                                <br><br>
                                <input type="file" required name="icon" class="form-control" data-role="file-image" data-preview="image-preview" data-parsley-trigger="change" accept="image/jpeg, image/png, image/gif" data-parsley-fileextension="jpg,png,gif,jpeg" data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB">
                                
                            </div>
                        </div>


                        <div class="col-md-12 mt-2">
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </div>

                    </div>
                </form>


            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteFacilityModal" tabindex="-1" role="dialog" aria-labelledby="addFacilityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">Do you want to remove this facility item?</div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="button" id="delete_yes" class="btn btn-primary">Yes</button></div>
        </div>
    </div>
</div>

@section('script')
<script>
    App.initFormView();



    // --------- On delete facility item -------

    // Handle record delete
    $('body').off('click', '.delete_facility');
    $('body').on('click', '.delete_facility', function(e) {
        e.preventDefault();
        var msg = $(this).data('message') || 'Are you sure that you want to delete this record?';
        var href = "{{route('vendor.facilities.delete_facility_item')}}";

         const rowId = $(this).attr("data-rowId");
        const facilityId = $(this).attr("data-facilityId");

        App.confirm('Confirm Delete', msg, function() {
            // Gather form data
            var formData = {
                "_token": "{{ csrf_token() }}",
                "facilityItemId": facilityId,
                "facilityGroupId": "{{$id}}"
                // Add other form fields as needed
            };

            // Perform AJAX request
            var ajxReq = $.ajax({
                url: href,
                type: 'post',
                dataType: 'json',
                data: formData, // Pass form data to the request
                success: function(res) {
                    if (res['status'] == 1) {
                        App.alert(res['message'] || 'Deleted successfully', 'Success!');

                       // Remove the row
                        $('#facilItm-' + rowId).remove();

                    } else {
                        App.alert(res['message'] || 'Unable to delete the record.', 'Failed!');
                    }
                },
                error: function(jqXhr, textStatus, errorMessage) {
                    // Handle error if needed
                }
            });
        });


    });


    // -----------------------------------------


    // ----------- On add facility item -------

    $('#add_facility').on('click', function(e) {
        e.preventDefault();
        // Add your code here

        $('#addFacilityModal').modal('show');

    });



    $('body').off('submit', '#addFacilityForm');
    $('body').on('submit', '#addFacilityForm', function(e) {
        e.preventDefault();
        var $form = $(this);
        var formData = new FormData(this);
        $(".invalid-feedback").remove();

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
            dataType: 'json',
            timeout: 600000,
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
                        var m = res['message'];
                        App.alert(m, 'Oops!');
                    }
                } else {
                    App.alert(res['message']);

                    // reload the page
                    setTimeout(function() {
                        window.location.reload();
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


    // ----------------------------------------


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

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: $form.attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json',
            timeout: 600000,
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
                        var m = res['message'];
                        App.alert(m, 'Oops!');
                    }
                } else {
                    App.alert(res['message']);
                    setTimeout(function() {
                        window.location.href = res["redirectUrl"];
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