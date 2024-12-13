@extends('admin.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section('content')
<div class="card mb-5">
    @if(get_user_permission('addon_categories','c'))
    <div class="card-header">
        <a href="{{ url('admin/addon-category/create') }}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Create Category</a>
    </div>
    @endif
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <!-- <th>Category Name</th>
                            
                            <th>Image</th> -->
                        <th>Category Details</th>
                        <th>Parent</th>
                        <th>Is Active</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($categories as $category)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td class="action">
                            <div class="d-flex align-items-center">
                                <span>
                                    @if ($category->image != '')
                                    <img id="image-preview" style="width:100px; height:90px;" class="img-responsive mb-2" data-image="{{ asset($category->image) }}" src="{{ asset($category->image) }}">
                                    @endif
                                </span>
                                <span class="ml-2">
                                    <a class="yellow-text">{{ $category->name }}</a>
                                    {{-- <span>{{ $category->parent_name }}</span> --}}
                                </span>
                            </div>
                        </td>
                        <td>{{ $category->parent_name }}</td>
                        <td>
                            <label class="switch s-icons s-outline  s-outline-warning mt-2 mb-2 mr-2">
                                <input type="checkbox" class="change_status" data-id="{{ $category->id }}" data-url="{{ route('admin.addon.category.change_status') }}" @if ($category->active) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>{{web_date_in_timezone($category->created_at,'d-m-Y h:i A')}}</td>
                        <td class="text-center">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7">
                                    @if(get_user_permission('addon_categories','u'))
                                    <a class="dropdown-item" href="{{ route('admin.addon.category.edit', ['id' => $category->id]) }}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif
                                    @if(get_user_permission('addon_categories','d'))
                                        <a class="dropdown-item cstm_lst_delete_itm" data-userid="dummy" data-itemid="{{$category->id}}" data-csrf="{{ csrf_token() }}" data-redirect="{{ route('admin.addon.category') }}" href="{{ route('admin.addon.category.delete') }}"><i class="flaticon-delete-1"></i> Delete</a>
                                    @endif
                                </div>
                            </div>
                        </td>

                    </tr>
                    @foreach ($category->child as $child)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span>
                                    @if ($child->image != '')
                                    <img id="image-preview" style="width:100px; height:90px;" class="img-responsive mb-2" data-image="{{ asset($child->image) }}" src="{{ asset($child->image) }}">
                                    @endif
                                </span>
                                <span class="ml-2">
                                    <a class="yellow-text">{{ $child->name }}</a>
                                    {{-- <span>{{ $child->parent_name }}</span> --}}
                                </span>
                            </div>
                        </td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <label class="switch s-icons s-outline  s-outline-warning mt-2 mb-2 mr-2">
                                <input type="checkbox" class="change_status" data-id="{{ $child->id }}" data-url="{{ route('admin.addon.category.change_status') }}" @if ($child->active) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>{{web_date_in_timezone($child->created_at,'d-m-Y h:i A')}}</td>
                        <td class="text-center">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7">
                                    @if(get_user_permission('addon_categories','u'))
                                    <a class="dropdown-item" href="{{ route('admin.addon.category.edit', ['id' => $child->id]) }}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif
                                    @if(get_user_permission('addon_categories','d'))
                                    <a class="dropdown-item cstm_lst_delete_itm" data-userid="dummy" data-itemid="{{$child->id}}" data-csrf="{{ csrf_token() }}" data-redirect="{{ route('admin.addon.category') }}" href="{{ route('admin.addon.category.delete') }}"><i class="flaticon-delete-1"></i> Delete</a>
                                    @endif
                                </div>
                            </div>
                        </td>

                    </tr>
                    @endforeach
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