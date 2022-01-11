<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Nigripes BOT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="NC BOT" name="description" />
        <meta content="NC BOT" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

        <!-- App css -->
        <link href="{{ asset('assets/css/config/default/bootstrap.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/config/default/app.css') }}" rel="stylesheet" type="text/css" />

    </head>

    <body class="authentication-bg authentication-bg-pattern">
        <form method="POST" action="{{ url('mge/login') }}">
            @csrf
            
        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-pattern">

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <a href="#" style="color:black;">
                                        <h4>Nigripes BOT | 管理 </h4>
                                    </a>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="account">帳號</label>
                                    <input id="account" type="text" class="form-control{{ $errors->has('account') ? ' is-invalid' : '' }}" name="account" value="{{ old('account') }}" placeholder="請輸入帳號" required autofocus>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">密碼</label>
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="current-password" placeholder="請輸入密碼">

                                </div>

                                <div class="form-group mb-0 text-center">
                                    @if ($errors->has('account') | $errors->has('password'))
                                    <label for="password">
                                            <strong style="color:red;">{{ $errors->first('account') }}{{ $errors->first('password') }}</strong>
                                        </label>
                                    @endif
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-blue btn-block" type="submit"> 登入 </button>
                                </div>


                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">

                        </div>
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        </form>

        <footer class="footer footer-alt">
            2021 - {{ now()->year }} &copy; NC BOT
        </footer>

        <!-- Vendor js -->
        <script src="{{ asset('assets/js/vendor.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
        
    </body>
</html>