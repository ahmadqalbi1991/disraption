@extends('admin.template.layout')
@section('header')

@stop
@section('content')
<form method="post" id="admin-form" action="{{ route(route_name_admin_vendor('admin', 'product.category.save'), ['user_id' => $user_id, 'type' => $type]) }}" enctype="multipart/form-data" data-parsley-validate="true">
    <div class="card mb-5">
        <div class="card-body">
            <div class="">

                <input type="hidden" name="id" id="cid" value="{{ $id }}">
                @csrf()

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category Name<b class="text-danger">*</b></label>
                            <input type="text" name="name" class="form-control" required data-parsley-required-message="Enter Category Name" value="{{ $name }}">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Image</label><br>
                            
                            
                            <input type="file" name="image" class="form-control" @if (!$id) required @endif data-role="file-image" data-preview="image-preview" data-parsley-trigger="change" accept="image/jpeg, image/png, image/gif" data-parsley-fileextension="jpg,png,gif,jpeg" data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" data-parsley-imagedimensions="300x300">
                            <span class="text-info">Upload image with dimension 300x300</span><br>
                            <img id="image-preview" style="width:100px; height:90px;" class="img-responsive mt-2" @if ($image) src="{{ asset($image) }}" @endif>
                        </div>
                    </div>





                </div>

            </div>
            <div class="col-xs-12 col-sm-6">
            </div>
        </div>
    </div>


    <div class="mb-4">
        <div class="card mb-5 cond_comp">
            <h5 class="card-header">Product Remarks Field</h5>
            <div class="card-body">
                <div class="row">
                    <!--<div class="col-12">-->
                    <!--    <h3>Product Remarks Field</h3>-->
                    <!--</div>-->

                    <div class="col-md-6 cond_comp">
                        <div class="form-group">
                            <!--<label>Product Remarks Field</label>-->
                            <!--<div class="form-check">-->
                            <!--    <input id="is_remarks" type="checkbox" class="form-check-input" name="is_remarks" value="1" <?php echo $is_remarks ? 'checked' : ''; ?>>-->
                            <!--</div>-->
                            <div class="form-check-custom mt-3">
                                    <input class="form-check-input-custom" type="checkbox" name="is_remarks"  id="is_remarks"value="1" <?php echo $is_remarks ? 'checked' : ''; ?>>
                                    <label class="form-check-label-custom" for="is_remarks">
                                        Product Remarks Field
                                    </label>
                                </div>
                        </div>
                    </div>

                    <div class="col-md-6 wrap">
                        <div class="form-group">
                            <label>Remark Field Title<b class="text-danger">*</b></label>
                            <input type="text" id="remarks_title" name="remarks_title" class="form-control" required data-parsley-required-message="Enter Remark Field Title" value="{{ $remarks_title }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div>
        <div class="card mb-5 cond_comp">
            <div class="card-body">
                <div class="row">


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="active" class="form-control">
                                <option <?= $active == 1 ? 'selected' : '' ?> value="1">Active</option>
                                <option <?= $active == 0 ? 'selected' : '' ?> value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
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



    // ------ On Product Remarks Field change ------
    $isRemarkelem = $("#is_remarks");
    $isRemarkelem.change(function() {
        if ($(this).is(':checked')) {

            // Add the required attribute to the remarks_title and show it
            $("#remarks_title").attr('required', 'required');
            $("#remarks_title").closest('.wrap').show();



        } else {

            // Remove the required attribute from the remarks_title and hide it
            $("#remarks_title").removeAttr('required');
            $("#remarks_title").closest('.wrap').hide();

        }
    });

    // trigger the change event on init
    $isRemarkelem.trigger('change');
    // ------------------------------


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
       
        // If is_remarks is checked then set 1 else set 0
        formData.append('is_remarks', $("#is_remarks").is(':checked') ? 1 : 0);


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
                        window.location.href = "{{ route(route_name_admin_vendor('admin', 'product.category'), ['user_id' => $user_id, 'type' => $type]) }}";
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