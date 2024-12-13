@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout') 

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">

@stop 


@section('content')
<div class="card mb-5">
    <div class="card-header">
        <a href="{{url('admin/vendors/yatch_offers_create')}}" class="btn-custom btn mr-2 mt-2 mb-2 "><i class="fa-solid fa-plus"></i> Add Offers</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped align-middle" style="align-content: center;" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Offer title</th>
                        <th>Offer Percentage</th>
                        <th>Offer Price</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <img src="{{ asset('') }}admin-assets/assets/img/offer-img.png" class="img-fluid" width="70" />
                        </td>
                        <th>
                            30% Discount on a Special Day
                        </th>
                        <td>
                            30%
                        </td>
                        <td>
                            350
                        </td>
                        <td>
                            <div class="dropdown custom-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink7">
                                    <a class="dropdown-item" href="#!"><iclass="flaticon-edit"></i> Edit</a>
                                    <a class="dropdown-item" href="#!"> <iclass="flaticon-delete-1"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
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