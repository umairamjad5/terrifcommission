        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Main Heads Budget</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8">
                        <form action="{{ route('delete-multiple-main-head-budgets')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">List Main Heads Budget</h4>
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
                                                    <th>Quarter</th>
                                                    <th>Main Head Code</th>
                                                    <th>Main Head Name</th>
                                                    <th>Total Amount</th>
                                                    <th>Amount</th>
                                                    <th>Balance Amount</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($array))
                                                @foreach($array as $row)
                                                <tr>
                                                    <td scope="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="id[]" value="{{ $row->automainheadbudgetid}}" id="mainheadcheckid">
                                                            <label class="form-check-label" for="mainheadcheckid"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $row->main_head_financial_year}}</td>
                                                    <td>{{ $row->main_head_quarter}}</td>
                                                    <td>{{ $row->main_head_code}}</td>
                                                    <td>{{ $row->main_head_name}}</td>
                                                    <td>Rs {{ number_format($row->main_head_budget_total_amount)}}</td>
                                                    <td>Rs {{ number_format($row->main_head_budget_amount)}}</td>
                                                    <td>Rs {{ number_format($row->main_head_budget_balance)}}</td>
                                                    <td>
                                                        <div class="hstack gap-3 fs-15">
                                                            <a href="{{ url('/update_main_head_budget/'.$row->automainheadbudgetid)}}" class="link-primary"><i class='uil uil-comment-edit'></i></a>
                                                            <a href="{{ url('/delete_main_head_budget/'.$row->automainheadbudgetid)}}" onclick="return confirm('Are you sure')" class="link-danger"><i class='uil-trash-alt'></i></a>
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
                        <form action="{{ route('update-main-head-budget-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <input type="hidden" name="automainheadbudgetid" value="{{ $array1->automainheadbudgetid}}">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="main_head">Main Head<span class="text-danger">*</span></label>
                                                <select name="main_head" id="main_head" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('main_heads')->orderby('automainheadid')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->automainheadid}}" <?php if($query->automainheadid == $array1->mainheadid) echo 'selected';?>>{{ $query->main_head_code}} - {{ $query->main_head_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('main_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="total_amount">Total Amount</label>
                                                <input type="number" class="form-control" name="total_amount" value="{{ $array1->main_head_budget_total_amount}}" id="total_amount">
                                                <span class="text-danger">@error('total_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="amount">Release Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="amount" value="{{ $array1->main_head_budget_amount}}" id="amount">
                                                <input type="hidden" class="form-control" name="old_amount" value="{{ $array1->main_head_budget_amount}}" id="old_amount">
                                                <input type="hidden" class="form-control" name="old_balance_amount" value="{{ $array1->main_head_budget_balance}}" id="old_balance_amount">
                                                <input type="hidden" class="form-control" name="diff_amount" value="{{ $array1->main_head_budget_amount - $array1->main_head_budget_balance}}" id="diff_amount">
                                                <span class="text-danger">@error('amount') {{ $message}} @enderror</span>
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
                        <form action="{{ route('main-head-budget-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="main_head">Main Head<span class="text-danger">*</span></label>
                                                <select name="main_head" id="main_head" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('main_heads')->orderby('automainheadid')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->automainheadid}}" <?php if($query->automainheadid == old('main_head')) echo 'selected';?>>{{ $query->main_head_code}} - {{ $query->main_head_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('main_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="total_amount">Total Amount</label>
                                                <input type="number" class="form-control" name="total_amount" value="{{ old('total_amount')}}" id="total_amount">
                                                <span class="text-danger">@error('total_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="amount">Release Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="amount" value="{{ old('amount')}}" id="amount">
                                                <span class="text-danger">@error('amount') {{ $message}} @enderror</span>
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