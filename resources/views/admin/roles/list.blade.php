@extends('admin.template.layout')

@section('content')
<style>
    .card.mb-5, .mb-5 .card, .order-detail-page .card{
        overflow: visible !important;
    }
</style>
<div class="card mb-5">
  @if(get_user_permission('user_roles','c'))

  <div class="card-header">
    <a href="{{route('admin.user_roles.create')}}" class="btn-custom btn mr-2 mt-2 mb-2"><i
        class="fa-solid fa-plus"></i> Create</a>
  </div>
  @endif
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-condensed table-bordered table-striped" id="example2">
        <thead>
          <tr>
            <th data-colname="id">#</th>
            <th data-colname="action">Action</th>
            <th data-colname="role">Role Name</th>
            <th data-colname="role">Users Count</th>
            <th data-colname="status">Status</th>
            <th data-colname="created_at">Created on</th>
            
          </tr>
        </thead>
        <tbody>
          @foreach ($roles as $role)
          <tr>
            <td>{{ $loop->index + 1 + ($roles->perPage() * ($roles->currentPage() - 1)) }}</td>
            <td class="action">
              <div class="dropdown custom-dropdown">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="true">
                  <i class="flaticon-dot-three"></i>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink7" style="top: 0 !important;">
                  @if (get_user_permission('user_roles', 'u'))
                  <a class="dropdown-item" href="{{ route('admin.user_roles.edit', ['id' => encrypt($role->id)]) }}">
                    <i class="flaticon-pencil-1"></i> Edit
                  </a>
                  @endif

                  @if (get_user_permission('user_roles', 'd'))
                  <a class="dropdown-item" data-role="unlink"
                    data-message="Do you want to remove the role? Make sure all users will be removed related to this role!"
                    href="{{ route('admin.user_roles.delete', ['id' => encrypt($role->id)]) }}">
                    <i class="flaticon-delete-1"></i> Delete
                  </a>
                  @endif
                </div>
              </div>
            </td>
            <td>{{ $role->role }}</td>
            <td>{{ $role->count }}</td>
            <td>{{ $role->getStatusText() }}</td>
            <td>{{ $role->created_at }}</td>
            
          </tr>
          @endforeach
        </tbody>
      </table>

      <div class="col-sm-12 col-md-12 pull-right">
        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
          {!! $roles->appends(request()->all())->links('admin.template.pagination') !!}
        </div>
      </div>

    </div>
  </div>
</div>
@stop
@section('script')
<script src="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.js"></script>
<script>
  jQuery(document).ready(function(){

      App.initTreeView();

      $('#example2').DataTable({
      "paging": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
        "columnDefs": [
            {
                "targets": [0, 1],
                "orderable": false
            },
        //     {
        //     "targets": [5], // Date columns
        //     "type": 'date', // Ensure these columns are treated as dates
        //     "render": function(data, type, row, meta) {
        //         if (type === 'sort') {

        //             // Convert date to timestamp for sorting
        //             return new Date(data).getTime();
        //         }
        //         return data;
        //     }
        // }
            //{ "targets": '_all', "orderDataType": 'disableSort'}
        ]
    });

  })
</script>
@stop