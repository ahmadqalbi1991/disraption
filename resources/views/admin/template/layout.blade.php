@extends('template.backend-Dashboard')

@section('header')
<link href="{{ asset('') }}admin-assets/assets/css/support-chat.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('') }}admin-assets/plugins/maps/vector/jvector/jquery-jvectormap-2.0.3.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('') }}admin-assets/plugins/charts/chartist/chartist.css" rel="stylesheet" type="text/css">
<link href="{{ asset('') }}admin-assets/assets/css/default-dashboard/style.css" rel="stylesheet" type="text/css" />

@stop


@section('sidebar_ul')


<ul class="nav-links">


    <li>
        <div class="iocn-link">
            <a href="{{route('admin.dashboard')}}">
                <i class='bx bx-tachometer'></i>
                <span class="link_name">Dashboard</span>
            </a>

        </div>

    </li>




    @if(get_user_permission('admin_users','r') || get_user_permission('user_roles','r'))
    <li class="showMenu">

        <div class="iocn-link">
            <a href="#">
                <i class='bx bx-user'></i>
                <span class="link_name">Admin Users</span>
            </a>
            <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
            <li>
                <a class="link_name" href="#">Admin Users</a>
            </li>
            @if(get_user_permission('admin_users','r'))
            <li>
                <a href="{{route('admin.admin_users.index')}}">Admin Users</a>
            </li>
            @endif
            @if(get_user_permission('user_roles','r'))
            <li>
                <a href="{{ route('admin.user_roles.list') }}"> User Roles </a>
            </li>
            @endif
        </ul>
    </li>
    @endif

    @if(get_user_permission('customers','r') || get_user_permission('vendor','r'))
    <li class="showMenu">
        <div class="iocn-link">
            <a href="#">
                <i class='bx bx-user-circle'></i>
                <span class="link_name">Users</span>
            </a>
            <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">

            @if(get_user_permission('customers','r'))
            <li>
                <a href="{{ url('admin/customers') }}">Customers </a>
            </li>
            @endif

            @if(get_user_permission('vendors','r'))
            <li>
                <a href="{{ url('admin/artist') }}"> Artists </a>
            </li>
            @endif

        </ul>
    </li>
    @endif

    @if(get_user_permission('masters_country','r') || get_user_permission('masters_category','r'))
    <li class="showMenu">
        <div class="iocn-link">
            <a href="#">
                <i class='bx bx-cube'></i>
                <span class="link_name">Masters</span>
            </a>
            <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
            @if(get_user_permission('masters_country','r'))
            <li>
                <a href="{{ url('admin/country') }}">Countries </a>
            </li>
            @endif

            @if(get_user_permission('masters_category','r'))
            <li>
                <a href="{{ url('admin/category') }}">Categories </a>
            </li>
            @endif

            @if(get_user_permission('masters_app_banners','r'))
            <li>
                <a href="{{ route('admin.app_banners.index') }}">App Banners</a>
            </li>
            @endif

            @if(get_user_permission('masters_booking_resources','u'))
            <li>
                <a href="{{ route('admin.bookingresource.index') }}"> Workstations </a>
            </li>
            @endif


        </ul>
    </li>
    @endif


    @if(get_user_permission('vendors_booking','r') || get_user_permission('customers_booking_order','r'))
    <li class="showMenu">
        <div class="iocn-link">
            <a href="#">
                <i class='bx bxs-book-content'></i>
                <span class="link_name">Bookings</span>
            </a>
            <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">

            @if(get_user_permission('vendors_booking','r'))
            <li>
                <a href="{{route('admin.artist-booking.index', ['type' => 'admin', 'user_id'=> 'all'])}}">Artist Bookings </a>
            </li>
            @endif



        </ul>
    </li>
    @endif




    @if(get_user_permission('vendor_ratings','r') || get_user_permission('customer_ratings','r'))
    <li class="showMenu d-none">
        <div class="iocn-link">
            <a href="#">
                <i class='bx bx-file'></i>
                <span class="link_name">Ratings</span>
            </a>
            <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">

            @if(get_user_permission('vendor_ratings','r'))
            <li>
                <a href="{{route('admin.ratings.index')}}">Artist Ratings</a>
            </li>
            @endif

            @if(get_user_permission('customer_ratings','r'))
            <li>
                <a href="{{route('admin.customer.ratings.index')}}">Customer Ratings</a>
            </li>
            @endif
        </ul>
    </li>
    @endif



    @if(get_user_permission('cms_pages','r'))
    <li class="showMenu">
        <div class="iocn-link">
            <a href="#">
                <i class='bx bx-file'></i>
                <span class="link_name">Cms Pages</span>
            </a>
            <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">

            @if(get_user_permission('cms_pages','r'))
            <li>
                <a href="{{route('admin.cms_pages')}}">Cms Pages</a>
            </li>
            @endif

            @if(get_user_permission('settings','u'))
            <li>
                <a href="{{ route('admin.settings') }}"> Settings </a>
            </li>
            @endif

            @if(get_user_permission('cms_rechedule_policy','u'))
            <li>
                <a href="{{ route('admin.reschedule_policy.view') }}"> Reschedule Policy </a>
            </li>
            @endif

            @if(get_user_permission('cms_cancellation_policy','u'))
            <li>
                <a href="{{ route('admin.cancellation.view') }}"> Cancellation Policy </a>
            </li>
            @endif

            @if(get_user_permission('cms_location','u'))
            <li>
                <a href="{{ route('admin.location.view') }}"> Location </a>
            </li>
            @endif


        </ul>
    </li>
    @endif


    @if(get_user_permission('reporting_vendors','r') || get_user_permission('reporting_customers','r') || get_user_permission('reporting_vendors_booking','r'))
    <li class="showMenu">
        <div class="iocn-link">
            <a href="#">
                <i class='bx bxs-report'></i>
                <span class="link_name">Reports</span>
            </a>
            <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
            <li>
                <a class="link_name" href="#">Reports</a>
            </li>


            @if(get_user_permission('reporting_vendors','r'))
            <li>
                <a href="{{ route('admin.artist', ['reporting'=> 'true']) }}">Artists</a>
            </li>
            @endif
            @if(get_user_permission('reporting_customers','r'))
            <li>
                <a href="{{ route('admin.customers.index', ['reporting'=> 'true']) }}">Customers</a>
            </li>
            @endif

            @if(get_user_permission('tax_report','r'))
            <li>
                <a href="{{route('admin.tax_report.index', ['type' => 'admin', 'user_id'=> 'all', 'reporting'=> 'true'])}}">Tax Report</a>
            </li>
            @endif

            @if(get_user_permission('reporting_vendors_booking','r'))
            <li>
                <a href="{{route('admin.artist-booking.index', ['type' => 'admin', 'user_id'=> 'all', 'reporting'=> 'true'])}}">Artist Bookings</a>
            </li>
            @endif
            @if(get_user_permission('reporting_vendors_rating','r'))
            <li>
                <a href="{{route('admin.ratings.index', ['reporting'=> 'true'])}}">Artist Ratings</a>
            </li>
            @endif

            @if(get_user_permission('contact_us_entries','u'))
            <li>
                <a href="{{ route('admin.contact_us.index') }}"> Contact us Entries</a>
            </li>
            @endif


        </ul>
    </li>
    @endif

</ul>
@stop


@section('right_bar_dropdown')
<div class="dropdown">
    <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="profile-name">Hi, Admin</span>
        <img src="{{ asset('') }}admin-assets/assets/img/profile-icon.svg" alt="mdo" width="32" height="32" class="rounded-circle">
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{ url('admin/dashboard') }}"><i class='bx bx-grid-alt'></i> Dashboard</a>
        <a class="dropdown-item" href="{{ url('admin/change_password') }}"><i class='bx bxs-key'></i> Change Password</a>
        <a class="dropdown-item" href="{{ url('admin/logout') }}"><i class='bx bx-log-out'></i>
            Log Out</a>
    </div>
</div>
@stop

<!--<style>-->
<!--    .home-section footer {-->
<!--        bottom: auto !important;-->
<!--    }-->

<!--    .table>tbody>tr>td {-->
<!--        white-space: nowrap;-->
<!--    }-->
<!--</style>-->

<!--<div class="row">-->
<!--    <div class="col-12">-->
<!--        @section('content')-->
<!--        @yield('content')-->
<!--        @stop-->
<!--    </div>-->
<!--</div>-->


@section('footer')
<script src="{{asset('')}}admin-assets/plugins/charts/chartist/chartist.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0-rc"></script>
@stop
