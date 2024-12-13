@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
    <style>
    .home-section .container-fluid {
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
</style>
@stop


@section("content")
<div class="card mb-5">
    @if(get_user_permission('admin_users','c'))
    <div class="card-header"><a href="{{url('admin/admin_users/create')}}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Create Admin User</a></div>
    @endif
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-condensed table-bordered table-striped" id="example2">
            <thead>
                <tr>
                <th>#</th>
                <th>Action</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Last Logged In</th>
                <th>Created Date</th>
                <th>Active</th>
                
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach($datamain as $datarow) 
                    <?php $i++ ?>
                    <tr>
                        <td>{{$i}}</td>
                        <td class="text-center action">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7">

                                    @if(get_user_permission('admin_users','u'))
                                    <a class="dropdown-item" href="{{url('admin/admin_users/'.$datarow->id.'/edit')}}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif
                                    
                                    @if(get_user_permission('admin_users','u'))
                                    <a class="dropdown-item" href="{{route('admin.change_password_a', ['user_id'=> $datarow->id])}}" userid="{{$datarow->id}}"><i class="flaticon-plus"></i> Change Password</a>
                                    @endif

                                    {{-- @if(get_user_permission('admin_users','UpdatePermission'))
                                    <a class="dropdown-item" href="{{url('admin/admin_users/update_permission/'.$datarow->id)}}"><i class="flaticon-pencil-1"></i> Update Permission </a>
                                    @endif --}}
                                   
                                    @if(get_user_permission('admin_users','d'))
                                    <a class="dropdown-item" data-role="unlink"
                                    data-message="Do you want to remove this Admin user?"
                                    href="{{ url('admin/admin_users/' . $datarow->id) }}"><i
                                        class="flaticon-delete-1"></i> Delete</a>
                                    @endif
                                    
                                </div>
                            </div>
                        </td>
                        <td>{{$datarow->first_name}} {{$datarow->last_name}}</td>
                        <td>{{$datarow->email}}</td>
                        <td>{{$datarow->user_role->role ?? '-'}}</td>
                        <td>{{$datarow->last_login ? web_date_in_timezone($datarow->last_login,'d-m-Y h:i A') : "N/A"}}</td>
                        <td>{{web_date_in_timezone($datarow->created_at,'d-m-Y h:i A')}}</td>
                        <td>
                        @if(get_user_permission('admin_users','u'))
                            <label class="switch s-icons s-outline  s-outline-warning  mb-2 mt-2 mr-2">
                                        <input type="checkbox" class="change_status" data-id="{{ $datarow->id }}"
                                            data-url="{{ url('admin/admin_users/change_status') }}"
                                            @if ($datarow->active) checked @endif>
                                        <span class="slider round"></span>
                            </label>
                            @else
                                {{$datarow->active ? 'Yes' : 'No'}}
                            @endif
                        </td>
                        
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
@stop

@section("script")


<script src="{{asset('')}}admin-assets/plugins/table/datatable/datatables.js"></script>


<script>


 // --- Get the disableSortingColumnsIndex from the server side ----
 var disableSortingColumnsIndex = <?php echo json_encode($disableSortingColumnsIndex);?>
    // us requcer to forma the array of object {"targets": 0, "orderable": false}
    var disableSortingColumns = disableSortingColumnsIndex.map(function(index) {
        return {
            "targets": index,
            "orderable": false
        }
    });

    // ----------------------------------------------------------------

    // ready the order
    var sortIndex = <?php echo request()->get('sort_index') ?? 0; ?>;
    var sortOrder = `<?php echo request()->get('sort_order') ?? 'asc'; ?>`;


    // Disable the table sorting rows, as we are sorting from the backend
    $.fn.dataTable.ext.order['disableSort'] = function(settings, col) {
        return []; // Return an empty array to effectively disable sorting
    };

    var table = $('#example2').DataTable({
        "paging": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "responsive": true,
        "order": [
            [sortIndex, sortOrder]
        ], // Default sort order
        "columnDefs": [...disableSortingColumns, { "targets": '_all', "orderDataType": 'disableSort'}]
    });

    // Handle the order event
    table.on('order.dt', function(e) {

        // Get the order details
        var order = table.order();
        var columnIndex = order[0][0]; // Column index
        var sortDirection = order[0][1]; // 'asc' or 'desc'

        // Get the column name from the header
        var columnName = table.column(columnIndex).header().textContent.trim();

        // Build the new URL with the query parameters
        var currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('sort_index', columnIndex);
        currentUrl.searchParams.set('sort_order', sortDirection);
        currentUrl.searchParams.set('page', 1); // Reset the page number to 1

        // Redirect to the new URL
        window.location.href = currentUrl.toString();

    });
    
    </script>
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

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: $form.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                timeout: 600000,
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
                            var m = res['message'];
                            App.alert(m, 'Oops!');
                        }
                    } else {
                        App.alert(res['message']);
                        setTimeout(function() {
                            window.location.href = App.siteUrl('/admin/admin_users');
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