@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section('content')

<?php $permission_id = "yatchs"; ?>
<div class="card mb-5">
    <?php /* if(get_user_permission({{$permission_id}},'c')) { */ ?>
    <div class="card-header">
        <a href="{{ route(route_name_admin_vendor($type, 'yatch.create'), ['user_id' => $user_id, 'type' => $type]) }}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Add New Yatch</a>
    </div>
    <?php /* } */ ?>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Capacity</th>
                        <th>Size</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($yachts as $yatch)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span>
                                    {{ $yatch->name }}
                                </span>
                            </div>
                        </td>
                        <td>{{ $yatch->capacity }}</td>
                        <td>{{ $yatch->size }}</td>


                        <td class="text-center">
                            <div class="dropdown custom-dropdown custom-dropdown-long">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink7">
                                    <?php /* <!-- @if(get_user_permission({{$permission_id}},'u')) --> */ ?>
                                    <a class="dropdown-item" href="{{ route(route_name_admin_vendor($type, 'yatch.edit'), ['user_id' => $user_id, 'id' => $yatch->id, 'type' => $type]) }}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    <?php /* <!-- @endif  --> */ ?>
                                    <?php /*  <!-- @if(get_user_permission({{$permission_id}},'d')) --> */ ?>
                                    <a class="dropdown-item cstm_lst_delete_itm" data-userid="{{$user_id}}" data-itemid="{{$yatch->id}}" data-csrf="{{ csrf_token() }}" data-redirect="{{ route(route_name_admin_vendor($type, 'yatch.index'), ['user_id' => $user_id, 'type' => $type]) }}" href="{{ route(route_name_admin_vendor($type, 'yatch.delete')) }}"><i class="flaticon-delete-1"></i> Delete</a>
                                    <?php /*  <!-- @endif        --> */ ?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{route(route_name_admin_vendor($type, 'yacht_rate_schedule.edit'), ['user_id' => $user_id, 'id' => $yatch->id, 'type' => $type])}}">Yatch Schedules</a>
                                    <a class="dropdown-item" href="{{route(route_name_admin_vendor($type, 'yacht_rate.index'), ['user_id' => $user_id, 'id' => $yatch->id, 'type' => $type])}}">Yatch Rates</a>
                                    <a class="dropdown-item" href="{{route(route_name_admin_vendor($type, 'yacht_rate_schedule.edit'), ['user_id' => $user_id, 'id' => $yatch->id, 'type' => $type])}}">Yatch Features</a>
                                    <a class="dropdown-item" href="">Yatch Packages</a>
                                    <a class="dropdown-item" href="{{url('admin/vendors/yatch_offers')}}">Yatch Offers</a>
                                    <a class="dropdown-item" href="{{ route(route_name_admin_vendor($type, 'yacht_booking.create'), ['user_id' => $user_id, 'id' => $yatch->id, 'type' => $type]) }}"><i class="flaticon-pencil-1"></i>Create Booking</a>
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