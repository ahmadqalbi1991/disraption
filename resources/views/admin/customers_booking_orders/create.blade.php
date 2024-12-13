@extends('admin.template.layout')
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
</style>
@stop
@section('content')
@php
$rowColumnCss = $user_id == "all" ? 'col-md-4' : 'col-md-6';
@endphp

<form method="post" id="admin-form" action="{{ route('admin.booking-orders.save', ['type'=> $type, 'user_id'=> $user_id]) }}" enctype="multipart/form-data" data-parsley-validate="true">
    <div class="card mb-5">
        <div class="card-body">
            <div class="">
                <input type="hidden" name="id" id="cid" value="{{ $id }}">
                @if ($user_id !== "all")
                <input type="hidden" name="user_id" id="cid" value="{{ $user_id }}">
                @endif
                @csrf()

                <div class="row">

                    @if ($user_id == "all")

                    <div class="{{$rowColumnCss}}">
                        <div class="form-group">
                            <label>Customer <span style="color:red;">*<span></label>
                            <select class="form-control jqv-input product_catd select2" name="user_id" data-role="select2" data-placeholder="Select Customer" data-allow-clear="true" required data-parsley-required-message="Select Customer">
                                <option value=""> Select Customer </option>
                                @foreach($customers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @endif

                    <div class="{{$rowColumnCss}}">
                        <div class="form-group">
                            <label>Booking Reference No <b class="text-danger">*</b></label>
                            <input type="text" name="refrence_no" class="form-control" required data-parsley-required-message="Enter Booking Refrence no">
                        </div>
                    </div>



                    <div class="{{$rowColumnCss}}">
                        <div class="form-group">
                            <label>Payment <b class="text-danger">*</b></label>
                            <select name="payment" class="form-control" required data-parsley-required-message="Select Payment">
                                <option value="advance">Advance</option>
                                <option value="full">Full</option>
                            </select>
                        </div>
                    </div>




                </div>

            </div>

            <div class="col-xs-12 col-sm-6">
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
    $(".flatpicker-time").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",

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



        App.loading(true);
        $form.find('button[type="submit"]')
            .text('Submitting ...')
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

                            try {
                                var error = $form.find('.is-invalid').eq(0);
                                $('html, body').animate({
                                    scrollTop: (error.offset().top - 100),
                                }, 500);
                            } catch (error) {

                            }


                        });
                    }

                    // If provided the message then show it
                    if (res['message']) {
                        App.alert(res['message'], 'Oops!');
                    }
                } else {
                    App.alert(res['message'], 'Success!');
                    setTimeout(function() {

                        //If id is provided then reload the page else redirect to the list page
                        if ('{{ $id }}') {
                            location.reload();
                        } else {
                            window.location.href = "{{ route('admin.booking-orders.index', ['type'=> $type, 'user_id'=> $user_id]) }}";
                        }

                    }, 1500);

                }

                $form.find('button[type="submit"]')
                    .text('Submit')
                    .attr('disabled', false);
            },
            error: function(e) {
                App.loading(false);
                $form.find('button[type="submit"]')
                    .text('Submit')
                    .attr('disabled', false);
                App.alert(e.responseText, 'Oops!');
            }
        });
    });
</script>

@stop