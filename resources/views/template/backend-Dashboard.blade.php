<?php
// $_current_user = \Request::get('_current_user');
// $userid = Auth::user()->id;
// if ($userid > 1) {
//     $privileges = \App\Models\UserPrivileges::privilege();
//     $privileges = json_decode($privileges, true);
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>{{ config('global.site_name') }} | Admin</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('') }}admin-assets/assets/img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('') }}admin-assets/assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('') }}admin-assets/assets/img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('') }}admin-assets/assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{ asset('') }}admin-assets/assets/img/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('') }}admin-assets/assets/img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'> -->
    <link href="{{ asset('') }}admin-assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="{{ asset('') }}admin-assets/assets/css/sidebar.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/solid.min.css" integrity="sha512-EvFBzDO3WBynQTyPJI+wLCiV0DFXzOazn6aoy/bGjuIhGCZFh8ObhV/nVgDgL0HZYC34D89REh6DOfeJEWMwgg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" integrity="sha512-MQXduO8IQnJVq1qmySpN87QQkiR1bZHtorbJBD0tzy7/0U9+YIC93QWHeGTEoojMVHWWNkoCp8V6OzVSYrX0oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}admin-assets/assets/css/custom.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}admin-assets/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}admin-assets/assets/css/parsley.css" rel="stylesheet" type="text/css" />
    
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    @yield('header')
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

</head>

<!-- <body class="default-sidebar"> -->

<!--<body class="dark"-->
<!--    style="background: url('{{ asset('') }}admin-assets/assets/img/bg-1920x1080.jpg'); background-size: cover; background-position: center; background-repeat: inherit;">-->

