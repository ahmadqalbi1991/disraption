@extends($type == 'admin' ? 'admin.template.layout' : 'vendor.template.layout')


@section('content')

@php
    $user_id = $type === "vendor" ? Auth::user()->id : 'all';
@endphp

<style>
    .card.rounded {
        border-radius: 15px !important;
        transition: 0.3s all ease-in-out;
    }

    .card.rounded:hover {
        transform: translateY(-5px);
    }

    .avatar {
        height: 3rem;
        width: 3rem
    }

    .avatar-title {
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        background-color: #1f58c7;
        color: #fff;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        font-weight: 500;
        height: 100%;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        width: 100%;
    }

    .font-size-24 {
        font-size: 24px !important;
    }

    .font-size-15 {
        font-size: 15px !important;
    }

    h6.font-size-15 {
        color: #eee;
    }

    .avatar-title.bg-primary-subtle {
        background: #1BD1EA !important;
    }

    .avatar-title.bg-primary {
        background: #b3ebf3 !important;
    }

    .avatar-title.bg-blue {
        background: #1D3466 !important;
    }

    .avatar-title.bg-black {
        background: #000 !important;
    }
</style>
<div class="row mt-4">
    <!--<div class="col-xl-3 col-lg-4 col-md-6 mb-4">-->
    <!--        <a href="#!" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 20px;">-->
    <!--        <div class="icon success m-0">-->
    <!--            <p>10</p>-->
    <!--            </div>-->
    <!--         <div class="content m-0">-->
    <!--               <h6 class="mb-0">Total Providers</h6>-->

    <!--        </div>-->
    <!--  </a>-->
    <!--</div>-->

    <!--<div class="col-xl-3 col-lg-4 col-md-6 mb-4">-->
    <!--        <a href="#!" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 20px;">-->
    <!--        <div class="icon purple m-0">-->
    <!--            <p>10</p>-->
    <!--            </div>-->
    <!--         <div class="content m-0">-->
    <!--               <h6 class="mb-0">Active Providers</h6>-->

    <!--        </div>-->
    <!--  </a>-->
    <!--</div>-->

    <!--<div class="col-xl-3 col-lg-4 col-md-6 mb-4">-->
    <!--        <a href="#!" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 20px;">-->
    <!--        <div class="icon primary m-0">-->
    <!--            <p>10</p>-->
    <!--            </div>-->
    <!--         <div class="content m-0">-->
    <!--               <h6 class="mb-0">Number Of Yachts</h6>-->

    <!--        </div>-->
    <!--  </a>-->
    <!--</div>-->
    <!--<div class="col-xl-3 col-lg-4 col-md-6 mb-4">-->
    <!--        <a href="#!" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 20px;">-->
    <!--        <div class="icon orange m-0">-->
    <!--            <p>10</p>-->
    <!--            </div>-->
    <!--         <div class="content m-0">-->
    <!--               <h6 class="mb-0">Number Of Customers</h6>-->

    <!--        </div>-->
    <!--  </a>-->
    <!--</div>-->
    <!--<div class="col-xl-3 col-lg-4 col-md-6 mb-4">-->
    <!--        <a href="#!" class="icon-card height-100 text-center align-items-center" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 20px;">-->
    <!--        <div class="icon orange m-0">-->
    <!--            <p>10</p>-->
    <!--            </div>-->
    <!--         <div class="content m-0">-->
    <!--               <h6 class="mb-0">Total income</h6>-->

    <!--        </div>-->
    <!--  </a>-->
    <!--</div>-->

    @if ($total_artists !== null)

    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{route('admin.artist')}}" class="card rounded overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar">
                        <div class="avatar-title rounded bg-primary">
                            <!--<i class="bx bx-check-shield font-size-24 mb-0 text-white"></i>-->
                            <img src="{{ asset('') }}admin-assets/assets/img/provider.svg" height="24">

                        </div>
                    </div>

                    <div class="flex-grow-1 ml-3">
                        <h6 class="mb-0 font-size-15">Total Artists</h6>
                    </div>
                </div>
                <div>
                    <h4 class="mt-2 pt-1 mb-0 h1 text-white">
                        {{ $total_artists }}
                    </h4>
                </div>
            </div>
        </a>
    </div>

    @endif


    @if ($active_artists !== null)
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4 d-none">
        <a href="{{route('admin.artist')}}" class="card rounded overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar">
                        <div class="avatar-title rounded bg-black">
                            <!--<i class="bx bx-check-shield font-size-24 mb-0 text-white"></i>-->
                            <img src="{{ asset('') }}admin-assets/assets/img/provider-active.svg" style="filter:invert(1);" height="24">
                        </div>
                    </div>

                    <div class="flex-grow-1 ml-3">
                        <h6 class="mb-0 font-size-15">Active Artists</h6>
                    </div>
                </div>
                <div>
                    <h4 class="mt-2 pt-1 mb-0 h1 text-white">
                        {{ $active_artists }}
                    </h4>
                </div>
            </div>
        </a>
    </div>
    @endif

    @if ($total_categories !== null)
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4 d-none">
        <a href="{{route('admin.package.category')}}" class="card rounded overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar">
                        <div class="avatar-title rounded bg-blue">
                            <!--<i class="bx bx-check-shield font-size-24 mb-0 text-white"></i>-->
                            <img src="{{ asset('') }}admin-assets/assets/img/yatch.svg" style="filter:invert(1);" height="24">
                        </div>
                    </div>

                    <div class="flex-grow-1 ml-3">
                        <h6 class="mb-0 font-size-15">Categories</h6>
                    </div>
                </div>
                <div>
                    <h4 class="mt-2 pt-1 mb-0 h1 text-white">
                        {{ $total_categories }}
                    </h4>
                </div>
            </div>
        </a>
    </div>
    @endif

    @if ($total_cms_pages !== null)
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4 d-none">
        <a href="{{route('admin.cms_pages')}}" class="card rounded overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar">
                        <div class="avatar-title rounded bg-black">
                            <!--<i class="bx bx-check-shield font-size-24 mb-0 text-white"></i>-->
                            <img src="{{ asset('') }}admin-assets/assets/img/package.svg" style="filter:invert(1);" height="24">
                        </div>
                    </div>

                    <div class="flex-grow-1 ml-3">
                        <h6 class="mb-0 font-size-15">Total Pages</h6>
                    </div>
                </div>
                <div>
                    <h4 class="mt-2 pt-1 mb-0 h1 text-white">
                        {{ $total_cms_pages }}
                    </h4>
                </div>
            </div>
        </a>
    </div>
    @endif


    @if ($total_customers !== null)
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{route('admin.customers.index')}}" class="card rounded overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar">
                        <div class="avatar-title rounded bg-primary-subtle">
                            <i class="bx bx-user font-size-24 mb-0 text-white"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 ml-3">
                        <h6 class="mb-0 font-size-15">Number Of Customers</h6>
                    </div>
                </div>
                <div>
                    <h4 class="mt-2 pt-1 mb-0 h1 text-white">
                        {{ $total_customers }}
                    </h4>
                </div>
            </div>
        </a>
    </div>
    @endif



    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{route(route_name_admin_vendor($type, 'artist-booking.index'), ['type' => $type, 'user_id'=> $user_id])}}" class="card rounded overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar">
                        <div class="avatar-title rounded bg-warning">
                            <!--<i class="bx bx-check-shield font-size-24 mb-0 text-white"></i>-->
                            <img src="{{ asset('') }}admin-assets/assets/img/booking.svg" height="24">
                        </div>
                    </div>

                    <div class="flex-grow-1 ml-3">
                        <h6 class="mb-0 font-size-15">Completed Bookings</h6>
                    </div>
                </div>
                <div>
                    <h4 class="mt-2 pt-1 mb-0 h1">
                        {{ $total_bookings }}
                    </h4>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <a href="{{route(route_name_admin_vendor($type, 'artist-booking.index'), ['type' => $type, 'user_id'=> $user_id])}}" class="card rounded overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar">
                        <div class="avatar-title rounded bg-success">
                            <!--<i class='bx bx-calendar-plus font-size-24 mb-0 text-white'></i>-->
                            <img src="{{ asset('') }}admin-assets/assets/img/booking-today.svg" style="filter:invert(1);" height="24">
                        </div>
                    </div>

                    <div class="flex-grow-1 ml-3">
                        <h6 class="mb-0 font-size-15">Upcoming Bookings</h6>
                    </div>
                </div>
                <div>
                    <h4 class="mt-2 pt-1 mb-0 h1">
                        {{ $today_bookings }}
                    </h4>
                </div>
            </div>
        </a>
    </div>




