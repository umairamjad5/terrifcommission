        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Employee Allowances</h4>
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('delete-multiple-employee-allowances')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">
                                        List All Allowances
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
                                                    <th>Head Category</th>
                                                    <th>Head Name</th>
                                                    <th>Main Head Name</th>
                                                    <th>Sub Head Name</th>
                                                    <th>Allowance Amount</th>
                                                    <!-- <th>Allowance Date</th>
                                                    <th>Description</th> -->
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($array))
                                                @foreach($array as $row)
                                                <tr>
                                                    <td scope="col">
                                                        <!-- <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="id[]" value="{{ $row->autoemployeeallowanceid}}" id="headcheckid">
                                                            <label class="form-check-label" for="headcheckid"></label>
                                                        </div> -->
                                                    </td>
                                                    <td>{{ $row->employee_name}}</td>
                                                    <td>{{ $row->head_category_name}}</td>
                                                    <td>{{ $row->head_name}}</td>
                                                    <td>{{ $row->main_head_name}}</td>
                                                    <td>{{ $row->sub_head_name}}</td>
                                                    <td>Rs {{ number_format($row->allowance_amount)}}</td>
                                                    <!-- <td>
                                                        @if($row->allowance_date != "")
                                                        {{ date('d/m/Y',strtotime($row->allowance_date))}}
                                                        @endif
                                                    </td>
                                                    <td>{{ $row->description}}</td> -->
                                                    <td>
                                                        <div class="hstack gap-3 fs-15">
                                                            <a href="{{ url('/validate_heads_allowance/'.$row->employeeid)}}" class="link-primary"><i class="uil-comment-alt-plus"></i></a>
                                                            <a href="{{ url('/update_employee_allowance/'.$row->employeeid.'/'.$row->autoemployeeallowanceid)}}" class="link-primary"><i class="uil uil-comment-edit"></i></a>
                                                            <a href="{{ url('/delete_employee_allowance/'.$row->employeeid.'/'.$row->autoemployeeallowanceid)}}" onclick="return confirm('Are you sure')" class="link-danger"><i class="uil-trash-alt"></i></a>
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