<body class="" style="background: #222222 !important">

    <!-- Tab Mobile View Header -->
    <header class="tabMobileView header navbar fixed-top d-lg-none d-none">
        <div class="nav-toggle">
            <a href="javascript:void(0);" class="nav-link sidebarCollapse" data-placement="bottom">
                <i class="flaticon-menu-line-2"></i>
            </a>
            <a href="{{ url('/') }}" class=""> <img src="{{ asset('') }}admin-assets/assets/img/logo.svg" class="img-fluid" alt="logo"></a>
        </div>
        <ul class="nav navbar-nav">
            <li class="nav-item d-lg-none">
                <form class="form-inline justify-content-end" role="search">
                    <input type="text" class="form-control search-form-control mr-3">
                </form>
            </li>
        </ul>
    </header>
    <!-- Tab Mobile View Header -->

    <!--  BEGIN NAVBAR  -->
    <header class="header navbar fixed-top navbar-expand-sm d-none">

        <ul class="navbar-nav flex-row ml-lg-auto">

            <li class="nav-item dropdown user-profile-dropdown ml-lg-0 mr-lg-2 ml-3 order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="flaticon-user-12"></span>
                </a>
                <div class="dropdown-menu  position-absolute" aria-labelledby="userProfileDropdown">
                    {{-- <a class="dropdown-item" href="#">
                        <i class="mr-1 flaticon-user-6"></i> <span>My Profile</span>
                    </a> --}}
                    <a class="dropdown-item" href="{{ url('admin/change_password') }}">
                        <i class="mr-1 flaticon-key-2"></i> <span>Change Password</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ url('admin/logout') }}">
                        <i class="mr-1 flaticon-power-button"></i> <span>Log Out</span>
                    </a>
                </div>
            </li>

            <li class="nav-item dropdown cs-toggle order-lg-0 order-3" style="display:none;">
                <a href="#" class="nav-link toggle-control-sidebar suffle">
                    <span class="flaticon-menu-dot-fill d-lg-inline-block d-none"></span>
                    <span class="flaticon-dots d-lg-none"></span>
                </a>
            </li>
        </ul>
    </header>
    <!--  END NAVBAR  -->




    <div class="sidebar">
        <div class="logo-details mt-2">
            <a href="#">
                <i class='bx bx-menu'></i>
            </a>
            <!-- <i class='bx bxl-c-plus-plus'></i> -->
            <!-- <img src="{{ asset('') }}admin-assets/assets/img/moda-icon.png" class="img-fluid" alt="">
            <span class="logo_name">MODA</span> -->
        </div>
        @yield('sidebar_ul')
       
    </div>


    <section class="home-section">
        <!-- <div class="home-content"> -->
        <!-- <div class="container-fluid">
        <i class='bx bx-menu' ></i>

      </div> -->
        <div class=" mb-2 container-fluid  mt-4">
            <div class="p-3 custom-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <!-- <div class="home-content">
                        <i class='bx bx-menu'></i>
                      </div> -->
                        <a href="{{ url('admin/dashboard') }}" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                            <img src="{{ asset('') }}admin-assets/assets/img/logo.svg" class="img-fluid brand-logo" alt="">
                        </a>
                    </div>
    
                    <div class="text-end d-flex align-items-center header-end">
                        <!-- <div class="dropdown">
                        <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="profile-name">Hi, Adam</span>
                        <img src="assets/img/profile-icon.png" alt="mdo" width="32" height="32" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu text-small">
                        <li><a class="dropdown-item" href="#">Dashboard</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Log out</a></li>
                        </ul>
                    </div> -->
    
                      
                    @yield('right_bar_dropdown')
                       
                    </div>
                </div>
            </div>
            
        </div>
        </div>

        <div class="container-fluid">
            <div class="page-header">
                <div class="page-title">
                    <h3>{{ $page_heading ?? '' }}</h3>
                    <div class="crumbs">
                        <ul id="breadcrumbs" class="breadcrumb">
                            <li><a href="{{ url('admin/dashboard') }}"><i class="flaticon-home-fill"></i></a>
                            </li>
                            <li><a>{{ $page_heading ?? '' }}</a>
                            </li>
                            <?php if (isset($mode)) { ?>
                                <li class="active"><a href="#">{{ $mode ?? '' }}</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            @yield('content')
        </div>

        @yield('after_content')

        <footer class="container-fluid text-center">
            <!-- Copyright -->
            <div class="text-center p-3">
                <p class="bottom-footer mb-0 text-white">&#xA9; {{ date('Y') }} <a target="_blank" class="text-white" href="#">{{ config('global.site_name') }}</a></p>
            </div>

            <!-- Copyright -->
        </footer>

        @yield('footer')
    </section>






    <!-- Modal -->
    <div class="modal fade" id="changepassword" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change password</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                    <form method="post" id="admin-form" action="{{ url('admin/change_user_password') }}" enctype="multipart/form-data" data-parsley-validate="true">
                        @csrf()
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="hidden" name="id" value="" id="userid">
                                    <div class="input-group mb-3">

                                        <input id="password" type="password" class="form-control jqv-input" data-jqv-required="true" data-parsley-required-message="Enter Password" data-parsley-minlength="8" required autocomplete="off" name="password">

                                        <div class="input-group-append" style="cursor: pointer;position: relative;box-sizing: border-box;height: auto;color: #fff;font-size: 16px;border: none;background: transparent;">
                                            <span class="input-group-text" onclick="password_show_hide();">
                                                <i class="fas fa-eye d-none" id="show_eye"></i>
                                                <i class="fas fa-eye-slash" id="hide_eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Re-enter Password</label>
                                    <div class="input-group mb-3">

                                        <input id="new_password" type="password" class="form-control jqv-input" data-parsley-minlength="8" data-parsley-equalto="#password" data-parsley-required-message="Please re-enter password." autocomplete="off" required name="new_pswd" data-parsley-trigger="keyup" data-parsley-equalto-message="Both passwords should be the same">

                                        <div class="input-group-append" style="cursor: pointer;position: relative;box-sizing: border-box;height: auto;color: #fff;font-size: 16px;border: none;background: transparent;">
                                            <span class="input-group-text" onclick="new_password_show_hide();">
                                                <i class="fas fa-eye d-none" id="show_eye_new"></i>
                                                <i class="fas fa-eye-slash" id="hide_eye_new"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Change</button>
                                </div>
                            </div>
                        </div>





                    </form>
                </div>

            </div>

        </div>
    </div>
    <style>
        .modal_loader {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, .8) url('https://i.stack.imgur.com/FhHRx.gif') 50% 50% no-repeat;
        }

        /* When the body has the loading class, we turn
    the scrollbar off with overflow:hidden */
        body.my-loading .modal_loader {
            overflow: hidden;
        }

        /* Anytime the body has the loading class, our
    modal element will be visible */
        body.my-loading .modal_loader {
            display: block;
        }

        .custom-file-label {
            overflow: hidden;
            white-space: nowrap;
            padding-right: 6em;
            text-overflow: ellipsis;
        }

        #image_crop_section {
            max-width: 100% !important;
        }

        /* General Datepicker Styles */
.datepicker {
    background-color: black;
    color: white;
    border-radius: 4px;
    padding: 10px;
}

/* Datepicker Header Styles */
.datepicker .datepicker-days .datepicker-switch,
.datepicker .datepicker-months .datepicker-switch,
.datepicker .datepicker-years .datepicker-switch {
    background-color: black;
    color: white;
    font-weight: bold;
}