</div>

<div class="row">
    <div class="col-lg-6 mb-4 d-none">
        <div class="card">
            <h5 class="card-header">Users</h5>
            <div class="card-body">
                <canvas id="barChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4 d-none">
        <div class="card">
            <h5 class="card-header">Highest Day & Hour</h5>
            <div class="card-body">
                <canvas id="highest-hour-day" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4 d-none">
        <div class="card">
            <h5 class="card-header">Lowest Day & Hour</h5>
            <div class="card-body">
                <canvas id="lowest-hour-day" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4 d-none">
        <div class="card">
            <h5 class="card-header">Profit</h5>
            <div class="card-body">
                <canvas id="profit" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 mb-4">
        <div class="card">
            <h5 class="card-header">Latest Bookings</h5>
            <div class="card-body">
                <style>
                    .table-top-scroll, .table-responsive{width: 100%; border: none;
                    overflow-x: scroll; overflow-y:hidden;}
                    .table-top-scroll{height: 20px;position: sticky;position: -webkit-sticky; top: 0; /* required */ z-index: 3;background: #36454f; }
                    /*.table-responsive{height: 200px; }*/
                    .scroller {height: 20px; }
                    .table {overflow: auto;}
                    body {
                        overflow-x: visible;
                        overflow-y: visible;
                    }
                </style>
                <div class="table-top-scroll mb-1">
                    <div class="scroller">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>

                            <tr>
                                <th>#</th>
                                <th scope="col" class="text-center">Action</th>
                                <th scope="col">Refrence No</th>
                                <th scope="col">Order No</th>
                                <th scope="col">Provider</th>
                                <th scope="col">Name</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Booking Date</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach ($bookings as $booking)
                            <?php $i++; ?>
                            <tr>
                                <th scope="row">{{ $i }}</th>
                                <td class="text-center">

                                    <a href="{{ route(route_name_admin_vendor($type, 'artist-booking.edit'), ['type'=> $type, 'user_id'=>$booking->user->id, 'id'=> $booking->id]) }}" class="btn btn-icon btn-primary"><i class="fa-regular fa-edit"></i></a>


                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="ml-3">
                                            <a class="yellow-color">{{$booking->reference_number}}</a>
                                        </span>

                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="ml-3">
                                            <a class="yellow-color">{{$booking->order_id}}</a>
                                        </span>

                                    </div>
                                </td> 

                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="ml-3">
                                            <a href="{{url('admin/artist/edit/'.$booking->user->id)}}" class="yellow-color">{{$booking->user->name}}</a>
                                            <div>{{$booking->user->email}}</div>
                                        </span>

                                    </div>
                                </td>


                                <td style="max-width: 280px; white-space: break-spaces;">{{ $booking->title }}</td>
                                
                                <td>{{ $booking->total_with_tax}} AED</td>

                                <td>{{web_date_in_timezone($booking->created_at,'d-m-Y h:i A')}}</td>




                            </tr>

                            @endforeach



                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('script')
<script>
    $(function(){
    var widthTable = $(".table-responsive .table").outerWidth();
    $(".table-top-scroll .scroller").css("width", widthTable);
    $(".table-top-scroll").scroll(function(){
        $(".table-responsive")
            .scrollLeft($(".table-top-scroll").scrollLeft());
    });
    $(".table-responsive").scroll(function(){
        $(".table-top-scroll")
            .scrollLeft($(".table-responsive").scrollLeft());
    });
    // console.log(widthTable);
});
</script>
<script>
    var ctx = document.getElementById("barChart").getContext('2d');
    var barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sst", "Sun"],
            datasets: [{
                label: 'Active',
                data: [12, 19, 3, 17, 28, 24, 7],
                backgroundColor: "#1BD1EA"
            }, {
                label: 'Inactive User',
                data: [30, 29, 5, 5, 20, 3, 10],
                backgroundColor: "#1d3466"
            }]
        },

    });
