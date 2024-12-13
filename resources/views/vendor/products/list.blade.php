@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section('content')

<?php $permission_id = "products"; ?>
<div class="card mb-5">
    <?php /* if(get_user_permission({{$permission_id}},'c')) { */ ?>
    <div class="card-header">
        <a href="{{ route(route_name_admin_vendor($type, 'products.create'), ['user_id' => $user_id, 'type' => $type]) }}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Add New Product</a>
    </div>
    <?php /* } */ ?>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Is Active</th>
                        <th>Created Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($products as $product)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span>
                                <img id="image-preview" style="width:100px; height:90px;" class="img-responsive" src="{{ get_uploaded_image_url($product->image, 'products') }}">  {{ $product->name }}
                                </span>
                            </div>
                        </td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->category->name }}</td>

                        <td>
                            <label class="switch s-icons s-outline  s-outline-warning mt-2 mb-2 mr-2">
                                <input type="checkbox" class="change_status" data-id="{{ $product->id }}" data-url="{{ route(route_name_admin_vendor($type, 'products.change_status'), ['user_id' => $user_id, 'type' => $type]) }}" @if ($product->active) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>{{web_date_in_timezone($product->created_at,'d-m-y h:i A')}}</td>

                        <td class="text-center">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink7">
                                    <?php /* <!-- @if(get_user_permission({{$permission_id}},'u')) --> */ ?>
                                    <a class="dropdown-item" href="{{ route(route_name_admin_vendor($type, 'products.edit'), ['user_id' => $user_id, 'id' => $product->id, 'type' => $type]) }}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    <?php /* <!-- @endif  --> */ ?>
                                    <?php /*  <!-- @if(get_user_permission({{$permission_id}},'d')) --> */ ?>
                                    <a class="dropdown-item cstm_lst_delete_itm" data-userid="{{$user_id}}" data-itemid="{{$product->id}}" data-csrf="{{ csrf_token() }}" data-redirect="{{ route(route_name_admin_vendor($type, 'products.index'), ['user_id' => $user_id, 'type' => $type]) }}" href="{{ route(route_name_admin_vendor($type, 'products.delete')) }}"><i class="flaticon-delete-1"></i> Delete</a>
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