/* Datepicker Days */
.datepicker .table-condensed td,
.datepicker .table-condensed th {
    color: white;
    font-weight: normal;
    padding: 5px;
}

/* Highlight Today's Date */
.datepicker .table-condensed .today {
    background-color: #ff6600; /* Example highlight color */
    color: white;
}

/* Highlight Selected Date */
.datepicker .table-condensed .active {
    background-color: #007bff;
    color: white;
}

/* Disabled Dates */
.datepicker .table-condensed .disabled,
.datepicker .table-condensed .disabled:hover {
    background-color: #333;
    color: #666;
    cursor: not-allowed;
}

/* Change Datepicker Input Size */
.datepicker input {
    width: 100%; /* Adjust width as needed */
    padding: 8px;
    font-size: 16px;
    background-color: black;
    color: white;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Arrow Styles */
.datepicker .prev,
.datepicker .next {
    color: white;
}

/* Custom Hover for Dates */
.datepicker .table-condensed td:hover {
    background-color: #555;
    color: white;
}

    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('') }}admin-assets/assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="{{ asset('') }}admin-assets/bootstrap/js/popper.min.js"></script>
    <script src="{{ asset('') }}admin-assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ asset('') }}admin-assets/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- <script src="{{asset('custom_js/')}}/jquery-validation/jquery.validate.min.js"></script> --}}
    <script src="{{ asset('admin-assets/assets/js/app.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/js/all.min.js" integrity="sha512-8pHNiqTlsrRjVD4A/3va++W1sMbUHwWxxRPWNyVlql3T+Hgfd81Qc6FC5WMXDC+tSauxxzp1tgiAvSKFu1qIlA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/js/solid.min.js" integrity="sha512-LKdDHe5ZhpmiH6Kd6crBCESKkS6ryNpGRoBjGeh5mM/BW3NRN4WH8pyd7lHgQTTHQm5nhu0M+UQclYQalQzJnw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>

    <script type="text/javascript">
        today = "<?php echo date('Y-m-d'); ?>";
        jQuery(function() {
            App2.init();
            App.init({
                site_url: '{{ url(' / ') }}',
                base_url: '{{ url(' / ') }}',
                site_name: '{{ config('
                global.site_name ') }}',
            });
            App.toast([]);

            App.initTreeView();
        });
        $('.date').datepicker({
            orientation: "bottom auto",
            todayHighlight: true,
            format: "yyyy-mm-dd",
            autoclose: true,
        });

        window.Parsley.addValidator('fileextension', {
            validateString: function(value, requirement) {
                var fileExtension = value.split('.').pop();
                extns = requirement.split(',');
                if (extns.indexOf(fileExtension.toLowerCase()) == -1) {
                    return fileExtension === requirement;
                }
            },
        });
        window.Parsley.addValidator('maxFileSize', {
            validateString: function(_value, maxSize, parsleyInstance) {
                var files = parsleyInstance.$element[0].files;
                return files.length != 1 || files[0].size <= maxSize * 1024;
            },
            requirementType: 'integer',
        });

        window.Parsley.addValidator('lt', {
            validateString: function(value, requirement) {
                return parseFloat(value) < parseRequirement(requirement);
            },
            priority: 32
        });
        var parseRequirement = function(requirement) {
            if (isNaN(+requirement))
                return parseFloat(jQuery(requirement).val());
            else
                return +requirement;
        };

        window.Parsley.addValidator('lte', {
            validateString: function(value, requirement) {
                return parseFloat(value) <= parseRequirement(requirement);
            },
            priority: 32
        });
        window.Parsley.addValidator('imagedimensions', {
            requirementType: 'string',
            validateString: function(value, requirement, parsleyInstance) {
                let file = parsleyInstance.$element[0].files[0];
                let [width, height] = requirement.split('x');
                let image = new Image();
                let deferred = $.Deferred();

                image.src = window.URL.createObjectURL(file);
                image.onload = function() {
                    if (image.width == width && image.height == height) {
                        deferred.resolve();
                    } else {
                        deferred.reject();
                    }
                };

                return deferred.promise();
            },
            messages: {
                en: 'Image dimensions should be  %spx'
            }
        });

        window.Parsley.addValidator('dategttoday', {
            validateString: function(value) {
                if (value !== '') {
                    return Date.parse(value) >= Date.parse(today);
                }
                return true;
            },
            messages: {
                en: 'Date should be equal or greater than today'
            }
        });


        // Handle record delete
        $('body').off('click', '[data-role="unlink"]');
        $('body').on('click', '[data-role="unlink"]', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to delete this record?';
            var href = $(this).attr('href');

            App.confirm('Confirm Delete', msg, function() {
                var ajxReq = $.ajax({
                    url: href,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        if (res['status'] == 1) {
                            App.alert(res['message'] || 'Deleted successfully', 'Success!');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);

                        } else {
                            App.alert(res['message'] || 'Unable to delete the record.',
                                'Failed!');
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {

                    }
                });
            });

        });

        $('body').off('click', '[data-role="approve"]');
        $('body').on('click', '[data-role="approve"]', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to approve this record?';
            var href = $(this).attr('href');
            var title = $(this).data('title') || 'Confirm Approve';

            App.confirm(title, msg, function() {
                var ajxReq = $.ajax({
                    url: href,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        if (res['status'] == 1) {
                            App.alert(res['message'] || 'Approved successfully', 'Success!');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);

                        } else {
                            App.alert(res['message'] || 'Unable to approve the record.',
                                'Failed!');
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {

                    }
                });
            });

        });
        $('body').off('click', '[data-role="reject"]');
        $('body').on('click', '[data-role="reject"]', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to reject this record?';
            var href = $(this).attr('href');
            var title = $(this).data('title') || 'Confirm Reject';

            App.confirm(title, msg, function() {
                var ajxReq = $.ajax({
                    url: href,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        if (res['status'] == 1) {
                            App.alert(res['message'] || 'Rejected successfully', 'Success!');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);

                        } else {
                            App.alert(res['message'] || 'Unable to reject the record.',
                                'Failed!');
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {

                    }
                });
            });

        });

        $(document).on('change', '.custom-file-input', function() {
            var file = $(this)[0].files[0].name;
            $(this).next('.custom-file-label').html(file);
        });

        $('body').off('click', '[data-role="verify"]');
        $('body').on('click', '[data-role="verify"]', function(e) {
            e.preventDefault();
            var href = $(this).attr('url');

            $.ajax({
                url: href,
                type: 'POST',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    if (res['status'] == 1) {
                        App.alert(res['message'] || 'Verified successfully', 'Success!');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);

                    } else {
                        App.alert(res['message'] || 'Unable to verify the record.', 'Failed!');
                    }
                },
                error: function(jqXhr, textStatus, errorMessage) {

                }
            });

        });
        $(".change_status").change(function() {
            status = 0;
            if (this.checked) {
                status = 1;
            }

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: $(this).data('url'),
                data: {
                    "id": $(this).data('id'),
                    'status': status,
                    "_token": "{{ csrf_token() }}"
                },
                timeout: 600000,
                dataType: 'json',
                success: function(res) {
                    App.loading(false);

                    if (res['status'] == 0) {
                        var m = res['message']
                        App.alert(m, 'Oops!');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        App.alert(res['message']);
                    }
                },
                error: function(e) {
                    App.alert(e.responseText, 'Oops!');
                }
            });
        });
        $(document).on('keyup', 'input[type="text"],textarea', function() {
            _name = $(this).attr("name");
            _type = $(this).attr("type");
            if (_name == "email" || _name == "r_email" || _name == "password" || _type == "email" || _type ==
                "password" || $(this).hasClass("no-caps")) {
                return false;
            }
            txt = $(this).val();
            //$(this).val(txt.substr(0,1).toUpperCase()+txt.substr(1));
        });
        // Load Provinces on Country Change
        $('body').off('change', '[data-role="country-change"]');
        $('body').on('change', '[data-role="country-change"]', function() {
            var $t = $(this);
            var $dialcode = $('#' + $t.data('input-dial-code'));
            var $state = $('#' + $t.data('input-state'));

            if ($dialcode.length > 0) {
                var code = $t.find('option:selected').data('phone-code');
                console.log(code)
                if (code == '') {
                    $dialcode.val('');
                } else {
                    $dialcode.val(code);
                }
            }

            if ($state.length > 0) {

                var id = $t.val();
                var html = '<option value="">Select</option>';
                $state.html(html);
                $state.trigger('change');

                if (id != '') {
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "{{ url('admin/states/get_by_country') }}",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}"
                        },
                        timeout: 600000,
                        dataType: 'json',
                        success: function(res) {
                            for (var i = 0; i < res['states'].length; i++) {
                                html += '<option value="' + res['states'][i]['id'] + '">' + res[
                                    'states'][i]['name'] + '</option>';
                                if (i == res['states'].length - 1) {
                                    $state.html(html);
                                    // $('.selectpicker').selectpicker('refresh')
                                }
                            }
                        }
                    });
                }
            }
        });
        $('body').off('change', '[data-role="state-change"]');
        $('body').on('change', '[data-role="state-change"]', function() {
            var $t = $(this);
            var $city = $('#' + $t.data('input-city'));

            if ($city.length > 0) {
                var id = $t.val();
                var html = '<option value="">Select</option>';

                $city.html(html);
                if (id != '') {
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "{{ url('admin/cities/get_by_state') }}",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}"
                        },
                        timeout: 600000,
                        dataType: 'json',
                        success: function(res) {
                            for (var i = 0; i < res['cities'].length; i++) {
                                html += '<option value="' + res['cities'][i]['id'] + '">' + res[
                                    'cities'][i]['name'] + '</option>';
                                if (i == res['cities'].length - 1) {
                                    $city.html(html);
                                    // $('.selectpicker').selectpicker('refresh')
                                }
                            }
                        }
                    });
                }

            }
        });
        $('body').off('change', '[data-role="vendor-change"]');
        $('body').on('change', '[data-role="vendor-change"]', function() {
            var $t = $(this);
            var $city = $('#' + $t.data('input-store'));

            if ($city.length > 0) {
                var id = $t.val();
                var html = '<option value="">Select</option>';

                $city.html(html);
                if (id != '') {
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: "{{ url('admin/store/get_by_vendor') }}",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}"
                        },
                        timeout: 600000,
                        dataType: 'json',
                        success: function(res) {
                            for (var i = 0; i < res['stores'].length; i++) {
                                html += '<option value="' + res['stores'][i]['id'] + '">' + res[
                                    'stores'][i]['store_name'] + '</option>';
                                if (i == res['stores'].length - 1) {
                                    $city.html(html);
                                    // $('.selectpicker').selectpicker('refresh')
                                }
                            }
                        }
                    });
                }

            }
        });
        $('body').off('click', '[data-role="change_password"]');
        $('body').on('click', '[data-role="change_password"]', function(e) {
            var userid = $(this).attr('userid');
            $('#userid').val(userid);
            $('#changepassword').modal('show');
        });





        $(".flatpickr-input").flatpickr({
            enableTime: false,
            dateFormat: "d-m-Y"
        });

        // no future date .flatpickr-input.no-future-date
        $(".flatpickr-input.no-future-date").flatpickr({
            enableTime: false,
            dateFormat: "d-m-Y",
            maxDate: "today"
        });
        

        $(".flatpickr-input-wtime").flatpickr({
            enableTime: true,
             dateFormat: "Y-m-d H:i",
        });
        

        let arrow = document.querySelectorAll(".arrow");
        for (var i = 0; i < arrow.length; i++) {
            arrow[i].addEventListener("click", (e) => {
                let arrowParent = e.target.parentElement.parentElement; //selecting main parent of arrow
                arrowParent.classList.toggle("showMenu");
            });
        }
        
        
        let homesection = document.querySelector(".home-section");
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");
        console.log(sidebarBtn);
        sidebarBtn.addEventListener("click", () => {
            sidebar.classList.toggle("close");
            homesection.classList.toggle("active");
        });
        
        
        

        $(".progress-bar-1").css('width', '30%');
        $(".progress-bar-2").css('width', '70%');


        // const body = document.querySelector("body"),
        //     modeSwitch = body.querySelector(".toggle-switch"),
        //     modeText = body.querySelector(".mode-text");
        // modeSwitch.addEventListener("click", () => {
        //     body.classList.toggle("dark");

        //     if (body.classList.contains("dark")) {
        //         modeText.innerText = "Light mode";
        //     } else {
        //         modeText.innerText = "Dark mode";

        //     }
        // });
    </script>
    <script>
        function password_show_hide() {
            var x = document.getElementById("password");
            var show_eye = document.getElementById("show_eye");
            var hide_eye = document.getElementById("hide_eye");
            show_eye.classList.remove("d-none");
            if (x.type === "password") {
                x.type = "text";
                hide_eye.style.display = "none";
                show_eye.style.display = "block";
            } else {
                x.type = "password";
                hide_eye.style.display = "block";
                show_eye.style.display = "none";
            }
        }
    </script>
    <script>
        function new_password_show_hide() {
            var x = document.getElementById("new_password");
            var show_eye = document.getElementById("show_eye_new");
            var hide_eye = document.getElementById("hide_eye_new");
            show_eye.classList.remove("d-none");
            if (x.type === "password") {
                x.type = "text";
                hide_eye.style.display = "none";
                show_eye.style.display = "block";
            } else {
                x.type = "password";
                hide_eye.style.display = "block";
                show_eye.style.display = "none";
            }
        }
    </script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <script>
        $('form input').attr('autocomplete','off');
        $('form').attr('autocomplete','off');
    </script>
    
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    @yield('script')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
</body>

</html>