</script>
<script>
    var ctx = document.getElementById("highest-hour-day").getContext('2d');


    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sst", "Sun"],

            datasets: [{
                    label: 'Highest Day', // Name the series
                    data: [30, 29, 5, 5, 20, 3, 10],
                    fill: false,
                    lineTension: 0.2,
                    borderColor: '#DE3163', // Add custom color border (Line)
                    backgroundColor: '#DE3163', // Add custom color background (Points and Fill)
                    borderWidth: 1 // Specify bar border width
                },
                {
                    label: 'Highest Hour',
                    borderColor: '#6495ED', // Add custom color border (Line)
                    backgroundColor: '#6495ED', // Add custom color background (Points and Fill)
                    borderWidth: 1, // Specify bar border width
                    fill: false,
                    data: [10, 20, 3, 2, 15, 2, 5],
                    lineTension: 0.2,

                }
            ]
        },
    });


    var ctx = document.getElementById("lowest-hour-day").getContext('2d');


    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sst", "Sun"],

            datasets: [{
                    label: 'Lowest Day', // Name the series
                    data: [30, 29, 5, 5, 20, 3, 10],
                    fill: false,
                    lineTension: 0.2,
                    borderColor: '#FF5733', // Add custom color border (Line)
                    backgroundColor: '#FF5733', // Add custom color background (Points and Fill)
                    borderWidth: 1 // Specify bar border width
                },
                {
                    label: 'Lowest Hour',
                    borderColor: '#9b51e0', // Add custom color border (Line)
                    backgroundColor: '#9b51e0', // Add custom color background (Points and Fill)
                    borderWidth: 1, // Specify bar border width
                    fill: false,
                    data: [10, 20, 3, 2, 15, 2, 5],
                    lineTension: 0.2,

                }
            ]
        },
    });

    var densityCanvas = document.getElementById("profit");



    var chartOptions = {
        scales: {
            xAxes: [{
                barPercentage: 1,
                categoryPercentage: 0.6
            }],
            yAxes: [{
                id: "y-axis-density"
            }, {
                id: "y-axis-gravity"
            }]
        }
    };

    var barChart = new Chart(densityCanvas, {
        type: 'bar',
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sst", "Sun"],

            datasets: [{
                label: 'Profit', // Name the series
                data: [30, 29, 5, 5, 20, 3, 10],
                fill: false,
                lineTension: 0.2,
                borderRadius: 5,
                borderColor: '#FF5733', // Add custom color border (Line)
                backgroundColor: '#FF5733', // Add custom color background (Points and Fill)
                borderWidth: 1 // Specify bar border width
            }, ]
        },
    });
</script>
@stop