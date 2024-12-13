@extends('admin.template.layout')
@section('header')

@stop
@section('content')
<form method="post" id="admin-form" action="{{ route('admin.cancellation.store') }}" enctype="multipart/form-data" data-parsley-validate="true">
    <div class="card mb-5">
        <div class="card-body">
            <div class="">

                @csrf()

                <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Cancelation Policy<b class="text-danger">*</b></label>
                        <textarea name="c_policy" class="form-control editor" required data-parsley-required-message="Enter Cancelation policy" rows="5">{{ $cancellationPolicy }}</textarea>
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


<script src="{{ asset('admin-assets/plugins/editors/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('admin-assets/plugins/editors/tinymce/editor_tinymce.js') }}"></script>
<script>
    tinymce.init({
        mode: "specific_textareas",
        editor_selector: "editor",
        plugins: ' fullscreen autolink lists table link',
        toolbar: ' fullscreen fontcolor code pageembed numlist bullist table link',
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
</script>

<script>
    App.initFormView();

    function resetSubmitButton() {
        $('#admin-form button[type="submit"]')
            .text('Save')
            .attr('disabled', false);
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
                        try {
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
                        } catch (error) {

                        }
                    }

                    // If provided the message then show it
                    if (res['message']) {
                        App.alert(res['message'], 'Oops!');
                    }
                } else {
                    App.alert(res['message'], 'Success!');
                    setTimeout(function() {

                        location.reload();

                    }, 1500);

                }

                resetSubmitButton();
            },
            error: function(e) {
                App.loading(false);
                resetSubmitButton();
                App.alert(e.responseText, 'Oops!');
            }
        });
    });
</script>
@stop
