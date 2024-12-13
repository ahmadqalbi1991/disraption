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
    @if(get_user_permission('masters_country','c'))
    <div class="card-header"><a href="{{url('admin/country/create')}}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Create Country</a></div>
    @endif
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-condensed table-bordered  table-striped" id="example2">
            <thead>
                <tr>
                <th>#</th>
                <th>Action</th>
                <th>Name</th>
                <th>Code</th>
                <th>Dial Code</th>
                <th>Status</th>
                <th>Created Date</th>
                
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach($countries as $country) 
                    <?php $i++ ?>
                    <tr>
                        <td>{{$i}}</td>
                        <td class="text-center action">
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7">
                                    @if(get_user_permission('masters_country','u'))
                                    <a class="dropdown-item" href="{{url('admin/country/'.$country->id.'/edit')}}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif
                                    @if(get_user_permission('masters_country','d'))
                                    <a class="dropdown-item" data-role="unlink"
                                    data-message="Do you want to remove this country?"
                                    href="{{ url('admin/country/' . $country->id) }}"><i
                                        class="flaticon-delete-1"></i> Delete</a>
                                        @endif 
                                </div>
                            </div>
                        </td>
                        <td>{{$country->name}}</td>
                        <td>{{$country->prefix}}</td>
                        <td>{{$country->dial_code}}</td>
                        <td>@if($country->active) Active @else Inactive @endif</td>
                        <td>{{web_date_in_timezone($country->created_at,'d-m-Y h:i A')}}</td>
                        
                        
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
$('#example2').DataTable({
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

     // Custom search function to filter the column specifically
     $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var searchTerm = $('#example2_filter input').val().toLowerCase();

            if (!searchTerm) return true; // If no search term is entered, show all rows

            // Columns index on which the search are allowed
            var columnsIndex = [2, 3, 4]; // "name, code, dialcode

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