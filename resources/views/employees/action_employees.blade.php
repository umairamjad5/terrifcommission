        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Employee Form</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">                    
                    <div class="col-lg-12">
                        @if(!empty($array1))
                        @foreach($array1 as $array1)
                        <form action="{{ route('update-employee-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <input type="hidden" name="autoemployeeid" value="{{ $array1->autoemployeeid}}">
                                        <div class="row gy-4">
                                            <div class="col-xl-6">
                                                <label for="employee_name">Employee Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="employee_name" value="{{ $array1->employee_name}}" id="employee_name">
                                                <span class="text-danger">@error('employee_name') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="employee_designation">Employee Designation<span class="text-danger">*</span></label>
                                                <select class="form-control select2" name="employee_designation" id="employee_designation">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('employee_designations')->orderby('bps_name')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autoemployeedesignationid}}" <?php if($query->autoemployeedesignationid == $array1->employee_designation) echo 'selected';?>>{{ $query->bps_name.'-'.$query->designation_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <!-- <input type="text" class="form-control" name="employee_designation" value="{{ $array1->employee_designation}}" id="employee_designation"> -->
                                                <span class="text-danger">@error('employee_designation') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="employee_scale">Employee Scale<span class="text-danger">*</span></label>
                                                <select class="form-control select2" name="employee_scale" id="employee_scale">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('employee_pay_scales')->orderby('bps_scale')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autoemployeepayscaleid}}" <?php if($query->autoemployeepayscaleid == $array1->employee_scale) echo 'selected';?>>{{ $query->bps_scale}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <!-- <input type="text" class="form-control" name="employee_scale" value="{{ $array1->employee_scale}}" id="employee_scale"> -->
                                                <span class="text-danger">@error('employee_scale') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="employee_stages">Employee Stages</label>
                                                <select class="form-control select2" name="employee_stages" id="employee_stages">
                                                    <option value="0">Select One</option>
                                                    <?php
                                                        for($i=1;$i<=45;$i++)
                                                        {
                                                    ?>
                                                    <option value="{{ $i}}" <?php if($i == $array1->employee_stages) echo 'selected';?>>{{ $i}}</option>    
                                                    <?php
                                                        }
                                                    ?>
                                                </select>
                                                <!-- <input type="text" class="form-control" name="employee_stages" value="{{ $array1->employee_stages}}" id="employee_stages"> -->
                                                <span class="text-danger">@error('employee_stages') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="date_of_joining">Date of Joining<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="date_of_joining" value="{{ $array1->date_of_joining}}" id="date_of_joining">
                                                <span class="text-danger">@error('date_of_joining') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="basic_salary">Basic Pay<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="basic_salary" value="{{ $array1->basic_salary}}" id="basic_salary">
                                                <span class="text-danger">@error('basic_salary') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="personal_salary">Personal Pay<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="personal_salary" value="{{ $array1->personal_salary}}" id="personal_salary">
                                                <span class="text-danger">@error('personal_salary') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="qualification_salary">Qualification Pay<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="qualification_salary" value="{{ $array1->qualification_salary}}" id="qualification_salary">
                                                <span class="text-danger">@error('qualification_salary') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="special_salary">Special Pay<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="special_salary" value="{{ $array1->special_salary}}" id="special_salary">
                                                <span class="text-danger">@error('special_salary') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="email_address">Email Address</label>
                                                <input type="text" class="form-control" name="email_address" value="{{ $array1->email_address}}" id="email_address">
                                                <span class="text-danger">@error('email_address') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="bank_account_detail">Bank Account Deatil<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="bank_account_detail" value="{{ $array1->bank_account_detail}}" id="bank_account_detail">
                                                <span class="text-danger">@error('bank_account_detail') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="date_of_retirement">Date of Retirement</label>
                                                <input type="date" class="form-control" name="date_of_retirement" value="{{ $array1->date_of_retirement}}" id="date_of_retirement">
                                                <span class="text-danger">@error('date_of_retirement') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="income_tax">Income Tax<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="income_tax" value="{{ $array1->income_tax}}" id="income_tax">
                                                <span class="text-danger">@error('income_tax') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="hb_advance">HB Advance<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="hb_advance" value="{{ $array1->hb_advance}}" id="hb_advance">
                                                <span class="text-danger">@error('hb_advance') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="group_insurance">Group Insurance<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="group_insurance" value="{{ $array1->group_insurance}}" id="group_insurance">
                                                <span class="text-danger">@error('group_insurance') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @endforeach
                        @else
                        <form action="{{ route('employee-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xl-6">
                                                <label for="employee_name">Employee Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="employee_name" value="{{ old('employee_name')}}" id="employee_name">
                                                <span class="text-danger">@error('employee_name') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="employee_designation">Employee Designation<span class="text-danger">*</span></label>
                                                <select class="form-control select2" name="employee_designation" id="employee_designation">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('employee_designations')->orderby('bps_name')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autoemployeedesignationid}}" <?php if($query->autoemployeedesignationid == old('employee_designation')) echo 'selected';?>>{{ $query->bps_name.'-'.$query->designation_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('employee_designation') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="employee_scale">Employee Scale<span class="text-danger">*</span></label>
                                                <select class="form-control select2" name="employee_scale" id="employee_scale">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('employee_pay_scales')->orderby('bps_scale')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autoemployeepayscaleid}}" <?php if($query->autoemployeepayscaleid == old('employee_scale')) echo 'selected';?>>{{ $query->bps_scale}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('employee_scale') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="employee_stages">Employee Stages</label>
                                                <select class="form-control select2" name="employee_stages" id="employee_stages">
                                                    <option value="0">Select One</option>
                                                    <?php
                                                        for($i=1;$i<=45;$i++)
                                                        {
                                                    ?>
                                                    <option value="{{ $i}}" <?php if($i == old('employee_stages')) echo 'selected';?>>{{ $i}}</option>    
                                                    <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="date_of_joining">Date of Joining<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="date_of_joining" value="{{ old('date_of_joining')}}" id="date_of_joining">
                                                <span class="text-danger">@error('date_of_joining') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="basic_salary">Basic Pay<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="basic_salary" value="{{ old('basic_salary')}}" id="basic_salary">
                                                <span class="text-danger">@error('basic_salary') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="personal_salary">Personal Pay<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="personal_salary" value="{{ old('personal_salary')}}" id="personal_salary">
                                                <span class="text-danger">@error('personal_salary') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="qualification_salary">Qualification Pay<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="qualification_salary" value="{{ old('qualification_salary')}}" id="qualification_salary">
                                                <span class="text-danger">@error('qualification_salary') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="special_salary">Special Pay<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="special_salary" value="{{ old('special_salary')}}" id="special_salary">
                                                <span class="text-danger">@error('special_salary') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="email_address">Email Address</label>
                                                <input type="text" class="form-control" name="email_address" value="{{ old('email_address')}}" id="email_address">
                                                <span class="text-danger">@error('email_address') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="bank_account_detail">Bank Account Deatil<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="bank_account_detail" value="{{ old('bank_account_detail')}}" id="bank_account_detail">
                                                <span class="text-danger">@error('bank_account_detail') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="date_of_retirement">Date of Retirement</label>
                                                <input type="date" class="form-control" name="date_of_retirement" value="{{ old('date_of_retirement')}}" id="date_of_retirement">
                                                <span class="text-danger">@error('date_of_retirement') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="income_tax">Income Tax<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="income_tax" value="{{ old('income_tax')}}" id="income_tax">
                                                <span class="text-danger">@error('income_tax') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="hb_advance">HB Advance<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="hb_advance" value="{{ old('hb_advance')}}" id="hb_advance">
                                                <span class="text-danger">@error('hb_advance') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="group_insurance">Group Insurance<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="group_insurance" value="{{ old('group_insurance')}}" id="group_insurance">
                                                <span class="text-danger">@error('group_insurance') {{ $message}} @enderror</span>
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