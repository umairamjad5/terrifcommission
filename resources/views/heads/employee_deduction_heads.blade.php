        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Employee Deduction Heads</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8">
                        <form action="{{ route('delete-multiple-employee-deduction-heads')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">List Employee Deduction Heads</h4>
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
                                                    <th>Financial Year</th>
                                                    <th>Deduction Head Code</th>
                                                    <th>Deduction Head Name</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($array))
                                                @foreach($array as $row)
                                                <tr>
                                                    <td scope="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="id[]" value="{{ $row->autoemployeedeductionheadid}}" id="deductionheadcheckid">
                                                            <label class="form-check-label" for="deductionheadcheckid"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $row->deductionhead_financialyear}}</td>
                                                    <td>{{ $row->deduction_head_code}}</td>
                                                    <td>{{ $row->deduction_head_name}}</td>
                                                    <td>
                                                        <div class="hstack gap-3 fs-15">
                                                            <a href="{{ url('/update_employee_deduction_head/'.$row->autoemployeedeductionheadid)}}" class="link-primary"><i class='uil uil-comment-edit'></i></a>
                                                            <a href="{{ url('/delete_employee_deduction_head/'.$row->autoemployeedeductionheadid)}}" onclick="return confirm('Are you sure')" class="link-danger"><i class='uil-trash-alt'></i></a>
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
                        <form action="{{ route('update-employee-deduction-head-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <input type="hidden" name="autoemployeedeductionheadid" value="{{ $array1->autoemployeedeductionheadid}}">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="deduction_head_code">Deduction Head Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="deduction_head_code" value="{{ $array1->deduction_head_code}}" id="deduction_head_code">
                                                <span class="text-danger">@error('deduction_head_code') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="deduction_head_name">Deduction Head Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="deduction_head_name" value="{{ $array1->deduction_head_name}}" id="deduction_head_name">
                                                <span class="text-danger">@error('deduction_head_name') {{ $message}} @enderror</span>
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
                        <form action="{{ route('employee-deduction-head-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="deduction_head_code">Deduction Head Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="deduction_head_code" value="{{ old('deduction_head_code')}}" id="deduction_head_code">
                                                <span class="text-danger">@error('deduction_head_code') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="deduction_head_name">Deduction Head Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="deduction_head_name" value="{{ old('deduction_head_name')}}" id="deduction_head_name">
                                                <span class="text-danger">@error('deduction_head_name') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="submit" class="btn btn-success">Sumit</button>
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