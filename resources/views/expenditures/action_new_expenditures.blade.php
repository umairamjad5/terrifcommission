        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Action Expenditures</h4>
                        </div>
                    </div>
                </div>
                <?php
                    $thisyearmonths = date('02',time());
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
                    <div class="col-lg-12">
                        <form action="{{ route('update-expenditure-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <input type="hidden" value="{{ $array1->autoexpenditureid}}" name="autoexpenditureid">
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h4>Debit Head</h4>
                                            </div>
                                        </div>
                                        <?php
                                            $debitledger = DB::table('ledgers')
                                            ->where('expenditureid','=',$array1->autoexpenditureid)
                                            ->get();
                                        ?>
                                        @foreach($debitledger as $debitledger)
                                        <input type="hidden" class="form-control" name="ledger_balance_amount" value="{{ $debitledger->last_balance_amount}}" id="ledger_balance_amount">
                                        <input type="hidden" value="{{ $debitledger->autoledgerid}}" name="debitautoledgerid">
                                        @endforeach
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <label for="debit_head">Debit Head<span class="text-danger">*</span></label>
                                                <select name="debit_head" id="debit_head" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('sub_heads')->orderby('autosubheadid')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autosubheadid}}" <?php if($query->autosubheadid == $array1->subheadid) echo 'selected';?>>{{ $query->sub_head_code.'-'.$query->sub_head_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('debit_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="bill_type">Bill Type<span class="text-danger">*</span></label>
                                                <select name="bill_type" id="bill_type" class="form-control">
                                                    <option value="">Select One</option>
                                                    <option value="C/B" <?php if($array1->bill_type == "C/B") echo 'selected';?>>C/B</option>
                                                    <option value="P/B" <?php if($array1->bill_type == "P/B") echo 'selected';?>>P/B</option>
                                                </select>
                                                <span class="text-danger">@error('bill_type') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="bill_no">Bill No<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="bill_no" value="{{ $array1->bill_no}}" id="bill_no">
                                                <span class="text-danger">@error('bill_no') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="payable_to">Payable To<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="payable_to" value="{{ $array1->payable_to}}" id="payable_to">
                                                <span class="text-danger">@error('payable_to') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="description">Description of Payment<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="description" value="{{ $array1->description}}" id="description">
                                                <span class="text-danger">@error('description') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="gross_amount">Gross Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gross_amount" value="{{ $array1->gross_amount}}" id="gross_amount">
                                                <span class="text-danger">@error('gross_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="gst">GST Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gst" value="{{ $array1->gst}}" id="gst">
                                                <span class="text-danger">@error('gst') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="gst_ratio">GST Ratio<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gst_ratio" step="any" value="{{ $array1->gst_ratio}}" id="gst_ratio">
                                                <span class="text-danger">@error('gst_ratio') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="income_tax">Income Tax<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="income_tax" value="{{ $array1->income_tax}}" id="income_tax">
                                                <span class="text-danger">@error('income_tax') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="net_amount">Net amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="net_amount" value="{{ $array1->net_amount}}" id="net_amount">
                                                <input type="hidden" class="form-control" name="old_net_amount" value="{{ $array1->net_amount}}" id="old_net_amount">
                                                <span class="text-danger">@error('net_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <!-- <div class="col-lg-4">
                                                <label for="balance">Balance Available<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="balance" value="{{ $array1->balance}}" id="balance">
                                                <span class="text-danger">@error('balance') {{ $message}} @enderror</span>
                                            </div> -->
                                            <div class="col-lg-3">
                                                <label for="amount_date">Amount date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="amount_date" value="{{ $array1->amount_date}}" id="amount_date">
                                                <span class="text-danger">@error('amount_date') {{ $message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h4>Credit Head</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <label for="credit_head">Credit Head<span class="text-danger">*</span></label>
                                                <select name="credit_head" id="credit_head" class="form-control">
                                                    <option value="">Select One</option>
                                                    <option value="Bank" selected>Bank</option>
                                                </select>
                                                <span class="text-danger">@error('credit_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="payable_from">Payable From</label>
                                                <input type="text" class="form-control" name="payable_from" value="{{ $array1->payable_from}}" id="payable_from">
                                                <span class="text-danger">@error('payable_from') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="description_from">Description<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="description_from" value="{{ $array1->description_from}}" id="description_from">
                                                <span class="text-danger">@error('description_from') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="credit_amount">Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="credit_amount" value="{{ $array1->credit_amount}}" id="credit_amount">
                                                <span class="text-danger">@error('credit_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <!-- <div class="col-lg-2">
                                                <label for="credit_amount_date">Amount date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="credit_amount_date" value="{{ $array1->credit_amount_date}}" id="credit_amount_date">
                                                <span class="text-danger">@error('credit_amount_date') {{ $message}} @enderror</span>
                                            </div> -->
                                            <div class="d-flex align-items-start gap-3 mt-2">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
                @else
                <div class="row h-100">
                    <div class="col-lg-12">
                        <form action="{{ route('expenditure-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h4>Debit Head</h4>
                                            </div>
                                        </div>
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
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <label for="debit_head">Debit Head<span class="text-danger">*</span></label>
                                                <select name="debit_head" id="debit_head" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('sub_heads')->orderby('autosubheadid')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autosubheadid}}" <?php if($query->autosubheadid == old('debit_head')) echo 'selected';?>>{{ $query->sub_head_code.'-'.$query->sub_head_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('debit_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="bill_type">Bill Type<span class="text-danger">*</span></label>
                                                <select name="bill_type" id="bill_type" class="form-control">
                                                    <option value="">Select One</option>
                                                    <option value="C/B" <?php if(old('bill_type') == "C/B") echo 'selected';?>>C/B</option>
                                                    <option value="P/B" <?php if(old('bill_type') == "P/B") echo 'selected';?>>P/B</option>
                                                </select>
                                                <span class="text-danger">@error('bill_type') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="bill_no">Bill No<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="bill_no" value="{{ old('bill_no')}}" id="bill_no">
                                                <span class="text-danger">@error('bill_no') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="payable_to">Payable To<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="payable_to" value="{{ old('payable_to')}}" id="payable_to">
                                                <span class="text-danger">@error('payable_to') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="description">Description of Payment<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="description" value="{{ old('description')}}" id="description">
                                                <span class="text-danger">@error('description') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="gross_amount">Gross Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gross_amount" value="{{ old('gross_amount')}}" id="gross_amount">
                                                <span class="text-danger">@error('gross_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="gst">GST Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gst" value="{{ old('gst')}}" id="gst">
                                                <span class="text-danger">@error('gst') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="gst_ratio">GST Ratio<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="gst_ratio" step="any" value="{{ old('gst_ratio')}}" id="gst_ratio">
                                                <span class="text-danger">@error('gst_ratio') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="income_tax">Income Tax<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="income_tax" value="{{ old('income_tax')}}" id="income_tax">
                                                <span class="text-danger">@error('income_tax') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="net_amount">Net amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="net_amount" value="{{ old('net_amount')}}" id="net_amount">
                                                <span class="text-danger">@error('net_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <!-- <div class="col-lg-4">
                                                <label for="balance">Balance Available<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="balance" value="{{ old('balance')}}" id="balance">
                                                <span class="text-danger">@error('balance') {{ $message}} @enderror</span>
                                            </div> -->
                                            <div class="col-lg-3">
                                                <label for="amount_date">Amount date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="amount_date" value="{{ old('amount_date')}}" id="amount_date">
                                                <span class="text-danger">@error('amount_date') {{ $message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h4>Credit Head</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <label for="credit_head">Credit Head<span class="text-danger">*</span></label>
                                                <select name="credit_head" id="credit_head" class="form-control">
                                                    <option value="">Select One</option>
                                                    <option value="Bank" selected <?php if(old('credit_head') == 'Bank') echo 'selected';?>>Bank</option>
                                                </select>
                                                <span class="text-danger">@error('credit_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="payable_from">Payable From</label>
                                                <input type="text" class="form-control" name="payable_from" value="{{ old('payable_from')}}" id="payable_from">
                                                <span class="text-danger">@error('payable_from') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="description_from">Description<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="description_from" value="{{ old('description_from')}}" id="description_from">
                                                <span class="text-danger">@error('description_from') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="credit_amount">Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="credit_amount" value="{{ old('credit_amount')}}" id="credit_amount">
                                                <span class="text-danger">@error('credit_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <!-- <div class="col-lg-2">
                                                <label for="credit_amount_date">Amount date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="credit_amount_date" value="{{ old('credit_amount_date')}}" id="credit_amount_date">
                                                <span class="text-danger">@error('credit_amount_date') {{ $message}} @enderror</span>
                                            </div> -->
                                            <div class="d-flex align-items-start gap-3 mt-2">
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