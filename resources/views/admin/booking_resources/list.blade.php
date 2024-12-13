@extends("admin.template.layout")

@section("header")
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section("content")
<div class="card mb-5">
    @if(get_user_permission('masters_booking_resources','c'))
    <div class="card-header"><a href="{{route('admin.bookingresource.create')}}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Add Workstation</a></div>
    @endif
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-bordered table-striped table-bordered" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Action</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach($items as $item)
                    <?php $i++ ?>

                    <tr>
                        <td width="15%">{{$i}}</td>
                        <td width="20%" class="text-left action">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7">
                                    @if(get_user_permission('masters_booking_resources','u'))
                                    <a class="dropdown-item" href="{{route('admin.bookingresource.edit', ['bookingresource'=> $item->id])}}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif
                                    @if(get_user_permission('masters_booking_resources','d'))
                                    <a class="dropdown-item cstm_lst_delete_itm" data-userid="dummy" data-itemid="{{$item->id}}" data-csrf="{{ csrf_token() }}" data-redirect="{{ route('admin.bookingresource.index') }}" href="{{ route('admin.bookingresource.delete') }}"><i class="flaticon-delete-1"></i> Delete</a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{$item->name}}</td>
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
    var table = $('#example2').DataTable({
        "paging": true,
        "searching": true,
        "ordering": false,
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
