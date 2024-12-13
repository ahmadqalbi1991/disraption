@extends('vendor.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section('content')

<?php $permission_id = "facility_group"; ?>
<div class="card mb-5">
    <?php /* if(get_user_permission({{$permission_id}},'c')) { */ ?>
    <div class="card-header">
        <a href="{{ route('vendor.facilities.create') }}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Create Facility Group</a>
    </div>
    <?php /* } */ ?>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <!-- <th>Category Name</th>
                            
                            <th>Image</th> -->
                        <th>Name</th>
                        <th>Description</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($facilityGroups as $facility_group)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span>
                                    {{ $facility_group->name }}
                                </span>
                            </div>
                        </td>
                        <td>{{ $facility_group->description }}</td>


                        <td class="text-center">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink7">
                                    <?php /* <!-- @if(get_user_permission({{$permission_id}},'u')) --> */ ?>
                                    <a class="dropdown-item" href="{{ route('vendor.facilities.edit', ['id' => $facility_group->id]) }}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    <?php /* <!-- @endif  --> */ ?>
                                    <?php /*  <!-- @if(get_user_permission({{$permission_id}},'d')) --> */ ?>
                                    <a class="dropdown-item delete_item" data-facilityId="{{$facility_group->id}}" href="#"><i class="flaticon-delete-1"></i> Delete</a>
                                    <?php /*  <!-- @endif        --> */ ?>
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