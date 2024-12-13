@extends('admin.template.layout')
@section('header')

@stop
@section('content')
<form method="post" id="admin-form" action="{{ route('admin.ratings.save') }}" enctype="multipart/form-data" data-parsley-validate="true">
    <input type="hidden" name="id" id="cid" value="{{ $rating_id }}">
    <div class="card mb-5">
        <div class="card-body">
            <div class="">


                @csrf()

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Artist <span style="color:red;">*<span></label>
                            <select class="form-control jqv-input product_catd select2" name="vendor_id" data-role="select2" data-placeholder="Select Artist" data-allow-clear="true" required data-parsley-required-message="Select Artist">
                                <option value=""> Select Artist </option>
                                @foreach($vendors as $user)
                                <option value="{{ $user->id }}" {{ $vendor_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer <span style="color:red;">*<span></label>
                            <select class="form-control jqv-input product_catd select2" name="customer_id" data-role="select2" data-placeholder="Select Customer" data-allow-clear="true" required data-parsley-required-message="Select Customer">
                                <option value=""> Select Customer </option>
                                @foreach($customers as $user)
                                <option value="{{ $user->id }}" {{ $customer_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Rating</label>
                            <select name="rating" class="form-control" required data-parsley-required-message="Select Rating">
                            @foreach($stars as $loopStar)
                                <option value="{{ $loopStar }}" {{ $star == $loopStar ? 'selected' : '' }}>
                                    {{ $loopStar }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mt-2">
                            <label>Review</label>
                            <textarea name="review" class="form-control">{{ $review }}</textarea>
                        </div>
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
                            'Unable to save review. Please try again later.';
                        App.alert(m, 'Oops!');
                    }
                } else {
                    App.alert(res['message'], 'Success!');
                    setTimeout(function() {
                       window.location.href = "{{ route('admin.ratings.index') }}";
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