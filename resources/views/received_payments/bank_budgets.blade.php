        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Quarterly Bank Budget</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8">
                        <form action="{{ route('delete-multiple-bank-budgets')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">List Quarterly Bank Budget</h4>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch form-switch-right form-switch-md">
                                            <button type="submit" onclick="return confirm('Are you sure')" class="btn btn-soft-danger"><i class="uil-trash-alt"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4 mb-3">
                                        <div class="col-lg-12">
                                            <table width="100%">
                                                <tr>
                                                    <th width="20%">Branch Code</th>
                                                    <td width="80%">0341</td>
                                                </tr>
                                                <tr>
                                                    <th width="20%">bank Name</th>
                                                    <td width="80%">National Bank</td>
                                                </tr>
                                                <tr>
                                                    <th width="20%">Account Title</th>
                                                    <td width="80%">National Tariff Commission</td>
                                                </tr>
                                                <tr>
                                                    <th width="20%">Account No</th>
                                                    <td width="80%">2267-6/1163626634</td>
                                                </tr>
                                                <tr>
                                                    <th width="20%">Branch Address</th>
                                                    <td width="80%">NBP, Main Branch Melody, Islamabad.</td>
                                                </tr>
                                            </table>
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
                                                            <input class="form-check-input" type="checkbox" name="id[]" value="{{ $row->autobankquarterlybudgetid}}" id="headcheckid">
                                                            <label class="form-check-label" for="headcheckid"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $row->bankfinancialyear}}</td>
                                                    <td>{{ $row->bank_quarterly}}</td>
                                                    <td>Rs {{ number_format($row->bank_quarterly_amount)}}</td>
                                                    <td>Rs {{ number_format($row->bank_quarterly_balance_amount)}}</td>
                                                    <td>
                                                        <div class="hstack gap-3 fs-15">
                                                            <a href="{{ url('/update_bank_budget/'.$row->autobankquarterlybudgetid)}}" class="link-primary"><i class='uil uil-comment-edit'></i></a>
                                                            <a href="{{ url('/delete_bank_budget/'.$row->autobankquarterlybudgetid)}}" onclick="return confirm('Are you sure')" class="link-danger"><i class='uil-trash-alt'></i></a>
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
                        <form action="{{ route('update-bank-budget-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <input type="hidden" name="autobankquarterlybudgetid" value="{{ $array1->autobankquarterlybudgetid}}">
                                        <div class="row gy-4">
                                            <?php
                                                $query = DB::table('ledgers')->where('bankquarterlybudgetid','=',$array1->autobankquarterlybudgetid)->get();
                                            ?>
                                            @foreach($query as $query)
                                            <div class="col-xxl-12">
                                                <label for="particular">Particular<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="particular" value="{{ $query->description}}" id="particular">
                                                <input type="hidden" class="form-control" name="last_balance_amount" value="{{ $query->last_balance_amount}}" id="last_balance_amount">
                                                <span class="text-danger">@error('particular') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="amount_date">Amount Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="amount_date" value="{{ $array1->amount_date}}" id="amount_date">
                                                <span class="text-danger">@error('amount_date') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="quarterly_amount">Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="quarterly_amount" value="{{ $array1->bank_quarterly_amount}}" id="quarterly_amount">
                                                <input type="hidden" class="form-control" name="old_quarterly_amount" value="{{ $array1->bank_quarterly_amount}}" id="old_quarterly_amount">
                                                <input type="hidden" class="form-control" name="old_balance_quarterly_amount" value="{{ $array1->bank_quarterly_balance_amount}}" id="old_balance_quarterly_amount">
                                                <input type="hidden" class="form-control" name="diff_amount" value="{{ $array1->bank_quarterly_amount - $array1->bank_quarterly_balance_amount}}" id="diff_amount">
                                                <span class="text-danger">@error('quarterly_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </form>
                        @endforeach
                        @else
                        <form action="{{ route('bank-budget-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xxl-12">
                                                <label for="particular">Particular<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="particular" value="{{ old('particular')}}" id="particular">
                                                <span class="text-danger">@error('particular') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="amount_date">Amount Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="amount_date" value="{{ old('amount_date')}}" id="amount_date">
                                                <span class="text-danger">@error('amount_date') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-xxl-12">
                                                <label for="quarterly_amount">Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="quarterly_amount" value="{{ old('quarterly_amount')}}" id="quarterly_amount">
                                                <span class="text-danger">@error('quarterly_amount') {{ $message}} @enderror</span>
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