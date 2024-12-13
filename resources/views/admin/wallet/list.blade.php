@extends('admin.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section('content')

<?php $permission_id = "customer_wallet"; ?>


<form method="post" id="admin-form" action="{{ route('admin.wallet.balance_save', ['type' => $type, 'user_id' => $user_id]) }}" enctype="multipart/form-data" data-parsley-validate="true">
    <div class="card mb-5">
        <div class="card-body">
            <div class="">
                <input type="hidden" name="user_id" id="cid" value="{{ $user_id }}">
                @csrf()

                <div class="row">

                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label>Wallet Balance</label>
                            <input id="balance" type="number" class="form-control form-control-sm frmt_price" name="wallet_balance" style="max-width: 100px;" value="{{$wallet_balance}}">
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


<h4 class="mb-4">Transactions</h4>

<div class="card mb-5">
    <?php /* if(get_user_permission({{$permission_id}},'c')) { */ ?>
    <?php /* } */ ?>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Transaction id</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Created at</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($transactions as $transaction)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $transaction->transaction_id }}</td>
                        <td>{{ $transaction->amount }} AED</td>
                        <td>{{ $transaction->type }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{web_date_in_timezone($transaction->created_at,'d-m-Y h:i A')}}</td>

                        <td class="text-center">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink7">
                                    <?php /* <!-- @if(get_user_permission({{$permission_id}},'u')) --> */ ?>
                                    <a class="dropdown-item" href="{{ route('admin.wallet.transactions.view', ['user_id' => $user_id, 'type' => 'admin', 'id' => $transaction->id]) }}"><i class="flaticon-pencil-1"></i> View</a>

                                </div>
                            </div>
                        </td>

                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('script')
<script src="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.js"></script>
<script>
    App.initFormView();



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

        // @todo, remove the below two lines after implementing the google map api
        formData.set('location', '234235, 34365645');
        formData.set('location_name', 'Al Safa St Downtown Dubai - Dubai United Arab Emirates');

        // Add_represent_details if selected then set the value 1 else set 0
        formData.set('is_social', $("#is_social").is(':checked') ? 1 : 0);


        // Save form data
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
                    }

                    // If provided the message then show it
                    if (res['message']) {
                        App.alert(res['message'], 'Oops!');
                    }
                } else {
                    App.alert(res['message'], 'Success!');

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


    $('#example2').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
    });
</script>
@stop