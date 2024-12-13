@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout')
@section('header')

@stop
@section('content')
<div class="card mb-5">
    <div class="card-body">
        <div class="">
            <form method="post" id="admin-form" action="{{ route(route_name_admin_vendor($type, 'products.save'), ['user_id' => $user_id, 'type' => $type]) }}" enctype="multipart/form-data" data-parsley-validate="true">
                <input type="hidden" name="id" id="cid" value="{{ $id }}">
                <input type="hidden" name="user_id" id="cid" value="{{ $user_id }}">
                @csrf()

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name<b class="text-danger">*</b></label>
                            <input type="text" name="name" class="form-control" required data-parsley-required-message="Enter Product Name" value="{{ $name }}">
                        </div>
                    </div>



                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Price</label>
                            <input id="pricenumber" type="number" name="price" class="form-control frmt_number" step="0.01" data-parsley-required-message="Enter price" value="{{ $price }}">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Product Category</label>
                            <select name="category_id" class="form-control">
                            @foreach($categories as $key => $val)
                                <option value="{{ $val->id }}" {{ $category_id == $val->id ? 'selected' : '' }}>{{ $val->name }}</option>
                            @endforeach
                            
                            </select>
                        </div>
                    </div>
                    
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="active" class="form-control">
                                <option <?= $active == 1 ? 'selected' : '' ?> value="1">Active</option>
                                <option <?= $active == 0 ? 'selected' : '' ?> value="0">Inactive</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Image</label><br>
                            <input type="file" name="image" @if (!$id) required @endif class="form-control" data-role="file-image" data-preview="image-preview" data-parsley-trigger="change" accept="image/jpeg, image/png, image/gif" data-parsley-fileextension="jpg,png,gif,jpeg" data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" data-parsley-imagedimensions="300x300">
                            <span class="text-info">Upload image with dimension 300x300</span><br>
                            <img id="image-preview" style="width:100px; height:90px;" class="img-responsive mt-2" @if ($image) src="{{ get_uploaded_image_url($image, 'products') }}" @endif>
                        </div>
                    </div>

                    
                    
                    


                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
        <div class="col-xs-12 col-sm-6">
        </div>
    </div>
</div>
@stop
@section('script')
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
                            window.location.href = "{{ route(route_name_admin_vendor($type, 'products.index'), ['user_id' => $user_id, 'type' => $type]) }}";
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