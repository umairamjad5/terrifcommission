        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Employee Other Deductions</h4>
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-lg-8">
                        <form action="{{ route('delete-multiple-employee-allowances')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">
                                        List All Other Deductions
                                        <?php
                                            $query = DB::table("employees")
                                            ->join("employee_designations", "employees.employee_designation", "=", "employee_designations.autoemployeedesignationid")
                                            ->where("employees.autoemployeeid",'=',$employee)->get();
                                        ?>
                                        @foreach($query as $query)
                                        for {{ $query->employee_name}} ({{ $query->bps_name}} - {{$query->designation_name}})
                                        @endforeach
                                    </h4>
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
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Total Amount</th>
                                                    <th>Installment</th>
                                                    <th>Balance Amount</th>
                                                    <th>Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($array))
                                                @foreach($array as $row)
                                                <tr>
                                                    <td scope="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="id[]" value="{{ $row->autoemployeeotherdeductionid}}" id="headcheckid">
                                                            <label class="form-check-label" for="headcheckid"></label>
                                                        </div>
                                                    </td>
                                                    <td>E02550</td>
                                                    <td>Other</td>
                                                    <td>{{ $row->description}}</td>
                                                    <td>Rs {{ number_format($row->otherdeduction_amount)}}</td>
                                                    <td>Rs {{ number_format($row->otherdeduction_installment)}}</td>
                                                    <td>Rs {{ number_format($row->otherdeduction_balance)}}</td>
                                                    <td>
                                                        {{ date('d/m/Y',strtotime($row->otherdeduction_date))}}
                                                    </td>
                                                    <td>
                                                        <div class="hstack gap-3 fs-15">
                                                            <a href="{{ url('/update_employee_other_deduction/'.$row->autoemployeeotherdeductionid)}}" class="link-primary"><i class="uil uil-comment-edit"></i></a>
                                                            <a href="{{ url('/delete_employee_other_deduction/'.$row->autoemployeeotherdeductionid)}}" onclick="return confirm('Are you sure')" class="link-danger"><i class="uil-trash-alt"></i></a>
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
                        <form action="{{ route('update-other-employee-deduction-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <input type="hidden" name="autoemployeeotherdeductionid" value="{{ $array1->autoemployeeotherdeductionid}}">
                                        <input type="hidden" name="employee" value="{{ $array1->employeeid}}">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="description">Description<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="description" value="{{ $array1->description}}" id="description">
                                                <span class="text-danger">@error('description') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="otherdeduction_amount">Total Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="otherdeduction_amount" value="{{ $array1->otherdeduction_amount}}" id="otherdeduction_amount">
                                                <span class="text-danger">@error('otherdeduction_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="otherdeduction_installment">Installment<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="otherdeduction_installment" value="{{ $array1->otherdeduction_installment}}" id="otherdeduction_installment">
                                                <span class="text-danger">@error('otherdeduction_installment') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="otherdeduction_balance">Balance Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="otherdeduction_balance" value="{{ $array1->otherdeduction_balance}}" id="otherdeduction_balance">
                                                <span class="text-danger">@error('otherdeduction_balance') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="otherdeduction_date">Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="otherdeduction_date" value="{{ $array1->otherdeduction_date}}" id="otherdeduction_date">
                                                <span class="text-danger">@error('otherdeduction_date') {{ $message}} @enderror</span>
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
                        <form action="{{ route('other-employee-deduction-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <input type="hidden" name="employee" value="{{ $employee}}">
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="description">Description<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="description" value="{{ old('description')}}" id="description">
                                                <span class="text-danger">@error('description') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="otherdeduction_amount">Total Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="otherdeduction_amount" value="{{ old('otherdeduction_amount')}}" id="otherdeduction_amount">
                                                <span class="text-danger">@error('otherdeduction_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="otherdeduction_installment">Installment<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="otherdeduction_installment" value="{{ old('otherdeduction_installment')}}" id="otherdeduction_installment">
                                                <span class="text-danger">@error('otherdeduction_installment') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="otherdeduction_balance">Balance Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="otherdeduction_balance" value="{{ old('otherdeduction_balance')}}" id="otherdeduction_balance">
                                                <span class="text-danger">@error('otherdeduction_balance') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="otherdeduction_date">Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="otherdeduction_date" value="{{ old('otherdeduction_date')}}" id="otherdeduction_date">
                                                <span class="text-danger">@error('otherdeduction_date') {{ $message}} @enderror</span>
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
                </div>
                <!--end row-->

            </div>
            <!-- container-fluid -->
        </div>
        
        @include('layouts.footer')