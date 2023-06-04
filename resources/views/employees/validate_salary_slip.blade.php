        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Validate Salary Month</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8 m-auto">
                        <form action="{{ route('add-to-salary-slip')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Select Salary Month</h4>
                                </div>
                                <input type="hidden" value="{{ $autoemployeeid}}" name="employeeid">
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-lg-12">
                                                <label for="salary_month">Salary Month<span class="text-danger">*</span></label>
                                                <input type="month" name="salary_month" id="salary_month" class="form-control" value="{{ old('salary_month')}}">
                                                <span class="text-danger">@error('salary_month') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="submit" class="btn btn-success">Enter to Salary Slip</button>
                                            </div>
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

            </div>
            <!-- container-fluid -->
        </div>
        
        @include('layouts.footer')