
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <title>Software Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

		<!-- App css -->
		<link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
		<link href="{{ asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

		<link href="{{ asset('assets/css/bootstrap-dark.min.css')}}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
		<link href="{{ asset('assets/css/app-dark.min.css')}}" rel="stylesheet" type="text/css" id="app-dark-stylesheet"  disabled />

		<!-- icons -->
		<link href="{{ asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />

    </head>

    <body class="authentication-bg">
        
        <div class="account-pages my-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="row g-0">
                                    <div class="col-lg-12 p-4">
                                        <h6 class="h5 mb-0 mt-3">Welcome to Software!</h6>
                                        <p class="text-muted mt-1 mb-4">
                                            Software is designed for all Sections of your Company.
                                        </p>
                                        @if(Session::has('success'))
                                            <div class="alert alert-success">
                                                {{ Session::get('success')}}
                                            </div>
                                        @endif
                                        @if(Session::has('error'))
                                            <div class="alert alert-danger">
                                                {{ Session::get('error')}}
                                            </div>
                                        @endif
                                        <form action="{{ route('login-admin')}}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Email Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="icon-dual" data-feather="mail"></i>
                                                    </span>
                                                    <input type="text" class="form-control" name="email" value="{{ old('email')}}" id="username"  placeholder="Enter username">
                                                </div>
                                                <span class="text-danger">@error('email') {{ $message}} @enderror</span>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Password</label>
                                                <!-- <a href="#" class="float-end text-muted text-unline-dashed ms-1">Forgot your password?</a> -->
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="icon-dual" data-feather="lock"></i>
                                                    </span>
                                                    <input type="password" class="form-control" name="password" placeholder="Enter password" id="password-input">
                                                </div>
                                                <span class="text-danger">@error('password') {{ $message}} @enderror</span>
                                            </div>

                                            <div class="mb-3 text-center d-grid">
                                                <button class="btn btn-primary" type="submit">Log In</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                            </div> 
                        </div>
                        
                    </div> 
                </div>
                
            </div>
            
        </div>
        

        <!-- Vendor js -->
        <script src="{{ asset('assets/js/vendor.min.js')}}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.min.js')}}"></script>
        
    </body>
</html>