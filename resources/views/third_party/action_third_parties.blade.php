        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Action Third Parties</h4>
                        </div>
                    </div>
                </div>
                <?php
                    $thisyearmonths = date('m',time());
                        
                    if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                    {
                        $financial_year = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                    }
                    else
                    {
                        $financial_year = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                    }
                    
                    if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                    {
                        $quarter = '3rd Quarter';
                    }
                    if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                    {
                        $quarter = '4th Quarter';
                    }
                    if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                    {
                        $quarter = '1st Quarter';
                    }
                    if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                    {
                        $quarter = '2nd Quarter';
                    }
                ?>
                @if(!empty($array1))
                @foreach($array1 as $array1)
                <div class="row h-100">
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <span class="text-muted text-uppercase fs-12 fw-bold">This Financial Year Remaining Balance</span>
                                        <h3 class="mb-0">
                                            <?php
                                                $query = DB::table("over_all_budgets")
                                                ->sum('balance_amount');
                                                echo 'Rs '.number_format($query);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <span class="text-muted text-uppercase fs-12 fw-bold">{{ $quarter}} Remaining Balance</span>
                                        <h3 class="mb-0">
                                            <?php
                                                $query = DB::table("quarterly_budgets")
                                                ->where('quarterly','=',$quarter)
                                                ->sum('quarterly_balance_amount');
                                                echo 'Rs '.number_format($query);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <span class="text-muted text-uppercase fs-12 fw-bold">Head Category Remaining Balance</span>
                                        <h3 class="mb-0">
                                            <?php
                                                $query = DB::table("head_categories_budgets")
                                                ->where('headcategoryid','=',$array1->headcategoryid)
                                                ->where('head_category_financial_year','=',$financial_year)
                                                ->where('head_category_quarter','=',$quarter)
                                                ->sum('head_category_budget_balance');
                                                echo 'Rs '.number_format($query);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <span class="text-muted text-uppercase fs-12 fw-bold">Head Remaining Balance</span>
                                        <h3 class="mb-0">
                                            <?php
                                                $query1 = DB::table("heads_budgets")
                                                ->where('headid','=',$array1->headid)
                                                ->where('head_financial_year','=',$financial_year)
                                                ->where('head_quarter','=',$quarter)
                                                ->sum('head_budget_balance');
                                                echo 'Rs '.number_format($query1);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <span class="text-muted text-uppercase fs-12 fw-bold">Main Head Remaining Balance</span>
                                        <h3 class="mb-0">
                                            <?php
                                                $query2 = DB::table("main_head_budgets")
                                                ->where('mainheadid','=',$array1->mainheadid)
                                                ->where('main_head_financial_year','=',$financial_year)
                                                ->where('main_head_quarter','=',$quarter)
                                                ->sum('main_head_budget_balance');
                                                echo 'Rs '.number_format($query2);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <span class="text-muted text-uppercase fs-12 fw-bold">Sub Head Remaining Balance</span>
                                        <h3 class="mb-0">
                                            <?php
                                                $query3 = DB::table("sub_head_budgets")
                                                ->where('subheadid','=',$array1->subheadid)
                                                ->where('sub_head_financial_year','=',$financial_year)
                                                ->where('sub_head_quarter','=',$quarter)
                                                ->sum('sub_head_budget_balance');
                                                echo 'Rs '.number_format($query3);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row h-100">
                    <div class="col-lg-12">
                        <form action="{{ route('update-third-party-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <input type="hidden" value="{{ $array1->autothirdpartyid}}" name="autothirdpartyid">
                                <input type="hidden" value="{{ $array1->headcategoryid}}" name="headcategoryid">
                                <input type="hidden" value="{{ $array1->headid}}" name="headid">
                                <input type="hidden" value="{{ $array1->mainheadid}}" name="mainheadid">
                                <input type="hidden" value="{{ $array1->subheadid}}" name="subheadid">
                                <?php
                                    $debitledger = DB::table('ledgers')
                                    ->where('thirdpartyid','=',$array1->autothirdpartyid)
                                    ->where('debit_credit','=','Debit')
                                    ->get();
                                ?>
                                @foreach($debitledger as $debitledger)
                                <input type="hidden" class="form-control" name="ledger_balance_amount" value="{{ $debitledger->last_balance_amount}}" id="ledger_balance_amount">
                                <input type="hidden" value="{{ $debitledger->autoledgerid}}" name="debitautoledgerid">
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-lg-6">
                                                <label for="bill_no">Bill No<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="bill_no" value="{{ $array1->bill_no}}" id="bill_no">
                                                <span class="text-danger">@error('bill_no') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="description">Description of Payment<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="description" value="{{ $array1->description}}" id="description">
                                                <span class="text-danger">@error('description') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="payable_to">Payable To<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="payable_to" value="{{ $array1->payable_to}}" id="payable_to">
                                                <span class="text-danger">@error('payable_to') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="gross_amount">Gross Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gross_amount" value="{{ $array1->gross_amount}}" id="gross_amount">
                                                <span class="text-danger">@error('gross_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="gst">GST<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gst" value="{{ $array1->gst}}" id="gst">
                                                <span class="text-danger">@error('gst') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="gst_ratio">GST Ratio<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gst_ratio" step="any" value="{{ $array1->gst_ratio}}" id="gst_ratio">
                                                <span class="text-danger">@error('gst_ratio') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="income_tax">Income Tax<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="income_tax" value="{{ $array1->income_tax}}" id="income_tax">
                                                <span class="text-danger">@error('income_tax') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="net_amount">Net amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="net_amount" value="{{ $array1->net_amount}}" id="net_amount">
                                                <input type="hidden" class="form-control" name="old_amount" value="{{ $array1->net_amount}}" id="old_amount">
                                                <span class="text-danger">@error('net_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="balance">Balance Available<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="balance" value="{{ $array1->balance}}" id="balance">
                                                <span class="text-danger">@error('balance') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="amount_date">Amount date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="amount_date" value="{{ $array1->amount_date}}" id="amount_date">
                                                <span class="text-danger">@error('amount_date') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
                @else
                <?php
                    if(isset($head_category)){ $head_category = $head_category;} else{ $head_category = '';}
                    if(isset($head)){ $head = $head;} else{ $head = '';}
                    if(isset($main_head)){ $main_head = $main_head;} else{ $main_head = '';}
                    if(isset($sub_head)){ $sub_head = $sub_head;} else{ $sub_head = '';}
                ?>
                <div class="row h-100">                    
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <span class="text-muted text-uppercase fs-12 fw-bold">This Financial Year Remaining Balance</span>
                                        <h3 class="mb-0">
                                            <?php
                                                $query = DB::table("over_all_budgets")
                                                ->sum('balance_amount');
                                                echo 'Rs '.number_format($query);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <span class="text-muted text-uppercase fs-12 fw-bold">{{ $quarter}} Remaining Balance</span>
                                        <h3 class="mb-0">
                                            <?php
                                                $query = DB::table("quarterly_budgets")
                                                ->where('quarterly','=',$quarter)
                                                ->sum('quarterly_balance_amount');
                                                echo 'Rs '.number_format($query);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <span class="text-muted text-uppercase fs-12 fw-bold">Head Category Remaining Balance</span>
                                        <h3 class="mb-0">
                                            <?php
                                                
                                                $query = DB::table("head_categories_budgets")
                                                ->where('headcategoryid','=',$head_category)
                                                ->where('head_category_financial_year','=',$financial_year)
                                                ->where('head_category_quarter','=',$quarter)
                                                ->sum('head_category_budget_balance');
                                                echo 'Rs '.number_format($query);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <span class="text-muted text-uppercase fs-12 fw-bold">Head Remaining Balance</span>
                                        <h3 class="mb-0">
                                            <?php
                                                
                                                $query1 = DB::table("heads_budgets")
                                                ->where('headid','=',$head)
                                                ->where('head_financial_year','=',$financial_year)
                                                ->where('head_quarter','=',$quarter)
                                                ->sum('head_budget_balance');
                                                echo 'Rs '.number_format($query1);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <span class="text-muted text-uppercase fs-12 fw-bold">Main Head Remaining Balance</span>
                                        <h3 class="mb-0">
                                            <?php
                                                
                                                $query2 = DB::table("main_head_budgets")
                                                ->where('mainheadid','=',$main_head)
                                                ->where('main_head_financial_year','=',$financial_year)
                                                ->where('main_head_quarter','=',$quarter)
                                                ->sum('main_head_budget_balance');
                                                echo 'Rs '.number_format($query2);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <span class="text-muted text-uppercase fs-12 fw-bold">Sub Head Remaining Balance</span>
                                        <h3 class="mb-0">
                                            <?php
                                                
                                                $query3 = DB::table("sub_head_budgets")
                                                ->where('subheadid','=',$sub_head)
                                                ->where('sub_head_financial_year','=',$financial_year)
                                                ->where('sub_head_quarter','=',$quarter)
                                                ->sum('sub_head_budget_balance');
                                                echo 'Rs '.number_format($query3);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row h-100">
                    <div class="col-lg-12">
                        <form action="{{ route('third-party-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <input type="hidden" value="{{ $head_category}}" name="headcategoryid">
                                <input type="hidden" value="{{ $head}}" name="headid">
                                <input type="hidden" value="{{ $main_head}}" name="mainheadid">
                                <input type="hidden" value="{{ $sub_head}}" name="subheadid">
                                <?php
                                    $ledger = DB::table('ledgers')
                                    ->where('ledger_financialyear','=',$financial_year)
                                    ->orderBy('autoledgerid','desc')
                                    ->offset(0)->limit(1)
                                    ->get();
                                ?>
                                @foreach($ledger as $ledger)
                                    <input type="hidden" class="form-control" name="ledger_balance_amount" value="{{ $ledger->balance_amount}}" id="ledger_balance_amount">
                                @endforeach
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-lg-6">
                                                <label for="bill_no">Bill No<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="bill_no" value="{{ old('bill_no')}}" id="bill_no">
                                                <span class="text-danger">@error('bill_no') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="description">Description of Payment<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="description" value="{{ old('description')}}" id="description">
                                                <span class="text-danger">@error('description') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="payable_to">Payable To<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="payable_to" value="{{ old('payable_to')}}" id="payable_to">
                                                <span class="text-danger">@error('payable_to') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="gross_amount">Gross Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gross_amount" value="{{ old('gross_amount')}}" id="gross_amount">
                                                <span class="text-danger">@error('gross_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="gst">GST Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gst" value="{{ old('gst')}}" id="gst">
                                                <span class="text-danger">@error('gst') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="gst_ratio">GST Ratio<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gst_ratio" step="any" value="{{ old('gst_ratio')}}" id="gst_ratio">
                                                <span class="text-danger">@error('gst_ratio') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="income_tax">Income Tax<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="income_tax" value="{{ old('income_tax')}}" id="income_tax">
                                                <span class="text-danger">@error('income_tax') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="net_amount">Net amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="net_amount" value="{{ old('net_amount')}}" id="net_amount">
                                                <span class="text-danger">@error('net_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="balance">Balance Available<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="balance" value="{{ old('balance')}}" id="balance">
                                                <span class="text-danger">@error('balance') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="amount_date">Amount date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="amount_date" value="{{ old('amount_date')}}" id="amount_date">
                                                <span class="text-danger">@error('amount_date') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
            
        </div>
        @include('layouts.footer')