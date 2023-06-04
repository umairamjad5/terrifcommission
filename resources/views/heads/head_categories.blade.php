        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Head Categories</h4>
                        </div>
                    </div>
                </div>     
                <div class="row">
                    <div class="col-lg-8">
                        <form action="{{ route('delete-multiple-head-categories')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">List Head Categories</h4>
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
                                                    <th>Financial Year</th>
                                                    <th>Head Type</th>
                                                    <th>Head Category Code</th>
                                                    <th>Head Category Name</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($array))
                                                @foreach($array as $row)
                                                <tr>
                                                    <td scope="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="id[]" value="{{ $row->autoheadcategoryid}}" id="headcheckid">
                                                            <label class="form-check-label" for="headcheckid"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $row->headcategory_financialyear}}</td>
                                                    <td>{{ $row->head_type}}</td>
                                                    <td>{{ $row->head_category_code}}</td>
                                                    <td>{{ $row->head_category_name}}</td>
                                                    <td>                                                        
                                                        <a href="{{ url('/update_head_category/'.$row->autoheadcategoryid)}}" class="link-primary"><i class='uil uil-comment-edit'></i></a>
                                                        <a href="{{ url('/delete_head_category/'.$row->autoheadcategoryid)}}" onclick="return confirm('Are you sure')" class="link-danger"><i class='uil-trash-alt'></i></a>
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
                        <form action="{{ route('update-head-category-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <input type="hidden" name="autoheadcategoryid" value="{{ $array1->autoheadcategoryid}}">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="head_type">Head Type<span class="text-danger">*</span></label>
                                                <select name="head_type" id="head_type" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <option value="Expenditures" <?php if($array1->head_type == 'Expenditures') echo 'selected';?>>Expenditures</option>
                                                    <option value="Payroll" <?php if($array1->head_type == 'Payroll') echo 'selected';?>>Payroll</option>
                                                    <option value="Allowances" <?php if($array1->head_type == 'Allowances') echo 'selected';?>>Allowances</option>
                                                    <option value="Deductions" <?php if($array1->head_type == 'Deductions') echo 'selected';?>>Deductions</option>
                                                    <option value="Third Parties" <?php if($array1->head_type == 'Third Parties') echo 'selected';?>>Third Parties</option>
                                                </select>
                                                <span class="text-danger">@error('head_type') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="head_category_code">Head Category Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="head_category_code" value="{{ $array1->head_category_code}}" id="head_category_code">
                                                <span class="text-danger">@error('head_category_code') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="head_category_name">Head Category Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="head_category_name" value="{{ $array1->head_category_name}}" id="head_category_name">
                                                <span class="text-danger">@error('head_category_name') {{ $message}} @enderror</span>
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
                        <form action="{{ route('head-category-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="head_type">Head Type<span class="text-danger">*</span></label>
                                                <select name="head_type" id="head_type" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <option value="Expenditures" <?php if(old('head_type') == 'Expenditures') echo 'selected';?>>Expenditures</option>
                                                    <option value="Payroll" <?php if(old('head_type') == 'Payroll') echo 'selected';?>>Payroll</option>
                                                    <option value="Allowances" <?php if(old('head_type') == 'Allowances') echo 'selected';?>>Allowances</option>
                                                    <option value="Deductions" <?php if(old('head_type') == 'Deductions') echo 'selected';?>>Deductions</option>
                                                    <option value="Third Parties" <?php if(old('head_type') == 'Third Parties') echo 'selected';?>>Third Parties</option>
                                                </select>
                                                <span class="text-danger">@error('head_type') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="head_category_code">Head Category Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="head_category_code" value="{{ old('head_category_code')}}" id="head_category_code">
                                                <span class="text-danger">@error('head_category_code') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="head_category_name">Head Category Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="head_category_name" value="{{ old('head_category_name')}}" id="head_category_name">
                                                <span class="text-danger">@error('head_category_name') {{ $message}} @enderror</span>
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
            </div> 
        </div>         
        @include('layouts.footer')