@extends('admin.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
<style>
    a.yellow-text {
        color: white;
    }
    .home-section .container-fluid {
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
</style>
@stop

@section('content')
<div class="card mb-5">
    @if(get_user_permission('masters_app_banners','c'))
    <div class="card-header">
        <a href="{{ route('admin.app_banners.create') }}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Add Banner</a>
    </div>
    @endif
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-bordered table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Action</th>
                        <th>Banner Image</th>
                        <th>Is Active</th>
                        <th>Created Date</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($banners as $banner)
                    <?php $i++; 
                    ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td class="text-center action">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7">
                                    @if(get_user_permission('masters_app_banners','u'))
                                    <a class="dropdown-item" href="{{ route('admin.app_banners.edit', ['id'=> $banner->id]) }}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif
                                    @if(get_user_permission('masters_app_banners','d'))
                                    <a class="dropdown-item cstm_lst_delete_itm" data-userid="dummy" data-itemid="{{$banner->id}}" data-csrf="{{ csrf_token() }}" data-redirect="{{ route('admin.app_banners.index') }}" href="{{ route('admin.app_banners.delete') }}"><i class="flaticon-delete-1"></i> Delete</a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span>
                                    @if ($banner->banner_image != '')
                                    <img id="image-preview" style="width:100px; height:90px;" class="img-responsive mb-2" data-image="{{ $banner->banner_image}}" src="{{ $banner->banner_image }}">
                                    @endif
                                </span>
                                <span class="ml-2">
                                    <a class="yellow-text">{{ $banner->name }}</a>

                                </span>
                            </div>
                        </td>

                        <td>
                        @if(get_user_permission('masters_app_banners','u'))
                            <label class="switch s-icons s-outline  s-outline-warning mt-2 mb-2 mr-2">
                                <input type="checkbox" class="change_status" data-id="{{ $banner->id }}" data-url="{{ route('admin.app_banners.change_status') }}" @if ($banner->active) checked @endif>
                                <span class="slider round"></span>
                            </label>
                            @else
                            {{$banner->active ? 'Yes' : 'No'}}
                            @endif
                        </td>
                        <td>{{web_date_in_timezone($banner->created_at,'d-m-Y h:i A')}}</td>


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
  var table =  $('#example2').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
        "columnDefs": [
        {
            "targets": [1],
            "orderable": false
        }
    ]
    });

    // Custom search function to filter the "Workstation" column specifically
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var searchTerm = $('#example2_filter input').val().toLowerCase();
            
            if (!searchTerm) return true; // If no search term is entered, show all rows

            // Columns index on which the search are allowed
            var columnsIndex = [2]; // "Workstation" column is the 2

            // loop through the columns index and search on that column
            for (var i = 0; i < columnsIndex.length; i++) {
                var columnIndex = columnsIndex[i];
                var columnValue = data[columnIndex].toLowerCase();

                if (columnValue.includes(searchTerm)) return true;
            }

        }
    );
</script>
@stop