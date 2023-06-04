        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Action Employee Allowances</h4>
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
                <!-- <div class="row h-100">
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
                </div> -->
                <div class="row h-100">
                    <div class="col-lg-12">
                        <form action="{{ route('update-employee-allowance-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <input type="hidden" value="{{ $array1->autoemployeeallowanceid}}" name="autoemployeeallowanceid">
                                <input type="hidden" value="{{ $array1->employeeid}}" name="employeeid">
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-lg-6">
                                                <label for="sub_head_enter_expenditure">Sub Head<span class="text-danger">*</span></label>
                                                <select name="sub_head" id="sub_head_enter_expenditure" class="form-control select2">
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
                                                <span class="text-danger">@error('sub_head') {{ $message}} @enderror</span>
                                            </div>
                                            <!-- <div class="col-lg-6">
                                                <label for="description">Description<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="description" value="{{ $array1->description}}" id="description">
                                                <span class="text-danger">@error('description') {{ $message}} @enderror</span>
                                            </div> -->
                                            <div class="col-lg-6">
                                                <label for="allowance_amount">Allowance Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="allowance_amount" value="{{ $array1->allowance_amount}}" id="allowance_amount">
                                                <input type="hidden" class="form-control" name="old_allowance_amount" value="{{ $array1->allowance_amount}}" id="old_allowance_amount">
                                                <span class="text-danger">@error('amount') {{ $message}} @enderror</span>
                                            </div>
                                            <!-- <div class="col-lg-6">
                                                <label for="allowance_date">Allowance Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="allowance_date" value="{{ $array1->allowance_date}}" id="allowance_date">
                                                <span class="text-danger">@error('allowance_date') {{ $message}} @enderror</span>
                                            </div> -->
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
                @endforeach
                @else
                <?php
                    if(isset($head_category)){ $head_category = $head_category;} else{ $head_category = '';}
                    if(isset($head)){ $head = $head;} else{ $head = '';}
                    if(isset($main_head)){ $main_head = $main_head;} else{ $main_head = '';}
                    if(isset($sub_head)){ $sub_head = $sub_head;} else{ $sub_head = '';}
                ?>
                <!-- <div class="row h-100">                    
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
                </div> -->
                <div class="row h-100">
                    <div class="col-lg-12">
                        <form action="{{ route('multiple-employee-allowance-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <?php
                                                for($i=1;$i<=12;$i++)
                                                {
                                            ?>
                                            <input type="hidden" value="{{ $autoemployeeid}}" name="employeeid">
                                            <div class="col-lg-6">
                                                <label for="sub_head_enter_expenditure{{ $i}}">Sub Head<span class="text-danger">*</span></label>
                                                <select name="sub_head[]" id="sub_head_enter_expenditure{{ $i}}" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('sub_heads')->orderby('autosubheadid')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autosubheadid}}" <?php if($query->autosubheadid == old('sub_head')) echo 'selected';?>>{{ $query->sub_head_code.'-'.$query->sub_head_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('sub_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="allowance_amount">Allowance Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="allowance_amount[]" value="{{ old('allowance_amount')}}" id="allowance_amount">
                                                <span class="text-danger">@error('allowance_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <?php
                                                }
                                            ?>
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