        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">HB Loan</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8">
                        <form action="{{ route('delete-multiple-hb-loans')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">List HB Loan</h4>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch form-switch-right form-switch-md">
                                            <button type="submit" onclick="return confirm('Are you sure')" class="btn btn-soft-danger"><i class="uil-trash-alt"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
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
                                                    <th>Employee Name</th>
                                                    <th>Loan Date</th>
                                                    <th>Total Loan Amount</th>
                                                    <th>Approved Amount</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($array))
                                                @foreach($array as $row)
                                                <tr>
                                                    <td scope="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="id[]" value="{{ $row->autoemployeehbloanid}}" id="headcheckid">
                                                            <label class="form-check-label" for="headcheckid"></label>
                                                        </div>
                                                    </td>
                                                    <?php
                                                        $q = DB::table('employees')->where('autoemployeeid','=',$row->employeeid)->get();
                                                    ?>
                                                    <td>
                                                        @foreach($q as $q)
                                                            {{ $q->employee_name}}
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $row->hb_loan_date}}</td>
                                                    <td>Rs {{ number_format($row->hb_loan_amount)}}</td>
                                                    <td>Rs {{ number_format($row->hb_approved_amount)}}</td>
                                                    <td>
                                                        <div class="hstack gap-3 fs-15">
                                                            <a href="{{ url('/update_hb_loan/'.$row->autoemployeehbloanid)}}" class="link-primary"><i class='uil uil-comment-edit'></i></a>
                                                            <a href="{{ url('/delete_hb_loan/'.$row->autoemployeehbloanid)}}" onclick="return confirm('Are you sure')" class="link-danger"><i class='uil-trash-alt'></i></a>
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
                        <form action="{{ route('update-hb-loan-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <input type="hidden" name="autoemployeehbloanid" value="{{ $array1->autoemployeehbloanid}}">
                                        <div class="col-xl-12">
                                            <label for="employee">Employee<span class="text-danger">*</span></label>
                                            <select class="form-control select2" name="employee" id="employee">
                                                <option value="">Select One</option>
                                                <?php
                                                    $query = DB::table('employees')->orderby('autoemployeeid')->get();
                                                ?>
                                                @if(!empty($query))
                                                @foreach($query as $query)
                                                <option value="{{ $query->autoemployeeid}}" <?php if($query->autoemployeeid == $array1->employeeid) echo 'selected';?>>{{ $query->employee_name}}</option>    
                                                @endforeach
                                                @endif
                                            </select>
                                            <span class="text-danger">@error('employee') {{ $message}} @enderror</span>
                                        </div>
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="hb_loan_date">Loan Amount Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="hb_loan_date" value="{{ $array1->hb_loan_date}}" id="hb_loan_date">
                                                <span class="text-danger">@error('hb_loan_date') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="hb_loan_amount">Approved Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="hb_loan_amount" value="{{ $array1->hb_loan_amount}}" id="hb_loan_amount">
                                                <span class="text-danger">@error('hb_loan_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="hb_approved_amount">Loan Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="hb_approved_amount" value="{{ $array1->hb_approved_amount}}" id="hb_approved_amount">
                                                <input type="hidden" class="form-control" name="old_hb_approved_amount" value="{{ $array1->hb_approved_amount}}" id="old_hb_approved_amount">
                                                <span class="text-danger">@error('hb_approved_amount') {{ $message}} @enderror</span>
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
                        <form action="{{ route('hb-loan-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xl-12">
                                                <label for="employee">Employee<span class="text-danger">*</span></label>
                                                <select class="form-control select2" name="employee" id="employee">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('employees')->orderby('autoemployeeid')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autoemployeeid}}" <?php if($query->autoemployeeid == old('employee')) echo 'selected';?>>{{ $query->employee_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('employee') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="hb_loan_date">Loan Amount Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="hb_loan_date" value="{{ old('hb_loan_date')}}" id="hb_loan_date">
                                                <span class="text-danger">@error('hb_loan_date') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="hb_loan_amount">Approved Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="hb_loan_amount" value="{{ old('hb_loan_amount')}}" id="hb_loan_amount">
                                                <span class="text-danger">@error('hb_loan_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="hb_approved_amount">Loan Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="hb_approved_amount" value="{{ old('hb_approved_amount')}}" id="hb_approved_amount">
                                                <span class="text-danger">@error('hb_approved_amount') {{ $message}} @enderror</span>
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