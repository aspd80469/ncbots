
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Nigripes BOT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="NC AI BOT" name="description" />
        <meta content="NC AI BOT" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

		<!-- App css -->
		<link href="{{ asset('assets/css/config/default/bootstrap.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
		<link href="{{ asset('assets/css/config/default/app.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

		<link href="{{ asset('assets/css/config/default/bootstrap-dark.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
		<link href="{{ asset('assets/css/config/default/app-dark.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />

		<!-- icons -->
		<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />

    </head>

    <body class="loading auth-fluid-pages pb-0">

        <div class="auth-fluid">
            <!--Auth fluid left content -->
            <div class="auth-fluid-form-box">
                <div class="align-items-center d-flex h-100">
                    <div class="card-body">

                        <!-- Logo -->
                        <div class="auth-brand text-center text-lg-start">
                            <div class="auth-logo">
                                <a href="index.html" class="logo logo-dark text-center">
                                    <span class="logo-lg">
                                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="22">
                                    </span>
                                </a>
            
                                <a href="index.html" class="logo logo-light text-center">
                                    <span class="logo-lg">
                                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="22">
                                    </span>
                                </a>
                            </div>
                        </div>

                        <!-- 警告訊息-->
                        @if( Session::has('alert-danger') | Session::has('alert-warning') | Session::has('alert-success') | Session::has('alert-info')  )
                        <div class="row">
                            <div class="col-md-12 my-2">
                                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                <div class="alert alert-{{ $msg }} alert-dismissible bg-{{ $msg }} text-white border-0 fade show" role="alert">
                                    {{ Session::get('alert-' . $msg) }}
                                </div>
                                @endif
                                @endforeach
                                @if( $errors->any() && $errors->first() != "")
                                <div class="alert alert-danger alert-dismissible bg-info text-white border-0 fade show" role="alert">
                                {!! $errors->first() !!}
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif<!-- 警告訊息-->

                        <!-- title-->
                        <h4 class="mt-0">登入</h4>

                        <!-- form -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control" type="email" id="email" name="email" required="" placeholder="請輸入email" required autofocus>

                                @if( $errors->has('email') )
								<span class="help-block" style="color: red;">
								<strong>請確認帳號是否有誤</strong>
								</span>
								@endif

                            </div>
                            <div class="mb-3">
                                <a href="{{ route('password.request') }}" class="text-muted float-end"><small>忘記密碼?</small></a>
                                <label for="password" class="form-label">密碼</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="請輸入密碼" autocomplete="current-password">
                                    <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>

                                    @if( $errors->has('password') )
                                    <span class="help-block" style="color: red;">
                                    <strong>請確認密碼是否有誤</strong>
                                    </span>
                                    @endif

                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">

                            </div>
                            <div class="text-center d-grid">
                                <button class="btn btn-blue waves-effect waves-light" type="submit">登入</button>
                            </div>
                        </form>
                        <!-- end form-->

                        <!-- Footer-->
                        <footer class="footer footer-alt">
                            <p class="text-muted"><a href="{{ route('register') }}" class="ms-1"><b>註冊帳號</b></a></p>
                        </footer>

                    </div> <!-- end .card-body -->
                </div> <!-- end .align-items-center.d-flex.h-100-->
            </div>
            <!-- end auth-fluid-form-box-->

            <!-- Auth fluid right content -->
            <div class="auth-fluid-right text-center">
                <div class="auth-user-testimonial">
                    <a href='https://www.freepik.com/vectors/abstract'>Abstract vector created by starline</a>
                </div> <!-- end auth-user-testimonial-->
            </div>
            <!-- end Auth fluid right content -->
        </div>
        <!-- end auth-fluid-->

        <!-- Vendor js -->
        <script src="{{ asset('assets/js/vendor.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
        
    </body>
</html>