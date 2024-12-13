@extends('admin.template.layout')
@section('header')

@stop
@section('content')
<form method="post" id="admin-form" action="{{ route('admin.settings.store') }}" enctype="multipart/form-data" data-parsley-validate="true">
    <div class="card mb-5">
        <div class="card-body">
            <div class="">

                @csrf()

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Whatsapp Phone No<b class="text-danger">*</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend mr-2">
                                    <select class="form-control jqv-input product_catd select2" name="whatsapp_dialcode" data-role="select2" data-placeholder="Select Code" data-allow-clear="true" required data-parsley-required-message="Select Code">
                                        @foreach ($countries as $cnt)
                                        <option @if($getSettingValue('whatsapp_dialcode')==$cnt->dial_code) selected @endif
                                            value="{{ $cnt->dial_code }}">+{{ $cnt->dial_code }}</option>
                                        @endforeach;
                                    </select>
                                </div>
                                <input autocomplete="off" type="number" class="form-control frmt_number nmbr_no_arrow" name="whatsapp_phone" value="{{empty($getSettingValue('whatsapp_phone')) ? '': $getSettingValue('whatsapp_phone')}}" data-jqv-required="true" required data-parsley-required-message="Enter Phone number" data-parsley-type="digits" data-parsley-minlength="5" data-parsley-maxlength="12" data-parsley-trigger="keyup">
                            </div>
                            <span id="mob_err"></span>
                        </div>
                    </div>


                    @foreach ($fields as $key => $field)

                    <div class="{{$field['class']}}">
                        <div class="form-group">
                            <label>{{$field["label"]}} @if($field["isRequired"])<b class="text-danger">*</b> @endif</label>
                            <input type="{{$field['type']}}" name="{{$key}}" class="form-control" @if($field["isRequired"]) required @endif data-parsley-required-message="Enter {{$field['label']}}" value="{{ $field['value'] }}">
                        </div>
                    </div>

                    @endforeach


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
                            'Unable to save settings. Please try again later.';
                        App.alert(m, 'Oops!');
                    }
                } else {
                    App.alert(res['message'], 'Success!');
                    setTimeout(function() {

                        // reload page
                        window.location.reload();

                    }, 1000);

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
