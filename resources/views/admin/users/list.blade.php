@extends('admin.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section('content')

<?php $permission_id = "all_users"; ?>
<div class="card mb-5">
    <?php /* if(get_user_permission({{$permission_id}},'c')) { */ ?>
    <?php /* } */ ?>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Details</th>
                        <th>Account Type</th>
                        <th>Active</th>
                        <th>Created at</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($users as $user)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="ml-3">
                                    <a href="#" class="yellow-color">{{$user->first_name}} {{$user->last_name}}</a>
                                    <div>{{$user->email}}</div>
                                    <div>+{{$user->dial_code}} {{$user->phone}}</div>
                                </span>

                            </div>
                        </td>
                        <td>{{ account_type($user->user_type_id) }}</td>

                        <td>
                            <label class="switch s-icons s-outline  s-outline-warning mt-2 mb-2 mr-2">
                                <input type="checkbox" class="change_status" data-id="{{ $user->id }}" data-url="{{ url('admin/category/change_status') }}" @if ($user->active) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>{{web_date_in_timezone($user->created_at,'d-m-Y h:i A')}}</td>

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

    // --------- On delete facility item -------

    // Handle record delete
    $('body').off('click', '.delete_item');
    $('body').on('click', '.delete_item', function(e) {
        e.preventDefault();
        var msg = $(this).data('message') || 'Are you sure that you want to delete this record?';
        var href = "{{route('vendor.facilities.delete')}}";

        const facilityId = $(this).attr("data-facilityId");

        App.confirm('Confirm Delete', msg, function() {
            // Gather form data
            var formData = {
                "_token": "{{ csrf_token() }}",
                "facilityGroupId": facilityId
                // Add other form fields as needed
            };

            // Perform AJAX request
            var ajxReq = $.ajax({
                url: href,
                type: 'post',
                dataType: 'json',
                data: formData, // Pass form data to the request
                success: function(res) {
                    if (res['status'] == 1) {
                        App.alert(res['message'] || 'Deleted successfully', 'Success!');

                        setTimeout(function() {

                            // Move to location
                            window.location.href = "{{ route('vendor.facilities.index') }}";

                        }, 1500);


                    } else {
                        App.alert(res['message'] || 'Unable to delete the record.', 'Failed!');
                    }
                },
                error: function(jqXhr, textStatus, errorMessage) {
                    // Handle error if needed
                }
            });
        });


    });


    // -----------------------------------------



    // --- Get the disableSortingColumnsIndex from the server side ----
    var disableSortingColumnsIndex = <?php echo json_encode($disableSortingColumnsIndex); ?>
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

        console.log("disableSort", settings, col);
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

    console.log("table", {
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
@stop