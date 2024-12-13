@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout')
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

    td.move-handle {
        max-width: 20px;
        width: 38px;
        text-align: center;
        cursor: move;
    }

    .edit_row a {
        color: white;
    }
</style>
@stop
@section('content')
<div class="card mb-5 form_wrap">
    <div class="card-body">
        <div class="">
            <input type="hidden" name="user_id" id="cid" value="{{ $user_id }}">
            @csrf()

            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">

                        <table id="media-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sort</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Media</th>
                                    <th class="d-none">Sort Order</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <button id="add-media" class="btn btn-primary mt-2" type="button">Add Media</button>
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
                <div class="form-group">

                    <div class="media_progress_Wrap" style="display: none;">
                        <h5>Please wait while the media is uploading.</h5>
                        <div class="progress mt-2" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                    </div>



                    <button type="submit" class="btn btn-primary form_submit">Submit</button>

                </div>
            </div>

        </div>


    </div>
</div>



@stop
@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
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


<script>
    function updateTableSortOrders() {

        // Loop through the media-table rows and update the sort order
        $('#media-table tbody tr').each(function(index) {
            $(this).find('.sort_order').text(index);
        });

    }

    $("tbody").sortable({
        cursor: 'row-resize',
        placeholder: 'ui-state-highlight',
        opacity: '0.55',
        items: 'tr',
        handle: '.move-handle',
        update: updateTableSortOrders
    });
</script>


<script>
    // Media management script

    $(document).ready(function() {

        // Function to generate the mediay data row editabled with delete button
        function generateRow(id, title, description, filepath, mediaType, sort_order) {
            return `
            <tr data-id="${id}">
                <td class="move-handle">&#9776;</td>
                <td class="edit_row title" style="max-width: 100px; overflow: hidden;" contenteditable="true">${title}</td>
                <td class="edit_row description" style="max-width: 150px;" contenteditable="true">${description}</td>
                <td class="edit_row" style="width: 400px;">

                ${
                    // It's server media so render based on the type
                    id != 'new' ?

                    // It's server media

                    // if the media is image then render the image tag
                    mediaType.includes('image') ? 
                    `<a href="${filepath}" target="_blank"><img src="${filepath}" style="max-width: 100px; max-height: 100px;"></a>`
                    :

                    // It's video so render the video tag
                    `
                    <a href="${filepath}" target="_blank">View Media</a>
                    `
                    
                    : 

                    // It's new media so render the file input
                    `<input type="file" class="form-control media" accept="image/*, video/*">`

                }


                </td>
                <td class="edit_row sort_order d-none" contenteditable="true" style="width: 100px;" onkeypress="return isNumberKey(event)" onpaste="return false" oninput="maxLengthCheck(this)">${sort_order}</td>
                <td style="width: 100px;"><button class="btn btn-sm btn-danger delete-row" type="button">Delete</button></td>
            </tr>
        `;


        }

        const vendor_portfolios = @json($vendor_portfolios);


        // Loop through the media data and add them to the table
        vendor_portfolios.forEach(media => {
            $('#media-table tbody').append(generateRow(media.id, media.title, media.description, media.filename, media.type, media.sort_order));

        });

        $('#add-media').click(function() {

            $('#media-table tbody').append(generateRow('new', '', '', '', '', 0));

            updateTableSortOrders();
        });

        $('#media-table').on('click', '.delete-row', function() {

            // If the id is provided then it's server image so ask for the confirmation
            if ($(this).closest('tr').data('id') != 'new') {

                // App.Alert the user for the confirmation
                App.confirm('Are you sure you want to delete this media?', 'Delete Confirmation', function(confirmed) {
                    if (confirmed) {
                        $(this).closest('tr').remove();
                    }
                }.bind(this));

            } else {
                $(this).closest('tr').remove();
            }


            updateTableSortOrders();

        });


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


    // ------- Function to validate the edit table number input --------

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function maxLengthCheck(object) {
        if (object.innerHTML.length >= 2) {
            object.innerHTML = object.innerHTML.slice(0, 2);
        }
    }

    // --------------------------------------------------


    var $mediaProgress = $('.media_progress_Wrap');


    $('body').off('submit', '#admin-form');
    $('body').on('click', '.form_submit', function(e) {
        e.preventDefault();
        var $form = $(".form_wrap");
        var formData = new FormData();
        $(".invalid-feedback").remove();



        App.loading(true);
        $('.form_submit')
            .text('Saving')
            .attr('disabled', true);

        // Add the user_id, csrf token
        formData.append('user_id', $('#cid').val());
        formData.append('_token', $('input[name="_token"]').val());

        var mediaFound = false;


        // Loop through the media-table rows and add them to the form data
        $('#media-table tbody tr').each(function() {
            var id = $(this).data('id');
            var title = $(this).find('td.title').text();
            var description = $(this).find('td.description').text();
            var sort_order = $(this).find('td.sort_order').text();
            var media = null;
            try {
                media = $(this).find('input.media').prop('files')[0];

                if (media) {
                    mediaFound = true;
                }

            } catch (error) {}

            // if id is new then generate random id with new prefix
            if (id == 'new') {
                id = 'new-' + Math.random().toString(36).substr(2, 9);
            }

            // If the media is provided then add it to the form data
            if (media) {
                formData.append(`media[${id}][file]`, media);
            }


            formData.append(`media[${id}][id]`, id);
            formData.append(`media[${id}][title]`, title);
            formData.append(`media[${id}][description]`, description);
            formData.append(`media[${id}][sort_order]`, sort_order);

        });


        // If media found the show the progress bar
        if (mediaFound) {
            $mediaProgress.show();
        }



        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "<?php echo route(route_name_admin_vendor($type, 'portfolio.save'), ['user_id' => $user_id, 'type' => $type]) ?>",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            dataType: 'json',
            xhr: function() {
                var xhr = new window.XMLHttpRequest();

                // Upload progress
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        // Update progress bar here
                        $('.progress-bar').css('width', percentComplete + '%').text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(res) {
                App.loading(false);
                $mediaProgress.hide();

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

                        location.reload();

                    }, 1500);

                }

                $('.form_submit')
                    .text('Save')
                    .attr('disabled', false);
            },
            error: function(e) {
                App.loading(false);
                $mediaProgress.hide();
                $('.form_submit')
                    .text('Save')
                    .attr('disabled', false);
                App.alert(e.responseText, 'Oops!');
            }
        });
    });
</script>

@stop