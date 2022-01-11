
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

                        <!-- form -->
                        <form action="{{ route('register') }}">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control" type="email" id="email" required="" placeholder="請輸入Email">

                                @if( $errors->has('email') )
								<span class="help-block">
								<strong>請確認Email格式</strong>
								</span>
								@endif

                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">密碼</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" placeholder="請輸入密碼" required autocomplete="new-password">
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>

                                    @if( $errors->has('password') )
                                    <span class="help-block" style="color: red;">
                                    <strong>請確認密碼輸入是否一致</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">再次輸入密碼</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="請再輸入一次密碼" required autocomplete="new-password">
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>

                                    @if( $errors->has('password_confirmation') )
                                    <span class="help-block" style="color: red;">
                                    <strong>請確認密碼輸入是否一致</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="refCode" class="form-label">推薦碼</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" id="refCode" name="refCode" class="form-control" placeholder="請輸入推薦碼">
                                </div>

                                @if( $errors->has('refCode') )
                                <span class="help-block" style="color: red;">
                                <strong>請輸入推薦碼</strong>
                                </span>
                                @endif

                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="checkbox-signup">
                                    <label class="form-check-label" for="checkbox-signup">我同意 <a href="javascript: void(0);" class="text-dark">風險條款</a>、<a href="javascript: void(0);" class="text-dark">服務條款</a>、<a href="javascript: void(0);" class="text-dark">隱私權政策</a></label>
                                </div>
                            </div>
                            <div class="text-center d-grid">
                                <button class="btn btn-blue waves-effect waves-light" type="submit"> 註冊 </button>
                            </div>
                        </form>
                        <!-- end form-->

                        <!-- Footer-->
                        <footer class="footer footer-alt">
                            <p class="text-muted">已經有帳號? <a href="{{ route('login') }}" class="ms-1"><b>登入</b></a></p>
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