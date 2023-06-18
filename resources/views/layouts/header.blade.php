
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <title>Accounting Software</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- plugins -->
        <link href="{{ asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
		<!-- App css -->
		<link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
		<link href="{{ asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

		<link href="{{ asset('assets/css/bootstrap-dark.min.css')}}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
		<link href="{{ asset('assets/css/app-dark.min.css')}}" rel="stylesheet" type="text/css" id="app-dark-stylesheet"  disabled />

		<!-- icons -->
		<link href="{{ asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <style>
            th
            {
                vertical-align: middle;
            }
        </style>
    </head>

    <body class="loading" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": false}, "topbar": {"color": "light"}, "showRightSidebarOnPageLoad": true}'>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <div class="navbar-custom">
                <div class="container-fluid">
                    <ul class="list-unstyled topnav-menu float-end mb-0">
    
                        <li class="dropdown notification-list topbar-dropdown">
                            <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="{{ asset('assets/avatar.png')}}" alt="user-image" class="rounded-circle">
                                <span class="pro-user-name ms-1">
                                {{ Session::get('admin_name')}} <i class="uil uil-angle-down"></i> 
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome {{ Session::get('admin_name')}}!</h6>
                                </div>
    
                                <a href="{{ url('/myprofile')}}" class="dropdown-item notify-item">
                                    <i data-feather="user" class="icon-dual icon-xs me-1"></i><span>My Account</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <a href="{{ url('/logout')}}" class="dropdown-item notify-item">
                                    <i data-feather="log-out" class="icon-dual icon-xs me-1"></i><span>Logout</span>
                                </a>
    
                            </div>
                        </li>
        
                    </ul>                    
    
                    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                        <li>
                            <button class="button-menu-mobile">
                                <i data-feather="menu"></i>
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
