        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Employees</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('generate-salary-slips')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Generate Salary Slips</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4 mb-3">
                                        <div class="col-lg-12 fomr-group">
                                            <label for="salary_month">Salary Month</label>
                                            <input type="month" name="salary_month" class="form-control" id="salary_month" value="old('salary_month')">
                                            <span class="text-danger">@error('salary_month') {{ $message}} @enderror</span>
                                        </div>
                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('delete-multiple-employees')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">List Employees</h4>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch form-switch-right form-switch-md">
                                            <button type="submit" onclick="return confirm('Are you sure')" class="btn btn-soft-danger"><i class="uil-trash-alt"></i></button>
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
                                                    <th>Name</th>
                                                    <th>Designation</th>
                                                    <th>Scale</th>
                                                    <th>Date of Joining</th>
                                                    <th>Basic Salary</th>
                                                    <th>Personal Salary</th>
                                                    <th>Bank Account Detail</th>
                                                    <th>Date of Retirement</th>
                                                    <th>Income Tax</th>
                                                    <th>HB Advance</th>
                                                    <th>Group Insurance</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($array))
                                                @foreach($array as $row)
                                                <tr>
                                                    <td scope="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="id[]" value="{{ $row->autoemployeeid}}" id="headcheckid">
                                                            <label class="form-check-label" for="headcheckid"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $row->employee_name}}</td>
                                                    <td>
                                                        <?php
                                                            $q = DB::table('employee_designations')->where('autoemployeedesignationid','=',$row->employee_designation)->get();
                                                            if(count($q) > 0)
                                                            {
                                                                foreach($q as $q);
                                                                echo $q->designation_name;
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q1 = DB::table('employee_pay_scales')->where('autoemployeepayscaleid','=',$row->employee_scale)->get();
                                                            if(count($q1) > 0)
                                                            {
                                                                foreach($q1 as $q1);
                                                                echo $q1->bps_scale;
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>{{ $row->date_of_joining}}</td>
                                                    <td>
                                                        @if($row->basic_salary != '0')
                                                        Rs{{ number_format($row->basic_salary)}}
                                                        @else
                                                        <?php
                                                            $q1 = DB::table('employee_pay_scales')->where('autoemployeepayscaleid','=',$row->employee_scale)->get();
                                                            if(count($q1) > 0)
                                                            {
                                                                foreach($q1 as $q1);
                                                                if($row->employee_stages > $q1->scale_stages)
                                                                {
                                                                    $emp_scale_salary = $q1->minimum_pay;
                                                                    $emp_scale_increment = $q1->scale_stages * $q1->increment_amount;
                                                                    $emp_salary = $emp_scale_salary + $emp_scale_increment;    
                                                                }
                                                                else
                                                                {
                                                                    $emp_scale_salary = $q1->minimum_pay;
                                                                    $emp_scale_increment = $row->employee_stages * $q1->increment_amount;
                                                                    $emp_salary = $emp_scale_salary + $emp_scale_increment;   
                                                                }
                                                                echo 'Rs'.number_format($emp_salary);
                                                            }                                                    
                                                        ?>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($row->personal_salary != '0')
                                                        Rs{{ number_format($row->personal_salary)}}
                                                        @else
                                                        <?php
                                                            $q1 = DB::table('employee_pay_scales')->where('autoemployeepayscaleid','=',$row->employee_scale)->get();
                                                            if(count($q1) > 0)
                                                            {
                                                                foreach($q1 as $q1);
                                                                if($row->employee_stages > $q1->scale_stages)
                                                                {
                                                                    $diff_scale_stages = $row->employee_stages - $q1->scale_stages;
                                                                    $emp_personal_salary = $q1->increment_amount * $diff_scale_stages;
                                                                }
                                                                else
                                                                {
                                                                    $emp_personal_salary = 0;
                                                                }
                                                                echo 'Rs'.number_format($emp_personal_salary);
                                                            }                                                    
                                                        ?>
                                                        @endif    
                                                    </td>
                                                    <td>{{ $row->bank_account_detail}}</td>
                                                    <td>{{ $row->date_of_retirement}}</td>
                                                    <td>Rs{{ number_format($row->income_tax)}}</td>
                                                    <td>Rs{{ number_format($row->hb_advance)}}</td>
                                                    <td>Rs{{ number_format($row->group_insurance)}}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Actions <i class="icon"><span data-feather="chevron-down"></span></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a href="{{ url('/action_allowances/'.$row->autoemployeeid)}}" class="dropdown-item">Add Allowances</a>
                                                                <a href="{{ url('/employee_allowances/'.$row->autoemployeeid)}}" class="dropdown-item">List Allowances</a>
                                                                <!-- <a href="{{ url('/action_deductions/'.$row->autoemployeeid)}}" class="dropdown-item">Add Deductions</a> -->
                                                                <a href="{{ url('/employee_other_deductions/'.$row->autoemployeeid)}}" class="dropdown-item">Other Deductions</a>
                                                                <a href="{{ url('/validate_salary_slip/'.$row->autoemployeeid)}}" class="dropdown-item">Salary Slip</a>
                                                                <a href="{{ url('/update_employee/'.$row->autoemployeeid)}}" class="dropdown-item">Update</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a href="{{ url('/delete_employee/'.$row->autoemployeeid)}}" onclick="return confirm('Are you sure')" class="dropdown-item">Delete</a>
                                                            </div>
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
                </div>
                <!--end row-->

            </div>
            <!-- container-fluid -->
        </div>
        
        @include('layouts.footer')