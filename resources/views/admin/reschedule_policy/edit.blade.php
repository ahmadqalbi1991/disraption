@extends('admin.template.layout')
@section('header')

@stop
@section('content')
<form method="post" id="admin-form" action="{{ route('admin.reschedule_policy.store') }}" enctype="multipart/form-data" data-parsley-validate="true">
    <div class="card mb-5">
        <div class="card-body">
            <div class="">

                @csrf()

                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <p>If you want zero penalty then please enter the 0 in the amount, if you want full deposit please enter 100%, else enter the penalty amount</p>
                            <table id="policy-table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Hours</th>
                                        <th>Amount ( In Percenatage )</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <button id="add-policy" class="btn btn-primary mt-2" type="button">Add Policy</button>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Return policy management script

    $(document).ready(function() {

        // Function to generate the return policy data row
        function generatePolicyRow(day,  amount) {
            return `<tr><td class="edit_row" contenteditable="true">${day}</td><td class="edit_row" contenteditable="true">${amount}</td><td><button class="btn btn-sm btn-danger delete-row" type="button">Delete</button></td></tr>`;
        }

        const return_policies = @json($return_policies);

        // Loop through the return policies and append the rows to the table
        return_policies.forEach(policy => {
            $('#policy-table tbody').append(generatePolicyRow(policy.hours, policy.amount));
        });

        $('#add-policy').click(function() {
            $('#policy-table tbody').append(generatePolicyRow('', ''));
        });

        $('#policy-table').on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
        });


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



        // Loop through the return policy rows and add them to the form data

        var errorFound = false;
        $('#policy-table tbody tr').each(function(index) {

            const hours = $(this).find('td').eq(0).text();
            const amount = $(this).find('td').eq(1).text();

            // If any of the field is empty then skip the row
            if (!hours  || !amount) return;


            // If the day is not number then show the error
            if (!/^\d+$/.test(hours)) {
                $(this).find('td').eq(0).addClass('is-invalid text-danger');
                //$(this).find('td').eq(0).html(`<div class="invalid-feedback">Please enter a valid day</div>`);
                $('<div class="invalid-feedback">Please enter a valid hours</div>').insertAfter($(this).find('td').eq(0));
                errorFound = true;
                return;
            }

            // If the amount is not number or number with percentage then show the error
            if (!/^\d+(\.\d+)?%?$/.test(amount)) {
                $(this).find('td').eq(1).addClass('is-invalid text-danger');
                $('<div class="invalid-feedback">Please enter a valid amount</div>')
                    .insertAfter($(this).find('td').eq(2));

                errorFound = true;
                return;
            }

            formData.append('return_policies[' + index + ']', JSON.stringify({
                hours: hours,
                amount: amount
            }));
        });



        // If error found then scroll to the first error
        if (errorFound) {
            var error = $form.find('.is-invalid').eq(0);
            $('html, body').animate({
                scrollTop: (error.offset().top - 100),
            }, 500);
            App.loading(false);
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
