@extends('vendor.template.layout')
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

    .selected_customer {
        display: inline-block;
        padding: 18px 23px;
        background: linear-gradient(90deg, #EA33C7 0%, #0507EA 100%, #0507EA 100%) !important;
    }
</style>
@stop
@section('content')
@php
$user_id = "custom_id";
@endphp

<form method="post" id="admin-form" action="{{ route('vendor.booking-orders.save', ['type'=> 'vendor', 'user_id'=> $user_id]) }}" enctype="multipart/form-data" data-parsley-validate="true">

    <div class="card mb-5">
        <div class="card-body">
            <h5 class="card-header">Customer</h5>

            <div class="row mt-3 search_form">


                <div class="col-md-12 mb-4">
                    <div class="form-group">
                        <label>Find customer by <b class="text-danger">*</b></label>
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="option" id="option_email" checked value="email">
                                    <label class="form-check-label" for="option_email">Email</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="option" id="option_phone" value="phone">
                                    <label class="form-check-label" for="option_phone">Phone</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6 email_div">
                    <div class="form-group">
                        <label>Email <b class="text-danger">*</b></label>
                        <input id="find_cust_email" name="custm_email" type="email" class="form-control" data-parsley-required-message="Enter Email">
                    </div>

                </div>


                <div class="col-md-6 phone_div" style="display: none">
                    <div class="form-group">
                        <label>Phone No<b class="text-danger">*</b></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select class="form-control jqv-input product_catd select2" name="custm_dialcode" id="find_cust_dialcode" data-role="select2" data-placeholder="Select Code" data-allow-clear="true" data-parsley-required-message="Select Code">
                                    @foreach ($countries as $cnt)
                                    <option value="{{ $cnt->dial_code }}">+{{ $cnt->dial_code }}</option>
                                    @endforeach;
                                </select>
                            </div>
                            <input autocomplete="off" type="number" class="form-control frmt_number" name="custm_phone" id="find_cust_phone" value="" data-jqv-required="true" data-parsley-required-message="Enter Phone number" data-parsley-type="digits" data-parsley-minlength="5" data-parsley-maxlength="12" data-parsley-trigger="keyup">
                        </div>
                        <span id="mob_err"></span>
                    </div>
                </div>



                <div class="col-md-12 mt-2">
                    <div class="form-group">
                        <button id="search" type="button" class="btn btn-primary">Search</button>
                    </div>
                </div>


            </div>

            <div class="customer_found mt-4" style="display: none;">

                <div class="row">

                    <div class="col-6 details">

                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="form-group">
                            <button id="remove_user" type="button" class="btn btn-primary">Remove Customer</button>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>


    <div class="card mb-5">
        <div class="card-body">
            <div class="">
                <input type="hidden" name="user_id" value="{{$user_id}}">
                @csrf()

                <div class="row">


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Booking Reference No <b class="text-danger">*</b></label>

                            <select name="refrence_no" class="form-control" required data-parsley-required-message="Select Booking Reference">
                                @foreach($bookingReferences as $bookingReference)
                                <option value="{{$bookingReference}}">{{$bookingReference}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>



                    <div class="col-md-6">
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
    // Find customer management

    // On Find customer by radio option change if the option is emaail then show the email div and hide the phone div and vice versa
    $('input[name="option"]').change(function() {

        if ($(this).val() == 'email') {
            $('.email_div').show();
            $('.phone_div').hide();
        } else {
            $('.email_div').hide();
            $('.phone_div').show();
        }
    });


    function doSearch() {

        var option = $('input[name="option"]:checked').val();
        var email = $('#find_cust_email').val();
        var phone = $('#find_cust_phone').val();
        var dialcode = $('#find_cust_dialcode').val();

        if (option == 'email') {
            if (email == '') {
                App.alert('Please enter email', 'Oops!');
                return false;
            }

            // Validate the email without any function
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                App.alert('Please enter valid email', 'Oops!');
                return false;
            }
        } else {
            if (phone == '') {
                App.alert('Please enter phone number', 'Oops!');
                return false;
            }
            if (dialcode == '') {
                App.alert('Please select dial code', 'Oops!');
                return false;
            }
        }

        // Disable the search button and show the loading text
        $('#search').attr('disabled', true).text('Searching ...');

        // functuon to reset the button text and enable the button
        function resetButton() {
            $('#search').attr('disabled', false).text('Search');
        }

        // If all the validations are passed then make the ajax call to find the customer
        $.ajax({
            url: "{{route('vendor.booking-orders.search_user')}}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                type: option,
                email: email,
                phone: phone,
                dialcode: dialcode
            },
            dataType: 'json',
            success: function(res) {
                console.log(res);
                resetButton();
                if (res.status == 1) {

                    $('.search_form').hide();
                    $('.customer_found').show();
                    $('.details').html(
                        ` <div class="selected_customer">
                            <h5>${res.data.name}</h5>
                            <p><strong>Email:</strong> ${res.data.email}</p>
                            <p><strong>Phone:</strong> +${res.data.dial_code} - ${res.data.phone}</p>
                            <input type="hidden" name="custom_user_id" value="${res.data.id}">
                        </div>`
                    );

                } else {
                    // If the customer is not found then show the error message
                    App.alert(res.message, 'Oops!');
                }
            },
            error: function(e) {

                resetButton();

                App.alert(e.responseText, 'Oops!');
            }
        });

    }

    // On email field and phone number press enter then call the doSearch function
    $('#find_cust_email, #find_cust_phone').keypress(function(e) {
        if (e.which == 13) {
            doSearch();

            e.preventDefault();
        }
    });

    // On search button click if the email is provided then validate if the email is valid or not else validate the phone number
    $('#search').click(function() {

        doSearch()

    });

    // On remove user button click hide the customer details and show the search form
    $('#remove_user').click(function() {
        $('.search_form').show();
        $('.customer_found').hide();
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


        // If customer_found is hidden then show the error message
        if ($('.customer_found').is(':hidden')) {
            App.alert('Please find the customer first', 'Oops!');
            App.loading(false);
            $form.find('button[type="submit"]')
                .text('Submit')
                .attr('disabled', false);

            // Sroll to the find customer form
            $('html, body').animate({
                scrollTop: ($('.search_form').offset().top - 100),
            }, 500);
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
                    console.log(res)
                    App.alert(res['message'], 'Success!');
                    setTimeout(function() {

                        //If id is provided then reload the page else redirect to the list page
                        window.location.href = res.data.vendor_panel_redirect_url;

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