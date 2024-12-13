@extends('admin.template.layout')

@section('header')
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{ asset('') }}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section('content')

<?php $permission_id = "customer_ratings"; ?>
<div class="card mb-5">

    @if(!isset($_GET['reporting']) || $_GET['reporting'] != 'true' and get_user_permission($permission_id,'c'))
    <div class="card-header">
        <a href="{{ route('admin.customer.ratings.create') }}" class="btn-custom btn mr-2 mt-2 mb-2"><i class="fa-solid fa-plus"></i> Add New Rating</a>
    </div>
    @endif

    <div class="card-body">

        <form action="" method="get" id="filterform">
            <input type="hidden" name="reporting" value="{{ request()->get('reporting') }}">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Customer Email</label>
                    <input type="email" name="customer_email" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('customer_email') }}">
                </div>
                <div class="col-md-3 form-group">
                    <label>Artist Email</label>
                    <input type="email" name="vendor_email" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{ request()->get('vendor_email') }}">
                </div>
              
                <div class="col-md-3 form-group">
                    <label>Rating</label>
                    <select name="rating" class="form-control form-control-sm">
                        <option value="">All</option>
                        @for($i=1; $i<=5; $i++)
                        <option value="{{ $i }}" {{ request()->get('rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3 form-group">
                    <label class="w-100">From Date:</label>
                        <input type="text" name="from_date" class="form-control form-control-sm flatpickr-input no-future-date" placeholder="" aria-controls="column-filter" value="{{ request()->get('from_date') }}">
                    
                </div>
                <div class="col-md-3 form-group">
                    <label class="w-100">To Date:</label>
                        <input type="text" name="to_date" class="form-control form-control-sm flatpickr-input no-future-date" placeholder="" aria-controls="column-filter" value="{{ request()->get('to_date') }}">
                    
                </div>

            


        
                <div class="col-md-4 mt-4">


                    <button type="submit" class="btn btn-primary fltr-btn">Filter</button>
                    <button id="reset" type="button" class="btn btn-primary ml-2 fltr_form_reset">Reset</button>
                    @if(isset($_GET['reporting']) && $_GET['reporting'] == 'true')
                    <a type="button" href="#" class="btn btn-primary btn-export fltr_form_export ml-2">Export</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">Action</th>
                        <th>Customer Details</th>
                        <th>Artist Details</th>
                        <th>Star</th>
                        <th>Review</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = $ratings->perPage() * ($ratings->currentPage() - 1); ?>
                    @foreach ($ratings as $rating)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>
                        <td class="text-center action">
                            <div class="dropdown custom-dropdown custom-dropdown-long">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="flaticon-dot-three"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuLink7">

                                    @if (get_user_permission($permission_id,'u'))
                                    <a class="dropdown-item" href="{{ route('admin.customer.ratings.edit', ['id'=> $rating->id]) }}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif

                                    @if (get_user_permission($permission_id,'d'))
                                    <a class="dropdown-item" data-role="unlink" data-message="Do you want to remove this review?" href="{{ route('admin.customer.ratings.delete', ['id'=>$rating->id]) }}"><i class="flaticon-delete-1"></i> Delete</a>
                                    @endif

                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="ml-3">
                                    <a class="yellow-color">{{$rating->customer->first_name}} {{$rating->customer->last_name}}</a>
                                    <div>{{$rating->customer->email}}</div>
                                    <div>+{{$rating->customer->dial_code}} {{$rating->customer->phone}}</div>
                                </span>

                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <span class="ml-3">
                                    <a class="yellow-color">{{$rating->vendor->first_name}} {{$rating->vendor->last_name}}</a>
                                    <div>{{$rating->vendor->email}}</div>
                                    <div>+{{$rating->vendor->dial_code}} {{$rating->vendor->phone}}</div>
                                </span>

                            </div>
                        </td>
                        <td>{{ $rating->rating }}</td>

                        <td>{{ substr($rating->review, 0, 50) }}</td>

                        <td>{{web_date_in_timezone($rating->created_at,'d-m-Y h:i A')}}</td>

                    </tr>

                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <span>Total {{ $ratings->total() }} entries</span>
                <div class="col-sm-12 col-md-12 pull-right">
                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        {!! $ratings->appends(request()->input())->links('admin.template.pagination') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script src="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.js"></script>
<script>
    App.initFormView();

    $('#example2').DataTable({
        "paging": false,
        "searching": false,
        "ordering": false,
        "info": false,
        "autoWidth": true,
        "responsive": true,
    });
</script>
@stop