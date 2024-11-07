<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

<title>Login - Not Barber Shop</title>

<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300&display=swap" rel="stylesheet">

</head>
<body class="account-page">

    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">
                <div class="login-content">
                    <div class="login-userset">
                            {{-- <img src="{{ asset('assets/images/barbericon.png') }}" style="width: 60%; height:60%; margin-left:80px" alt=""> --}}
                        <div class="login-userheading">
                            <h1 style="font-family: 'Oswald', sans-serif;">Not Barber Shop Admin Panel</h1>
                            <hr>
                            <h3 style="font-family: 'Oswald', sans-serif;">Sign In</h3>
                            {{-- <h4>Please login to your account</h4> --}}
                        </div>
                        @if($errors->any())
                            <div class="text-danger">
                                {{$errors->first('email')}}
                            </div>
                        @endif
                        <form action="{{route('authenticate')}}" method="POST">
                            @csrf

                            <div class="form-login">
                                <label>Email or Phone</label>
                                <div class="form-addons">
                                    <input type="text" placeholder="Enter your email or phone" name="email">
                                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/mail.svg" alt="img">
                                </div>
                            </div>

                            <div class="form-login">
                                <label>Password</label>
                                <div class="pass-group">
                                    <input type="password" class="pass-input" placeholder="Enter your password" name="password">
                                    <span class="fas toggle-password fa-eye-slash"></span>
                                </div>
                            </div>
                            <div class="form-login">
                                <input class="btn btn-login" value="Login" type="submit">
                            </div>
                        </form>
                    </div>
                </div>
            <div class="login-img">
                <img src="{{ asset('assets/images/barbershop.jpg') }}" alt="img">
            </div>
        </div>
    </div>


    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

    <script src="{{ asset('assets/js/feather.min.js') }}"></script>

    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    {{-- <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script> --}}

    <script src="{{ asset('assets/js/moment.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}"></script>

    <script src="{{ asset('assets/js/script.js') }}"></script>

</html>
