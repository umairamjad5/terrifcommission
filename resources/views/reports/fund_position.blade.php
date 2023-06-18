        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Fund Position</h4>
                        </div>
                    </div>
                </div>
                @if(empty($financial_year))
                <div class="row">
                    <div class="col-lg-12 m-auto">
                        <form action="{{ route('fund-position-db')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Get Fund Position</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-lg-10">
                                                <label for="financial_year">Select Financial Year<span class="text-danger">*</span></label>
                                                <select name="financial_year" id="financial_year" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <option value="2022 - 2023" <?php if(old('financial_year') == '2022 - 2023') echo 'selected';?>>2022 - 2023</option>    
                                                </select>
                                                <span class="text-danger">@error('financial_year') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="end_date" class="text-white">Submtit Report<span class="text-white">*</span></label>
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end col-->
                </div>
                @else
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">
                                    Fund Position in {{ $financial_year}}
                                </h4>
                                <div class="flex-shrink-0">
                                    <div class="form-check form-switch form-switch-right form-switch-md">
                                        <button type="button" class="btn btn-soft-success" onclick="exportReport()"><i class="uil-print"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="ExportTable">
                                                <tr>
                                                    <th class="text-center" rowspan="2">Function Code</th>
                                                    <th class="text-center" rowspan="2">Function Description</th>
                                                    <th class="text-center" colspan="4">Released Funds</th>
                                                    <th class="text-center" rowspan="2">Total Release</th>
                                                    <th class="text-center" colspan="12">Details of Expenditure (Amount Rs).</th>
                                                    <th class="text-center" colspan="2">Balance</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">1st Quarter</th>
                                                    <th class="text-center">2nd Quarter</th>
                                                    <th class="text-center">3rd Quarter</th>
                                                    <th class="text-center">4th Quarter</th>
                                                    <th class="text-center">July</th>
                                                    <th class="text-center">August</th>
                                                    <th class="text-center">September</th>
                                                    <th class="text-center">October</th>
                                                    <th class="text-center">November</th>
                                                    <th class="text-center">December</th>
                                                    <th class="text-center">January</th>
                                                    <th class="text-center">February</th>
                                                    <th class="text-center">March</th>
                                                    <th class="text-center">April</th>
                                                    <th class="text-center">May</th>
                                                    <th class="text-center">June</th>
                                                    <th class="text-center">Balance w.r.t Release Budget</th>
                                                    <th class="text-center">Balance w.r.t Final Budget</th>
                                                </tr>
                                                <?php
                                                    $thisyearmonths = date('08',time());

                                                    $financial = explode(' - ',$financial_year);
                                                    
                                                    $financial_yearone = $financial[0];
                                                    $financial_yeartwo = $financial[1];

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

                                                    $q = DB::table("head_categories")
                                                    ->orderby('head_category_code','asc')
                                                    ->get();
                                                ?>
                                                @if(!empty($q))
                                                @foreach($q as $q)
                                                <!-- Head categories -->
                                                <tr>
                                                    <td style="background-color:#ededed;font-weight:bold;">{{ $q->head_category_code}}</td>
                                                    <td style="background-color:#ededed;font-weight:bold;">{{ $q->head_category_name}}</td>
                                                    <td>
                                                        <?php
                                                            $q_1 = DB::table("head_categories_budgets")
                                                            ->where('head_category_financial_year','=',$financial_year)
                                                            ->where('head_category_quarter','=','1st Quarter')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->get();
                                                            if(count($q_1) > 0)
                                                            {
                                                                foreach($q_1 as $q_1);
                                                                echo 'Rs'.number_format($q_1->head_category_budget_amount);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_2 = DB::table("head_categories_budgets")
                                                            ->where('head_category_financial_year','=',$financial_year)
                                                            ->where('head_category_quarter','=','2nd Quarter')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->get();
                                                            if(count($q_2) > 0)
                                                            {
                                                                foreach($q_2 as $q_2);
                                                                echo 'Rs'.number_format($q_2->head_category_budget_amount);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_3 = DB::table("head_categories_budgets")
                                                            ->where('head_category_financial_year','=',$financial_year)
                                                            ->where('head_category_quarter','=','3rd Quarter')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->get();
                                                            if(count($q_3) > 0)
                                                            {
                                                                foreach($q_3 as $q_3);
                                                                echo 'Rs'.number_format($q_3->head_category_budget_amount);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_4 = DB::table("head_categories_budgets")
                                                            ->where('head_category_financial_year','=',$financial_year)
                                                            ->where('head_category_quarter','=','4th Quarter')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->get();
                                                            if(count($q_4) > 0)
                                                            {
                                                                foreach($q_4 as $q_4);
                                                                echo 'Rs'.number_format($q_4->head_category_budget_amount);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_5 = DB::table("head_categories_balanaces")
                                                            ->where('head_category_financial_year','=',$financial_year)
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->get();
                                                            if(count($q_5) > 0)
                                                            {
                                                                foreach($q_5 as $q_5);
                                                                echo 'Rs'.number_format($q_5->head_category_total_amount);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_6 = DB::table("expenditures")
                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                            ->where('amount_date','like',$financial_yearone.'-07%')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->sum('net_amount');
                                                            if(!empty($q_6))
                                                            {
                                                                $sumexpenditure[] = $q_6;
                                                                echo 'Rs'.number_format($q_6);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_7 = DB::table("expenditures")
                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                            ->where('amount_date','like',$financial_yearone.'-08%')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->sum('net_amount');
                                                            if(!empty($q_7))
                                                            {
                                                                echo 'Rs'.number_format($q_7);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_8 = DB::table("expenditures")
                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                            ->where('amount_date','like',$financial_yearone.'-09%')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->sum('net_amount');
                                                            if(!empty($q_8))
                                                            {
                                                                echo 'Rs'.number_format($q_8);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_9 = DB::table("expenditures")
                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                            ->where('amount_date','like',$financial_yearone.'-10%')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->sum('net_amount');
                                                            if(!empty($q_9))
                                                            {
                                                                echo 'Rs'.number_format($q_9);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_10 = DB::table("expenditures")
                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                            ->where('amount_date','like',$financial_yearone.'-11%')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->sum('net_amount');
                                                            if(!empty($q_10))
                                                            {
                                                                echo 'Rs'.number_format($q_10);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_11 = DB::table("expenditures")
                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                            ->where('amount_date','like',$financial_yearone.'-12%')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->sum('net_amount');
                                                            if(!empty($q_11))
                                                            {
                                                                echo 'Rs'.number_format($q_11);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_12 = DB::table("expenditures")
                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                            ->where('amount_date','like',$financial_yeartwo.'-01%')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->sum('net_amount');
                                                            if(!empty($q_12))
                                                            {
                                                                echo 'Rs'.number_format($q_12);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_13 = DB::table("expenditures")
                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                            ->where('amount_date','like',$financial_yeartwo.'-02%')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->sum('net_amount');
                                                            if(!empty($q_13))
                                                            {
                                                                echo 'Rs'.number_format($q_13);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_14 = DB::table("expenditures")
                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                            ->where('amount_date','like',$financial_yeartwo.'-03%')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->sum('net_amount');
                                                            if(!empty($q_14))
                                                            {
                                                                echo 'Rs'.number_format($q_14);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_15 = DB::table("expenditures")
                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                            ->where('amount_date','like',$financial_yeartwo.'-04%')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->sum('net_amount');
                                                            if(!empty($q_15))
                                                            {
                                                                echo 'Rs'.number_format($q_15);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_16 = DB::table("expenditures")
                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                            ->where('amount_date','like',$financial_yeartwo.'-05%')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->sum('net_amount');
                                                            if(!empty($q_16))
                                                            {
                                                                echo 'Rs'.number_format($q_16);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_17 = DB::table("expenditures")
                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                            ->where('amount_date','like',$financial_yeartwo.'-06%')
                                                            ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                            ->sum('net_amount');
                                                            if(!empty($q_17))
                                                            {
                                                                echo 'Rs'.number_format($q_17);
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_18 = DB::table("quarterly_budgets")
                                                            ->where('financialyear','=',$financial_year)
                                                            ->where('quarterly','=',$quarter)
                                                            ->sum('quarterly_amount');
                                                            if(!empty($q_18))
                                                            {
                                                                $q_18_1 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_year.'%')
                                                                ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                                ->sum('net_amount');
                                                                if(!empty($q_18_1))
                                                                {
                                                                    echo 'Rs'.number_format($q_18 - $q_18_1);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $q_19 = DB::table("sum_bank_balances")
                                                            ->where('bankfinancialyear','=',$financial_year)
                                                            ->sum('bank_sum_amount');
                                                            if(!empty($q_19))
                                                            {
                                                                $q_19_1 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_year.'%')
                                                                ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                                ->sum('net_amount');
                                                                if(!empty($q_19_1))
                                                                {
                                                                    echo 'Rs'.number_format($q_19 - $q_19_1);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            }
                                                            else
                                                            {
                                                                echo '';
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <!-- Heads -->
                                                    <?php
                                                        $q1 = DB::table("heads")
                                                        ->where('headcategoryid','=',$q->autoheadcategoryid)
                                                        ->orderby('head_code','asc')
                                                        ->get();
                                                    ?>
                                                    @if(!empty($q1))
                                                    @foreach($q1 as $q1)
                                                    @if($q->head_category_code != $q1->head_code)
                                                    <tr>
                                                        <td style="background-color:#d0cece;font-weight:bold;">{{ $q1->head_code}}</td>
                                                        <td style="background-color:#d0cece;font-weight:bold;">{{ $q1->head_name}}</td>
                                                        <td>
                                                            <?php
                                                                $q1_1 = DB::table("heads_budgets")
                                                                ->where('head_financial_year','=',$financial_year)
                                                                ->where('head_quarter','=','1st Quarter')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->get();
                                                                if(count($q1_1) > 0)
                                                                {
                                                                    foreach($q1_1 as $q1_1);
                                                                    echo 'Rs'.number_format($q1_1->head_budget_amount);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_2 = DB::table("heads_budgets")
                                                                ->where('head_financial_year','=',$financial_year)
                                                                ->where('head_quarter','=','2nd Quarter')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->get();
                                                                if(count($q1_2) > 0)
                                                                {
                                                                    foreach($q1_2 as $q1_2);
                                                                    echo 'Rs'.number_format($q1_2->head_budget_amount);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_3 = DB::table("heads_budgets")
                                                                ->where('head_financial_year','=',$financial_year)
                                                                ->where('head_quarter','=','3rd Quarter')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->get();
                                                                if(count($q1_3) > 0)
                                                                {
                                                                    foreach($q1_3 as $q1_3);
                                                                    echo 'Rs'.number_format($q1_3->head_budget_amount);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_4 = DB::table("heads_budgets")
                                                                ->where('head_financial_year','=',$financial_year)
                                                                ->where('head_quarter','=','4th Quarter')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->get();
                                                                if(count($q1_4) > 0)
                                                                {
                                                                    foreach($q1_4 as $q1_4);
                                                                    echo 'Rs'.number_format($q1_4->head_budget_amount);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_5 = DB::table("heads_balanaces")
                                                                ->where('head_financial_year','=',$financial_year)
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->get();
                                                                if(count($q1_5) > 0)
                                                                {
                                                                    foreach($q1_5 as $q1_5);
                                                                    echo 'Rs'.number_format($q1_5->head_total_amount);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_6 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_yearone.'-07%')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->sum('net_amount');
                                                                if(!empty($q1_6))
                                                                {
                                                                    $sumexpenditure[] = $q1_6;
                                                                    echo 'Rs'.number_format($q1_6);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_7 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_yearone.'-08%')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->sum('net_amount');
                                                                if(!empty($q1_7))
                                                                {
                                                                    echo 'Rs'.number_format($q1_7);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_8 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_yearone.'-09%')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->sum('net_amount');
                                                                if(!empty($q1_8))
                                                                {
                                                                    echo 'Rs'.number_format($q1_8);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_9 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_yearone.'-10%')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->sum('net_amount');
                                                                if(!empty($q1_9))
                                                                {
                                                                    echo 'Rs'.number_format($q1_9);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_10 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_yearone.'-11%')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->sum('net_amount');
                                                                if(!empty($q1_10))
                                                                {
                                                                    echo 'Rs'.number_format($q1_10);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_11 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_yearone.'-12%')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->sum('net_amount');
                                                                if(!empty($q1_11))
                                                                {
                                                                    echo 'Rs'.number_format($q1_11);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_12 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_yeartwo.'-01%')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->sum('net_amount');
                                                                if(!empty($q1_12))
                                                                {
                                                                    echo 'Rs'.number_format($q1_12);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_13 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_yeartwo.'-02%')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->sum('net_amount');
                                                                if(!empty($q1_13))
                                                                {
                                                                    echo 'Rs'.number_format($q1_13);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_14 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_yeartwo.'-03%')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->sum('net_amount');
                                                                if(!empty($q1_14))
                                                                {
                                                                    echo 'Rs'.number_format($q1_14);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_15 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_yeartwo.'-04%')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->sum('net_amount');
                                                                if(!empty($q1_15))
                                                                {
                                                                    echo 'Rs'.number_format($q1_15);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_16 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_yeartwo.'-05%')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->sum('net_amount');
                                                                if(!empty($q1_16))
                                                                {
                                                                    echo 'Rs'.number_format($q1_16);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_17 = DB::table("expenditures")
                                                                ->where('expenditure_financialyear','=',$financial_year)
                                                                ->where('amount_date','like',$financial_yeartwo.'-06%')
                                                                ->where('headid','=',$q1->autoheadid)
                                                                ->sum('net_amount');
                                                                if(!empty($q1_17))
                                                                {
                                                                    echo 'Rs'.number_format($q1_17);
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_18 = DB::table("quarterly_budgets")
                                                                ->where('financialyear','=',$financial_year)
                                                                ->where('quarterly','=',$quarter)
                                                                ->sum('quarterly_amount');
                                                                if(!empty($q1_18))
                                                                {
                                                                    $q1_18_1 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_year.'%')
                                                                    ->where('headid','=',$q1->autoheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q1_18_1))
                                                                    {
                                                                        echo 'Rs'.number_format($q1_18 - $q1_18_1);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $q1_19 = DB::table("sum_bank_balances")
                                                                ->where('bankfinancialyear','=',$financial_year)
                                                                ->sum('bank_sum_amount');
                                                                if(!empty($q1_19))
                                                                {
                                                                    $q1_19_1 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_year.'%')
                                                                    ->where('headid','=',$q1->autoheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q1_19_1))
                                                                    {
                                                                        echo 'Rs'.number_format($q1_19 - $q1_19_1);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                }
                                                                else
                                                                {
                                                                    echo '';
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <!-- Main Heads -->
                                                        <?php
                                                            $q2 = DB::table("main_heads")
                                                            ->where('headid','=',$q1->autoheadid)
                                                            ->orderby('main_head_code','asc')
                                                            ->get();
                                                        ?>
                                                        @if(!empty($q2))
                                                        @foreach($q2 as $q2)
                                                        <tr>
                                                            <td style="background-color:#ededed;font-weight:bold;">{{ $q2->main_head_code}}</td>
                                                            <td style="background-color:#ededed;font-weight:bold;">{{ $q2->main_head_name}}</td>
                                                            <td>
                                                                <?php
                                                                    $q2_1 = DB::table("main_head_budgets")
                                                                    ->where('main_head_financial_year','=',$financial_year)
                                                                    ->where('main_head_quarter','=','1st Quarter')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->get();
                                                                    if(count($q2_1) > 0)
                                                                    {
                                                                        foreach($q2_1 as $q2_1);
                                                                        echo 'Rs'.number_format($q2_1->main_head_budget_amount);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_2 = DB::table("main_head_budgets")
                                                                    ->where('main_head_financial_year','=',$financial_year)
                                                                    ->where('main_head_quarter','=','2nd Quarter')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->get();
                                                                    if(count($q2_2) > 0)
                                                                    {
                                                                        foreach($q2_2 as $q2_2);
                                                                        echo 'Rs'.number_format($q2_2->main_head_budget_amount);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_3 = DB::table("main_head_budgets")
                                                                    ->where('main_head_financial_year','=',$financial_year)
                                                                    ->where('main_head_quarter','=','3rd Quarter')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->get();
                                                                    if(count($q2_3) > 0)
                                                                    {
                                                                        foreach($q2_3 as $q2_3);
                                                                        echo 'Rs'.number_format($q2_3->main_head_budget_amount);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_4 = DB::table("main_head_budgets")
                                                                    ->where('main_head_financial_year','=',$financial_year)
                                                                    ->where('main_head_quarter','=','4th Quarter')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->get();
                                                                    if(count($q2_4) > 0)
                                                                    {
                                                                        foreach($q2_4 as $q2_4);
                                                                        echo 'Rs'.number_format($q2_4->main_head_budget_amount);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_5 = DB::table("main_heads_balanaces")
                                                                    ->where('main_head_financial_year','=',$financial_year)
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->get();
                                                                    if(count($q2_5) > 0)
                                                                    {
                                                                        foreach($q2_5 as $q2_5);
                                                                        echo 'Rs'.number_format($q2_5->main_head_total_amount);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_6 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_yearone.'-07%')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q2_6))
                                                                    {
                                                                        echo 'Rs'.number_format($q2_6);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_7 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_yearone.'-08%')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q2_7))
                                                                    {
                                                                        echo 'Rs'.number_format($q2_7);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_8 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_yearone.'-09%')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q2_8))
                                                                    {
                                                                        echo 'Rs'.number_format($q2_8);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_9 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_yearone.'-10%')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q2_9))
                                                                    {
                                                                        echo 'Rs'.number_format($q2_9);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_10 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_yearone.'-11%')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q2_10))
                                                                    {
                                                                        echo 'Rs'.number_format($q2_10);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_11 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_yearone.'-12%')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q2_11))
                                                                    {
                                                                        echo 'Rs'.number_format($q2_11);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_12 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_yeartwo.'-01%')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q2_12))
                                                                    {
                                                                        echo 'Rs'.number_format($q2_12);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_13 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_yeartwo.'-02%')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q2_13))
                                                                    {
                                                                        echo 'Rs'.number_format($q2_13);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_14 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_yeartwo.'-03%')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q2_14))
                                                                    {
                                                                        echo 'Rs'.number_format($q2_14);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_15 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_yeartwo.'-04%')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q2_15))
                                                                    {
                                                                        echo 'Rs'.number_format($q2_15);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_16 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_yeartwo.'-05%')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q2_16))
                                                                    {
                                                                        echo 'Rs'.number_format($q2_16);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_17 = DB::table("expenditures")
                                                                    ->where('expenditure_financialyear','=',$financial_year)
                                                                    ->where('amount_date','like',$financial_yeartwo.'-06%')
                                                                    ->where('mainheadid','=',$q2->automainheadid)
                                                                    ->sum('net_amount');
                                                                    if(!empty($q2_17))
                                                                    {
                                                                        echo 'Rs'.number_format($q2_17);
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_18 = DB::table("quarterly_budgets")
                                                                    ->where('financialyear','=',$financial_year)
                                                                    ->where('quarterly','=',$quarter)
                                                                    ->sum('quarterly_amount');
                                                                    if(!empty($q2_18))
                                                                    {
                                                                        $q2_18_1 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_year.'%')
                                                                        ->where('mainheadid','=',$q2->automainheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q2_18_1))
                                                                        {
                                                                            echo 'Rs'.number_format($q2_18 - $q2_18_1);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $q2_19 = DB::table("sum_bank_balances")
                                                                    ->where('bankfinancialyear','=',$financial_year)
                                                                    ->sum('bank_sum_amount');
                                                                    if(!empty($q2_19))
                                                                    {
                                                                        $q2_19_1 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_year.'%')
                                                                        ->where('mainheadid','=',$q2->automainheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q2_19_1))
                                                                        {
                                                                            echo 'Rs'.number_format($q2_19 - $q2_19_1);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '';
                                                                    }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <!-- Sub Heads -->
                                                            <?php
                                                                $q3 = DB::table("sub_heads")
                                                                ->where('mainheadid','=',$q2->automainheadid)
                                                                ->orderby('sub_head_code','asc')
                                                                ->get();
                                                            ?>
                                                            @if(!empty($q3))
                                                            @foreach($q3 as $q3)
                                                            <tr>
                                                                <td>{{ $q3->sub_head_code}}</td>
                                                                <td>{{ $q3->sub_head_name}}</td>
                                                                <td>
                                                                    <?php
                                                                        $q3_1 = DB::table("sub_head_budgets")
                                                                        ->where('sub_head_financial_year','=',$financial_year)
                                                                        ->where('sub_head_quarter','=','1st Quarter')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->get();
                                                                        if(count($q3_1) > 0)
                                                                        {
                                                                            foreach($q3_1 as $q3_1);
                                                                            echo 'Rs'.number_format($q3_1->sub_head_budget_amount);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_2 = DB::table("sub_head_budgets")
                                                                        ->where('sub_head_financial_year','=',$financial_year)
                                                                        ->where('sub_head_quarter','=','2nd Quarter')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->get();
                                                                        if(count($q3_2) > 0)
                                                                        {
                                                                            foreach($q3_2 as $q3_2);
                                                                            echo 'Rs'.number_format($q3_2->sub_head_budget_amount);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_3 = DB::table("sub_head_budgets")
                                                                        ->where('sub_head_financial_year','=',$financial_year)
                                                                        ->where('sub_head_quarter','=','3rd Quarter')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->get();
                                                                        if(count($q3_3) > 0)
                                                                        {
                                                                            foreach($q3_3 as $q3_3);
                                                                            echo 'Rs'.number_format($q3_3->sub_head_budget_amount);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_4 = DB::table("sub_head_budgets")
                                                                        ->where('sub_head_financial_year','=',$financial_year)
                                                                        ->where('sub_head_quarter','=','4th Quarter')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->get();
                                                                        if(count($q3_4) > 0)
                                                                        {
                                                                            foreach($q3_4 as $q3_4);
                                                                            echo 'Rs'.number_format($q3_4->sub_head_budget_amount);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_5 = DB::table("sub_heads_balanaces")
                                                                        ->where('sub_head_financial_year','=',$financial_year)
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->get();
                                                                        if(count($q3_5) > 0)
                                                                        {
                                                                            foreach($q3_5 as $q3_5);
                                                                            echo 'Rs'.number_format($q3_5->sub_head_total_amount);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_6 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_yearone.'-07%')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q3_6))
                                                                        {
                                                                            echo 'Rs'.number_format($q3_6);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_7 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_yearone.'-08%')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q3_7))
                                                                        {
                                                                            echo 'Rs'.number_format($q3_7);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_8 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_yearone.'-09%')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q3_8))
                                                                        {
                                                                            echo 'Rs'.number_format($q3_8);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_9 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_yearone.'-10%')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q3_9))
                                                                        {
                                                                            echo 'Rs'.number_format($q3_9);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_10 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_yearone.'-11%')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q3_10))
                                                                        {
                                                                            echo 'Rs'.number_format($q3_10);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_11 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_yearone.'-12%')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q3_11))
                                                                        {
                                                                            echo 'Rs'.number_format($q3_11);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_12 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_yeartwo.'-01%')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q3_12))
                                                                        {
                                                                            echo 'Rs'.number_format($q3_12);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_13 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_yeartwo.'-02%')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q3_13))
                                                                        {
                                                                            echo 'Rs'.number_format($q3_13);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_14 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_yeartwo.'-03%')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q3_14))
                                                                        {
                                                                            echo 'Rs'.number_format($q3_14);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_15 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_yeartwo.'-04%')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q3_15))
                                                                        {
                                                                            echo 'Rs'.number_format($q3_15);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_16 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_yeartwo.'-05%')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q3_16))
                                                                        {
                                                                            echo 'Rs'.number_format($q3_16);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_17 = DB::table("expenditures")
                                                                        ->where('expenditure_financialyear','=',$financial_year)
                                                                        ->where('amount_date','like',$financial_yeartwo.'-06%')
                                                                        ->where('subheadid','=',$q3->autosubheadid)
                                                                        ->sum('net_amount');
                                                                        if(!empty($q3_17))
                                                                        {
                                                                            echo 'Rs'.number_format($q3_17);
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_18 = DB::table("quarterly_budgets")
                                                                        ->where('financialyear','=',$financial_year)
                                                                        ->where('quarterly','=',$quarter)
                                                                        ->sum('quarterly_amount');
                                                                        if(!empty($q3_18))
                                                                        {
                                                                            $q3_18_1 = DB::table("expenditures")
                                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                                            ->where('amount_date','like',$financial_year.'%')
                                                                            ->where('subheadid','=',$q3->autosubheadid)
                                                                            ->sum('net_amount');
                                                                            if(!empty($q3_18_1))
                                                                            {
                                                                                echo 'Rs'.number_format($q3_18 - $q3_18_1);
                                                                            }
                                                                            else
                                                                            {
                                                                                echo '';
                                                                            }
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $q3_19 = DB::table("sum_bank_balances")
                                                                        ->where('bankfinancialyear','=',$financial_year)
                                                                        ->sum('bank_sum_amount');
                                                                        if(!empty($q3_19))
                                                                        {
                                                                            $q3_19_1 = DB::table("expenditures")
                                                                            ->where('expenditure_financialyear','=',$financial_year)
                                                                            ->where('amount_date','like',$financial_year.'%')
                                                                            ->where('subheadid','=',$q3->autosubheadid)
                                                                            ->sum('net_amount');
                                                                            if(!empty($q3_19_1))
                                                                            {
                                                                                echo 'Rs'.number_format($q3_19 - $q3_19_1);
                                                                            }
                                                                            else
                                                                            {
                                                                                echo '';
                                                                            }
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            @endif
                                                        @endforeach
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                @endforeach
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!--end row-->

            </div>
            <!-- container-fluid -->
        </div>
        
        @include('layouts.footer')