
<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <title>NC BOT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="NC AI BOT" name="description" />
        <meta content="NC AI BOT" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

        <!-- Plugins css -->
        <link href="{{ asset('assets/libs/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
        
        <!-- App css -->
        <link href="{{ asset('assets/css/config/default/bootstrap.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
        <link href="{{ asset('assets/css/config/default/app.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

        <link href="{{ asset('assets/css/config/default/bootstrap-dark.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" />
        <link href="{{ asset('assets/css/config/default/app-dark.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" />

        <!-- icons -->
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />

    </head>

    <!-- body start -->
    <body class="loading" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": false}, "topbar": {"color": "dark"}}'>

        <!-- Begin page -->
        <div id="wrapper">

        <!-- Topbar Start -->
        <div class="navbar-custom">
            <div class="container-fluid">
                <ul class="list-unstyled topnav-menu float-end mb-0">
            
                    <li class="dropdown d-inline-block d-lg-none">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fe-search noti-icon"></i>
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end p-0">
                            <form class="p-3">
                                <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                            </form>
                        </div>
                    </li>
                    
                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fe-bell noti-icon"></i>
                            <span class="badge bg-danger rounded-circle noti-icon-badge">1</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-lg">
            
                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="m-0">
                                    <span class="float-end">
                                        <a href="" class="text-dark">
                                            <small>清除</small>
                                        </a>
                                    </span>通知
                                </h5>
                            </div>
            
                            <div class="noti-scroll" data-simplebar>
            
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item active">
                                    <div class="notify-icon">
                                        <img src="{{ asset('assets/images/users/user-1.jpg') }}" class="img-fluid rounded-circle" alt="" /> </div>
                                    <p class="notify-details">Cristina Pride</p>
                                    <p class="text-muted mb-0 user-msg">
                                        <small>Hi, How are you? What about our next meeting</small>
                                    </p>
                                </a>

                            </div>
            
                            <!-- All-->
                            <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">
                                全部
                                <i class="fe-arrow-right"></i>
                            </a>
            
                        </div>
                    </li>
            
                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="pro-user-name ms-1">
                                Geneva <i class="mdi mdi-chevron-down"></i> 
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">您好!</h6>
                            </div>
            
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-user"></i>
                                <span>會員資料</span>
                            </a>
            
                            <div class="dropdown-divider"></div>
            
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fe-log-out"></i>
                                <span>登出</span>
                            </a>
            
                        </div>
                    </li>
            
                </ul>
            
                <!-- LOGO -->
                <div class="logo-box">
                    <a href="index.html" class="logo logo-dark text-center">
                        <span class="logo-sm">
                            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                            <!-- <span class="logo-lg-text-light">UBold</span> -->
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="20">
                            <!-- <span class="logo-lg-text-light">U</span> -->
                        </span>
                    </a>
            
                    <a href="index.html" class="logo logo-light text-center">
                        <span class="logo-sm">
                            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="20">
                        </span>
                    </a>
                </div>
            
                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="fe-menu"></i>
                        </button>
                    </li>

                    <li>
                        <!-- Mobile menu toggle (Horizontal Layout)-->
                        <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>   
                    
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu">

            <div class="h-100" data-simplebar>

                <!--- Sidemenu -->
                <div id="sidebar-menu">

                    <ul id="side-menu">

                        <li class="menu-title">會員選單</li>

                        <li>
                            <a href="{{ url('myBots') }}" class="active">
                                <i class="fas fa-cat"></i>
                                <span> 我的機器人 </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('userPlans') }}" class="active">
                                <i class="fas fa-feather"></i>
                                <span> 會員方案 </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('userPlanRecords') }}" class="active">
                                <i class="fas fa-list-alt"></i>
                                <span> 付費紀錄 </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('riskNotice') }}" class="active">
                                <i class="fas fa-bong"></i>
                                <span> 風險聲明 </span>
                            </a>
                        </li>

                       

                        <li class="menu-title">管理選單</li>

                        <li>
                            <a href="{{ url('mge/users') }}">
                                <i class="fa fa-users"></i>
                                <span> 會員列表 </span>
                            </a>

                        </li>

                        <li>
                            <a href="{{ url('mge/orders') }}">
                                <i class="fas fa-bolt"></i>
                                <span> 會員下單 </span>
                            </a>

                        </li>

                        <li>
                            <a href="{{ url('mge/plans') }}">
                                <i class="fas fa-grip-vertical"></i>
                                <span> 會員方案 </span>
                            </a>

                        </li>

                        <li>
                            <a href="{{ url('mge/manualOrders') }}">
                                <i class="fas fa-broom"></i>
                                <span> 手動補單 </span>
                            </a>

                        </li>

                        <li>
                            <a href="{{ url('mge/signals') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                <span> 指標訊號 </span>
                            </a>

                        </li>

                        <li>
                            <a href="#sidebarTickets" data-bs-toggle="collapse" class="" aria-expanded="false">
                                <i class="fe-settings"></i>系統設定
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarTickets" style="">
                                <ul class="nav-second-level">

                                    <li>
                                        <a href="{{ url('mge/settings') }}">參數設定</a>
                                    </li>

                                    <li>
                                        <a href="{{ url('mge/managers') }}">管理帳號</a>
                                    </li>

                                    <li>
                                        <a href="{{ url('mge/userPlans') }}">方案管理</a>
                                    </li>

                                    <li>
                                        <a href="{{ url('mge/riskNotice') }}">風險聲明</a>
                                    </li>

                                    <li>
                                        <a href="{{ url('mge/sysSignals') }}">訊號Token</a>
                                    </li>

                                    <li>
                                        <a href="{{ url('mge/sysSignalLogs') }}">訊號紀錄</a>
                                    </li>

                                    <li>
                                        <a href="{{ url('mge/sysLogs') }}">系統紀錄</a>
                                    </li>

                                </ul>
                            </div>
                        </li>


                        

                        <li>
                            <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                              <i class="fe-log-out"></i> <span>登出</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>    
                        </li>

                    </ul>

                </div>
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

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

                    <!-- 內容-->
                    @yield('content')<!-- 內容-->
                                            
                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>2021 - document.write(new Date().getFullYear())</script> &copy; <a href="">NC AI BOT</a> 
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-sm-block">

                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

        <!-- Plugins js-->
        <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
        <script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>

        <!-- Dashboar 1 init js-->
        <script src="{{ asset('assets/js/pages/dashboard-1.init.js') }}"></script>

        <!-- App js-->
        <script src="{{ asset('assets/js/app.min.js') }}"></script>
        
    </body>
</html>