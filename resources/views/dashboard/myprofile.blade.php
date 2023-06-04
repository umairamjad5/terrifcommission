            @include('layouts.header')
            @include('layouts.left')
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">My Account</h4>
                            </div>
                        </div>
                    </div>     
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mt-0 mb-1">Update Your Profile</h4>
                                    <form action="{{ route('myprofiledb')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $array->id}}">
                                        <div class="row g-3">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="name" value="{{ $array->name}}">
                                                <span class="text-danger">@error('name') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="email" value="{{ $array->email}}">
                                                <span class="text-danger">@error('email') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Gender</label>
                                                <select name="gender" class="form-control">
                                                    <option value="">Select One</option>
                                                    <option value="Male" <?php if($array->gender == "Male") echo 'selected';?>>Male</option>
                                                    <option value="Female" <?php if($array->gender == "Female") echo 'selected';?>>Female</option>
                                                </select>
                                                <span class="text-danger">@error('gender') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">Phone No</label>
                                                <input type="text" class="form-control" name="phone" value="{{ $array->phone}}">
                                                <span class="text-danger">@error('phone') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-success">Update Profile</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div> 
                            </div> 
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mt-0 mb-1">Update Your Password</h4>
                                    <form action="{{ route('mypassworddb')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $array->id}}">
                                        <div class="row g-3">
                                            <div class="form-group col-md-12">
                                                <label class="form-label">New Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" name="password">
                                                <span class="text-danger">@error('password') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-success">Change Password</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div> 
                            </div> 
                        </div> 
                    </div>
                </div> 
            </div> 
            @include('layouts.footer')