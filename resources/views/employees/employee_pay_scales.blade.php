        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Employee Pay Scales</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8">
                        <form action="{{ route('delete-multiple-employee-pay-scales')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">List Employee Pay Scales</h4>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch form-switch-right form-switch-md">
                                            <button type="deductionmit" onclick="return confirm('Are you sure')" class="btn btn-soft-danger"><i class="uil-trash-alt"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4 mb-3">
                                        <div class="col-sm-auto">
                                            <div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="basic-datatable" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="select-all" id="select-all">
                                                            <label class="form-check-label" for="select-all"></label>
                                                        </div>
                                                    </th>
                                                    <th>Year</th>
                                                    <th>BPS Scale</th>
                                                    <th>Minimun Salary</th>
                                                    <th>Increment</th>
                                                    <th>Maximum Salary</th>
                                                    <th>Stages</th>
                                                    <th>GP Fund Subscription</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($array))
                                                @foreach($array as $row)
                                                <tr>
                                                    <td scope="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="id[]" value="{{ $row->autoemployeepayscaleid}}" id="deductionheadcheckid">
                                                            <label class="form-check-label" for="deductionheadcheckid"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $row->payscale_year}}</td>
                                                    <td>{{ $row->bps_scale}}</td>
                                                    <td>{{ $row->minimum_pay}}</td>
                                                    <td>{{ $row->increment_amount}}</td>
                                                    <td>{{ $row->maximum_pay}}</td>
                                                    <td>{{ $row->scale_stages}}</td>
                                                    <td>{{ $row->gp_fund_advance}}</td>
                                                    <td>
                                                        <div class="hstack gap-3 fs-15">
                                                            <a href="{{ url('/update_employee_pay_scale/'.$row->autoemployeepayscaleid)}}" class="link-primary"><i class='uil uil-comment-edit'></i></a>
                                                            <a href="{{ url('/delete_employee_pay_scale/'.$row->autoemployeepayscaleid)}}" onclick="return confirm('Are you sure')" class="link-danger"><i class='uil-trash-alt'></i></a>
                                                        </div>
                                                    </td>
                                                </tr>  
                                                @endforeach
                                                @endif                                      
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="col-lg-4">
                        @if(!empty($array1))
                        @foreach($array1 as $array1)
                        <form action="{{ route('update-employee-pay-scale-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <input type="hidden" name="autoemployeepayscaleid" value="{{ $array1->autoemployeepayscaleid}}">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="bps_scale">BPS Scale<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="bps_scale" value="{{ $array1->bps_scale}}" id="bps_scale">
                                                <span class="text-danger">@error('bps_scale') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="minimum_pay">Minimun Salary<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="minimum_pay" value="{{ $array1->minimum_pay}}" id="minimum_pay">
                                                <span class="text-danger">@error('minimum_pay') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="increment_amount">Increment<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="increment_amount" value="{{ $array1->increment_amount}}" id="increment_amount">
                                                <span class="text-danger">@error('increment_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="maximum_pay">Maximum Salary<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="maximum_pay" value="{{ $array1->maximum_pay}}" id="maximum_pay">
                                                <span class="text-danger">@error('maximum_pay') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="scale_stages">Stages<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="scale_stages" value="{{ $array1->scale_stages}}" id="scale_stages">
                                                <span class="text-danger">@error('scale_stages') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="gp_fund_advance">GP Fund Subscription<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gp_fund_advance" value="{{ $array1->gp_fund_advance}}" id="gp_fund_advance">
                                                <span class="text-danger">@error('gp_fund_advance') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </form>
                        @endforeach
                        @else
                        <form action="{{ route('employee-pay-scale-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                        <div class="col-xxl-12">
                                                <label for="bps_scale">BPS Scale<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="bps_scale" value="{{ old('bps_scale')}}" id="bps_scale">
                                                <span class="text-danger">@error('bps_scale') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="minimum_pay">Minimun Salary<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="minimum_pay" value="{{ old('minimum_pay')}}" id="minimum_pay">
                                                <span class="text-danger">@error('minimum_pay') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="increment_amount">Increment<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="increment_amount" value="{{ old('increment_amount')}}" id="increment_amount">
                                                <span class="text-danger">@error('increment_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="maximum_pay">Maximum Salary<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="maximum_pay" value="{{ old('maximum_pay')}}" id="maximum_pay">
                                                <span class="text-danger">@error('maximum_pay') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="scale_stages">Stages<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="scale_stages" value="{{ old('scale_stages')}}" id="scale_stages">
                                                <span class="text-danger">@error('scale_stages') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="gp_fund_advance">GP Fund Subscription<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gp_fund_advance" value="{{ old('gp_fund_advance')}}" id="gp_fund_advance">
                                                <span class="text-danger">@error('gp_fund_advance') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

            </div>
            <!-- container-fluid -->
        </div>
        
        @include('layouts.footer')