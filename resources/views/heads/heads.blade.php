@include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Heads</h4>
                        </div>
                    </div>
                </div>     
                <div class="row">
                    <div class="col-lg-8">
                        <form action="{{ route('delete-multiple-heads')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">List Heads</h4>
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
                                                    <th>Head Category</th>
                                                    <th>Head Code</th>
                                                    <th>Head Name</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($array))
                                                @foreach($array as $row)
                                                <tr>
                                                    <td scope="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="id[]" value="{{ $row->autoheadid}}" id="headcheckid">
                                                            <label class="form-check-label" for="headcheckid"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $row->head_financialyear}}</td>
                                                    <td>{{ $row->head_category_name}}</td>
                                                    <td>{{ $row->head_code}}</td>
                                                    <td>{{ $row->head_name}}</td>
                                                    <td>
                                                        <div class="hstack gap-3 fs-15">
                                                            <a href="{{ url('/update_head/'.$row->autoheadid)}}" class="link-primary"><i class="uil uil-comment-edit"></i></a>
                                                            <a href="{{ url('/delete_head/'.$row->autoheadid)}}" onclick="return confirm('Are you sure')" class="link-danger"><i class="uil-trash-alt"></i></a>
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
                        <form action="{{ route('update-head-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <input type="hidden" name="autoheadid" value="{{ $array1->autoheadid}}">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="head_category">Head Category<span class="text-danger">*</span></label>
                                                <select name="head_category" id="head_category" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('head_categories')->orderby('autoheadcategoryid','asc')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autoheadcategoryid}}" <?php if($query->autoheadcategoryid == $array1->headcategoryid) echo 'selected';?>>{{ $query->head_category_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('head_category') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="head_code">Head Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="head_code" value="{{ $array1->head_code}}" id="head_code">
                                                <span class="text-danger">@error('head_code') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="head_name">Head Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="head_name" value="{{ $array1->head_name}}" id="head_name">
                                                <span class="text-danger">@error('head_name') {{ $message}} @enderror</span>
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
                        <form action="{{ route('head-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="head_category">Head Category<span class="text-danger">*</span></label>
                                                <select name="head_category" id="head_category" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('head_categories')->orderby('autoheadcategoryid','asc')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autoheadcategoryid}}" <?php if($query->autoheadcategoryid == old('head_category')) echo 'selected';?>>{{ $query->head_category_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('head_category') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="head_code">Head Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="head_code" value="{{ old('head_code')}}" id="head_code">
                                                <span class="text-danger">@error('head_code') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="head_name">Head Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="head_name" value="{{ old('head_name')}}" id="head_name">
                                                <span class="text-danger">@error('head_name') {{ $message}} @enderror</span>
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