<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>{{env('APP_NAME')}} | Admin Login </title>
    <!--<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-touch-icon.png">-->
    <!--<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('') }}admin-assets/assets/img/favicon/favicon-32x32.png">-->
    <!--<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('') }}admin-assets/assets/img/favicon/favicon-16x16.png">-->
    <!--<link rel="manifest" href="{{ asset('') }}admin-assets/assets/img/favicon/site.webmanifest">-->
    <!--<link rel="mask-icon" href="{{ asset('') }}admin-assets/assets/img/favicon/safari-pinned-tab.svg" color="#ac772b">-->
    <!--<meta name="msapplication-TileColor" content="#da532c">-->
    
    
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('') }}admin-assets/assets/img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('') }}admin-assets/assets/img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('') }}admin-assets/assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('') }}admin-assets/assets/img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('') }}admin-assets/assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{ asset('') }}admin-assets/assets/img/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('') }}admin-assets/assets/img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    
    <meta name="theme-color" content="#ffffff">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
    <link href="{{ asset('admin-assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-assets/assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin-assets/assets/css/users/login-3.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/plugins/jqvalidation/custom-jqBootstrapValidation.css') }}">
    <link href="{{ asset('admin-assets/plugins/notification/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style type="text/css">
        .invalid-feedback{
            color: red;
            display: block;
        }
        .form-login .btn{
            /*margin-top: 30px;*/
                border-radius: 10px !important;
                height: 50px;
        }
        /* Change the white to any color */
        /*input:-webkit-autofill,*/
        /*input:-webkit-autofill:hover, */
        /*input:-webkit-autofill:focus, */
        /*input:-webkit-autofill:active{*/
        /*    -webkit-box-shadow: 0 0 0 30px white inset !important;*/
        /*}*/
    </style>

</head>
<body class="login" style="background: #222222; background-size: cover; background-position: center; background-repeat: no-repeat;">
<!--<body class="login" style="background: #301934; background-size: cover; background-position: center; background-repeat: no-repeat;">-->
    <!--<body class="login" style="background-size: cover;background-position: center;background-repeat: no-repeat;background: hsla(42, 97%, 53%, 1);background: linear-gradient(0deg, hsla(42, 97%, 53%, 1) 0%, hsla(358, 72%, 45%, 1) 100%, hsla(237, 64%, 56%, 1) 100%);background: -moz-linear-gradient(0deg, hsla(42, 97%, 53%, 1) 0%, hsla(358, 72%, 45%, 1) 100%, hsla(237, 64%, 56%, 1) 100%);background: -webkit-linear-gradient(0deg, hsla(42, 97%, 53%, 1) 0%, hsla(358, 72%, 45%, 1) 100%, hsla(237, 64%, 56%, 1) 100%);filter: progid: DXImageTransform.Microsoft.gradient( startColorstr=&quot;#FCB813&quot;, endColorstr=&quot;#C52026&quot;, GradientType=1 );">-->

    <form method="POST" class="form-login" action="{{ route('admin.check_login') }}">
        @csrf
        <input type="hidden" name="admin" value="1">
        <div class="row">
            <div class="col-md-12 text-center mb-4">
                <img alt="logo" src="{{ asset('') }}admin-assets/assets/img/logo.svg" style="height: 30px;" class="theme-logo">
            </div>
            <div class="col-md-12">

                <div>
                    <label for="inputEmail" class="text-muted">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ is_null(old('email')) ? 'admin@admin.com': old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <label for="inputPassword" class="text-muted">Password</label>
                {{-- <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"> --}}
                <div class="input-group mb-3">
                                      
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    <div style="cursor: pointer; position: absolute; box-sizing: border-box; height: auto; color: #fff; font-size: 16px; border: none; background: transparent; margin-bottom: 15px !important; right: 14px; margin-top: 13px; z-index: 9">
                        <span  style="border-radius: 0px 10px 10px 0px !important;background: transparent;border-left: 0;border: 0;color: #EA33C7;" onclick="password_show_hide();">
                          <i class="fas fa-eye d-none" id="show_eye"></i>
                          <i class="fas fa-eye-slash" id="hide_eye"></i>
                        </span>
                      </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <button type="submit" class="btn btn-gradient-primary btn-rounded btn-block d-flex">Sign in</button>
            </div>

        </div>
    </form>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('admin-assets/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/jqvalidation/jqBootstrapValidation-1.3.7.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
    <script src="{{ asset('admin-assets/plugins/notification/toastr/toastr.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/js/all.min.js" integrity="sha512-8pHNiqTlsrRjVD4A/3va++W1sMbUHwWxxRPWNyVlql3T+Hgfd81Qc6FC5WMXDC+tSauxxzp1tgiAvSKFu1qIlA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Toaster options
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "rtl": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": 300,
            "hideDuration": 1000,
            "timeOut": 2000,
            "extendedTimeOut": 1000,
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        $(document).ready(function() {
        @if (\Session::has('error') && \Session::get('error') != null)
            toastr["error"]("{{\Session::get('error')}}");
        @endif

        })
        $(".form-login").submit(function(e) {
            e.preventDefault();

            const email = $("#email").val();
            const password = $("#password").val();

            if (email == "") {
                toastr["error"]("Please enter email address");
                return false;
            } else if (password == "") {
                toastr["error"]("Please enter password");
                return false;
            }

            $.ajax({
                    type:'POST',
                    url: "{{ route("admin.check_login")}}",
                    data:{
                        '_token': $('input[name=_token]').val(),
                        'email': $("#email").val(),
                        'password': $("#password").val(),
                        'timezone': Intl.DateTimeFormat().resolvedOptions().timeZone
                    },
                    success: function(response) {
                        if(response.success){
                            toastr["success"](response.message);
                            setTimeout(function(){
                                window.location.href = "{{ route("admin.dashboard")}}";
                            }, 1000);

                        } else {
                            toastr["error"](response.message);
                        }
                    }
                });
        });
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
        $('form input').attr('autocomplete','off');
        $('form').attr('autocomplete','off');
    </script>

    <!-- END GLOBAL MANDATORY SCRIPTS -->
</body>
</html>
