        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Employee Deductions</h4>
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('delete-multiple-employee-deductions')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">
                                        List All Deductions
                                    </h4>
                                    <!-- <div class="flex-shrink-0">
                                        <div class="form-check form-switch form-switch-right form-switch-md">
                                            <button type="submit" onclick="return confirm('Are you sure')" class="btn btn-soft-danger"><i class="uil-trash-alt"></i></button>
                                        </div>
                                    </div> -->
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
                                                        <!-- <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="select-all" id="select-all">
                                                            <label class="form-check-label" for="select-all"></label>
                                                        </div> -->
                                                    </th>
                                                    <th>Employee Name</th>
                                                    <th>Deduction Head</th>
                                                    <th>Deduction Amount</th>
                                                    <th>Deduction Date</th>
                                                    <th>Description</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($array))
                                                @foreach($array as $row)
                                                <tr>
                                                    <td scope="col">
                                                        <!-- <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="id[]" value="{{ $row->autoemployeedeductionid}}" id="headcheckid">
                                                            <label class="form-check-label" for="headcheckid"></label>
                                                        </div> -->
                                                    </td>
                                                    <td>{{ $row->employee_name}}</td>
                                                    <td>{{ $row->deduction_head_code.' - '.$row->deduction_head_name}}</td>
                                                    <td>Rs {{ number_format($row->deduction_amount)}}</td>
                                                    <td>
                                                        @if($row->deduction_date != "")
                                                        {{ date('d/m/Y',strtotime($row->deduction_date))}}
                                                        @endif
                                                    </td>
                                                    <td>{{ $row->description}}</td>
                                                    <td>
                                                        <div class="hstack gap-3 fs-15">
                                                            <a href="{{ url('/action_deductions/'.$row->autoemployeeid)}}" class="link-primary"><i class="uil-comment-alt-plus"></i></a>
                                                            <a href="{{ url('/update_employee_deduction/'.$row->autoemployeeid.'/'.$row->autoemployeedeductionid)}}" class="link-primary"><i class="uil uil-comment-edit"></i></a>
                                                            <a href="{{ url('/delete_employee_deduction/'.$row->autoemployeeid.'/'.$row->autoemployeedeductionid)}}" onclick="return confirm('Are you sure')" class="link-danger"><i class="uil-trash-alt"></i></a>
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