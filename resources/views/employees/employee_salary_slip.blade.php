        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Salary Slip for {{ date('M-Y',strtotime($salary_month))}}</h4>
                            <?php
                                $main_query = DB::table('employee_salary_slips')
                                ->where('employeeid','=',$employeeid)
                                ->where('salary_month','=',$salary_month)
                                ->get();
                                if(count($main_query) == 1)
                                {
                            ?>
                                <a class="btn btn-danger" onclick="return confirm('Are you sure')" href="{{ url('/delete_employee_salary_slip/'.$employeeid.'/'.$salary_month)}}">Delete Salary Slip</a>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12 m-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">
                                    <?php
                                    $main_query = DB::table('employee_salary_slips')
                                    ->where('employeeid','=',$employeeid)
                                    ->where('salary_month','=',$salary_month)
                                    ->get();
                                    if(count($main_query) == 1)
                                    {
                                        $query = DB::table("employees")
                                        ->where("autoemployeeid",'=',$employeeid)
                                        ->get();
                                    ?>
                                    @if(!empty($query))
                                    @foreach($query as $query)
                                    <div class="row gy-4">
                                        <div class="col-lg-3">
                                            <img src="{{ asset('assets/images/logo.png')}}" alt="logo">
                                        </div>
                                        <div class="col-lg-6">
                                            <center>
                                                <b>Government of Pakistan</b><br>
                                                <b>Ministry of Commerce</b><br>
                                                <b>National Tariff Commission</b>
                                            </center>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-lg-12">
                                            <table width="100%">
                                                <tr>
                                                    <th width="30%">Name:</th>
                                                    <th width="70%">{{ $query->employee_name}}</th>
                                                </tr>
                                                <tr>
                                                    <th width="30%">Designation:</th>
                                                    <td width="70%">
                                                        <?php
                                                            $q1 = DB::table('employee_designations')
                                                            ->where('autoemployeedesignationid','=',$query->employee_designation)
                                                            ->get();
                                                            foreach($q1 as $q1);
                                                            echo $q1->designation_name.' (BPS-'.$q1->bps_name.')';
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="30%">Scale:</th>
                                                    <td width="70%">
                                                        <?php
                                                            $q2 = DB::table('employee_pay_scales')
                                                            ->where('autoemployeepayscaleid','=',$query->employee_scale)
                                                            ->get();
                                                            foreach($q2 as $q2);
                                                            echo $q2->bps_scale;
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="30%">Date of Entry into Goveronment Service:</th>
                                                    <td width="70%">{{ date('d-m-Y',strtotime($query->date_of_joining))}}</td>
                                                </tr>
                                                <tr>
                                                    <th width="30%">Length of Service:</th>
                                                    <td width="70%">
                                                        <?php
                                                            $date1 = new DateTime(date('Y-m-d',strtotime($query->date_of_joining)));
                                                            $date2 = new DateTime(date('Y-m-d',time()));
                                                            $interval = $date1->diff($date2);
                                                            echo $interval->y . " years, " . $interval->m." months, ".$interval->d." days "; 

                                                            // shows the total amount of days (not divided into years, months and days like above)
                                                            // echo "difference " . $interval->days . " days ";
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan='2' align='right'><b>{{ date('F-Y',strtotime($salary_month))}}</b></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table width="100%" border="1" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <th width="25%">Head</th>
                                                    <th width="35%">Payment</th>
                                                    <th width="20%">Monthly Rate</th>
                                                    <th width="20%">Amount</th>
                                                </tr>
                                            </table>
                                            <table width="100%">
                                                <?php
                                                    $query1 = DB::table("employee_allowances")
                                                    ->join('sub_heads','employee_allowances.subheadid','=','sub_heads.autosubheadid')
                                                    ->where('employee_allowances.employeeid','=',$employeeid)
                                                    ->get();
                                                ?>
                                                <tr>
                                                    <td>A01101</td>
                                                    <td>Pay</td>
                                                    <?php
                                                        $q3 = DB::table('employee_pay_scales')
                                                        ->where('autoemployeepayscaleid','=',$query->employee_scale)
                                                        ->get();
                                                        foreach($q3 as $q3);
                                                        $emp_scale_salary = $q3->minimum_pay;
                                                        if($query->employee_stages > $q3->scale_stages)
                                                        {
                                                            $emp_scale_increment = $q3->scale_stages * $q3->increment_amount;
                                                        }
                                                        else
                                                        {
                                                            $emp_scale_increment = $query->employee_stages * $q3->increment_amount;
                                                        }
                                                    ?>
                                                    <td>
                                                        @if($query->basic_salary == '0')
                                                            <?php
                                                                $emp_salary = $emp_scale_salary + $emp_scale_increment;
                                                                echo number_format($emp_salary);
                                                            ?>
                                                        @else
                                                            <?php
                                                                $emp_salary = $query->basic_salary;
                                                                echo number_format($emp_salary);                                                            
                                                            ?>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($query->basic_salary == '0')
                                                            <?php
                                                                $emp_salary = $emp_scale_salary + $emp_scale_increment;
                                                                echo number_format($emp_salary);
                                                            ?>
                                                        @else
                                                            <?php
                                                                $emp_salary = $query->basic_salary;
                                                                echo number_format($emp_salary);                                                            
                                                            ?>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>A01102</td>
                                                    <td>Personal Pay</td>
                                                    <td>
                                                        @if($query->personal_salary == '0')
                                                            <?php
                                                                if($query->employee_stages > $q3->scale_stages)
                                                                {
                                                                    $diff_scale_stages = $query->employee_stages - $q3->scale_stages;
                                                                    $emp_personal_salary = $q3->increment_amount * $diff_scale_stages;
                                                                    echo number_format($emp_personal_salary);
                                                                }
                                                                else
                                                                {
                                                                    $emp_personal_salary = 0;
                                                                    echo '-';
                                                                }
                                                            ?>
                                                        @else
                                                            <?php
                                                                $emp_personal_salary = $query->personal_salary;
                                                                echo number_format($emp_personal_salary);                                                            
                                                            ?>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($query->personal_salary == '0')
                                                            <?php
                                                                if($query->employee_stages > $q3->scale_stages)
                                                                {
                                                                    $diff_scale_stages = $query->employee_stages - $q3->scale_stages;
                                                                    $emp_personal_salary = $q3->increment_amount * $diff_scale_stages;
                                                                    echo number_format($emp_personal_salary);
                                                                }
                                                                else
                                                                {
                                                                    $emp_personal_salary = 0;
                                                                    echo '-';
                                                                }
                                                            ?>
                                                        @else
                                                            <?php
                                                                $emp_personal_salary = $query->personal_salary;
                                                                echo number_format($emp_personal_salary);                                                            
                                                            ?>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if(!$query1->isEmpty())
                                                @foreach($query1 as $query1)
                                                <tr>
                                                    <td width="25%">{{ $query1->sub_head_code}}</td>
                                                    <td width="35%">{{ $query1->sub_head_name}}</td>
                                                    <td width="20%">{{ number_format($query1->allowance_amount)}}</td>
                                                    <td width="20%">{{ number_format($query1->allowance_amount)}}</td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </table>
                                            <table width="100%" border="1" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <th width="25%">Gross Claim</th>
                                                    <th width="35%"></th>
                                                    <th width="20%">
                                                        <?php
                                                            $gross_claim = '0';
                                                            $emp_allowances = DB::table("employee_allowances")->where('employeeid','=',$employeeid)->sum('allowance_amount');
                                                            $gross_claim = $emp_salary+$emp_personal_salary+$emp_allowances;
                                                            echo number_format($gross_claim);
                                                        ?>
                                                    </th>
                                                    <th width="20%" style="border-left:3px solid black;">
                                                        <?php
                                                            echo number_format($gross_claim);
                                                        ?>
                                                    </th>
                                                </tr>
                                            </table>
                                            <table width="100%">
                                                <tr>
                                                    <th width="25%"></th>
                                                    <th width="35%" style="font-size:18px;"><u>Deductions</u></th>
                                                    <th width="20%"></th>
                                                    <th width="20%"></th>
                                                </tr>
                                                <?php
                                                    $q11 = DB::table('deduction_statments')
                                                    ->where('employeeid','=',$employeeid)
                                                    ->where('month','=',$salary_month)
                                                    ->get();   
                                                    foreach($q11 as $q11);             
                                                ?>
                                                <tr>
                                                    <td width="25%">B 01141</td>
                                                    <td width="35%">Income Tax</td>
                                                    <td width="20%"></td>
                                                    <td width="20%">
                                                        @if($q11->income_tax == 0)
                                                        -
                                                        @else
                                                        {{ number_format($q11->income_tax)}}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="25%">B 06202</td>
                                                    <td width="35%">Benevolent Fund</td>
                                                    <td width="20%"></td>
                                                    <td width="20%">
                                                        @if($q11->ben_fund == 0)
                                                        -
                                                        @else
                                                        {{ number_format($q11->ben_fund)}}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="25%">B 06409</td>
                                                    <td width="35%">Group Insurance</td>
                                                    <td width="20%"></td>
                                                    <td width="20%">
                                                        @if($q11->gi_fund == 0)
                                                        -
                                                        @else
                                                        {{ number_format($q11->gi_fund)}}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="25%">B 06103</td>
                                                    <td width="35%">GP Fund Subscription</td>
                                                    <td width="20%">
                                                        <?php
                                                            $gp_fund_balance = DB::table("g_p_fund_subscription_balances")
                                                            ->where('employeeid','=',$employeeid)
                                                            ->sum('gp_fund_balance');
                                                            if(!empty($gp_fund_balance))
                                                            {
                                                                echo number_format($gp_fund_balance);
                                                            }
                                                            else
                                                            {
                                                                echo '-';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td width="20%">
                                                        @if($q3->gp_fund_advance == 0)
                                                        -
                                                        @else
                                                        {{ number_format($q3->gp_fund_advance)}}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="25%">A 08170</td>
                                                    <td width="35%">GP Advance</td>
                                                    <td width="20%">
                                                        <?php
                                                            $emp_gp_loan_bal = DB::table("employee_g_p_loan_balances")
                                                            ->where('employeeid','=',$employeeid)
                                                            ->sum('gp_balance_amount');
                                                            if(!empty($emp_gp_loan_bal))
                                                            {
                                                                echo number_format($emp_gp_loan_bal);
                                                            }
                                                            else
                                                            {
                                                                echo '-';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td width="20%">
                                                        <?php
                                                            $emp_gp_loan = DB::table("employee_g_p_loans")
                                                            ->where('employeeid','=',$employeeid)
                                                            ->where('gp_loan_type','=','Refundable')
                                                            ->get();
                                                            if(count($emp_gp_loan) > 0)
                                                            {
                                                                foreach($emp_gp_loan as $row1);
                                                                $gp_adv_ins = $row1->gp_loan_installment;
                                                                echo number_format($gp_adv_ins);
                                                            }
                                                            else
                                                            {
                                                                $gp_adv_ins = '0';
                                                                echo '-';
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="25%">E 02501</td>
                                                    <td width="35%">HB Advance</td>
                                                    <td width="20%">
                                                        <?php
                                                            $emp_hb_loan = DB::table("employee_h_b_loans")
                                                            ->where('employeeid','=',$employeeid)
                                                            ->sum('hb_approved_amount');
                                                            if(!empty($emp_hb_loan))
                                                            {
                                                                echo number_format($emp_hb_loan);
                                                            }
                                                            else
                                                            {
                                                                echo '-';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td width="20%">
                                                        <?php
                                                            $emp_hb_loan_bal = DB::table("employee_h_b_loan_balances")
                                                            ->where('employeeid','=',$employeeid)
                                                            ->sum('hb_balance_amount');
                                                            if(!empty($emp_hb_loan_bal))
                                                            {
                                                                if($emp_hb_loan_bal > $query->hb_advance)
                                                                {
                                                                    $hb_loan_deduct_amount = $query->hb_advance;
                                                                }
                                                                else
                                                                {
                                                                    $hb_loan_deduct_amount = $query->hb_advance - $emp_hb_loan_bal;
                                                                }
                                                                echo number_format($hb_loan_deduct_amount);
                                                            }
                                                            else
                                                            {
                                                                $hb_loan_deduct_amount = '0';
                                                                echo '-';
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="25%">E 02502</td>
                                                    <td width="35%">Motor Car Advance</td>
                                                    <td width="20%">
                                                        <?php
                                                            $emp_motorcar_loan = DB::table("employee_motor_car_loans")
                                                            ->where('employeeid','=',$employeeid)
                                                            ->sum('motorcar_approved_amount');
                                                            if(!empty($emp_motorcar_loan))
                                                            {
                                                                echo number_format($emp_motorcar_loan);
                                                            }
                                                            else
                                                            {
                                                                echo '-';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td width="20%">
                                                        <?php
                                                            $motorcar_installment = $emp_motorcar_loan / 60;
                                                            $emp_motorcar_loan_bal = DB::table("employee_motor_car_loan_balances")
                                                            ->where('employeeid','=',$employeeid)
                                                            ->sum('motorcar_balance_amount');
                                                            if(!empty($emp_motorcar_loan_bal))
                                                            {
                                                                if($emp_motorcar_loan_bal > $motorcar_installment)
                                                                {
                                                                    $motorcar_loan_deduct_amount = $motorcar_installment;
                                                                }
                                                                else
                                                                {
                                                                    $motorcar_loan_deduct_amount = $motorcar_installment - $emp_motorcar_loan_bal;
                                                                }
                                                                echo number_format($motorcar_loan_deduct_amount);
                                                            }
                                                            else
                                                            {
                                                                $motorcar_loan_deduct_amount = '0';
                                                                echo '-';
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="25%">E 02503</td>
                                                    <td width="35%">Motor Cycle Advance</td>
                                                    <td width="20%">
                                                        <?php
                                                            $emp_motorcycle_loan = DB::table("employee_motor_cycle_loans")
                                                            ->where('employeeid','=',$employeeid)
                                                            ->sum('motorcycle_approved_amount');
                                                            if(!empty($emp_motorcycle_loan))
                                                            {
                                                                echo number_format($emp_motorcycle_loan);
                                                            }
                                                            else
                                                            {
                                                                echo '-';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td width="20%">
                                                        <?php
                                                            $motorcycle_installment = $emp_motorcycle_loan / 60;
                                                            $emp_motorcycle_loan_bal = DB::table("employee_motor_cycle_loan_balances")
                                                            ->where('employeeid','=',$employeeid)
                                                            ->sum('motorcycle_balance_amount');
                                                            if(!empty($emp_motorcycle_loan_bal))
                                                            {
                                                                if($emp_motorcycle_loan_bal > $motorcycle_installment)
                                                                {
                                                                    $motorcycle_loan_deduct_amount = $motorcycle_installment;
                                                                }
                                                                else
                                                                {
                                                                    $motorcycle_loan_deduct_amount = $motorcycle_installment - $emp_motorcycle_loan_bal;
                                                                }
                                                                echo number_format($motorcycle_loan_deduct_amount);
                                                            }
                                                            else
                                                            {
                                                                $motorcycle_loan_deduct_amount = '0';
                                                                echo '-';
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                    $query2 = DB::table("employee_other_deductions")
                                                    ->where('otherdeduction_date','like',$salary_month.'%')
                                                    ->where("employeeid",'=',$employeeid)
                                                    ->get();
                                                ?>
                                                @if(!$query2->isEmpty())
                                                @foreach($query2 as $query2)
                                                <tr>
                                                    <td width="25%">E 02550</td>
                                                    <td width="35%">Other ({{ $query2->description}})</td>
                                                    <td width="20%">
                                                        {{ number_format($query2->otherdeduction_balance)}}
                                                    </td>
                                                    <td width="20%">
                                                        <?php
                                                            if($query2->otherdeduction_balance > $query2->otherdeduction_installment)
                                                            {
                                                                $other_deduct_amount = $query2->otherdeduction_installment;
                                                            }
                                                            else
                                                            {
                                                                $other_deduct_amount = $query2->otherdeduction_installment - $query2->otherdeduction_balance;
                                                            }
                                                        ?>
                                                        {{ number_format($other_deduct_amount)}}
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endif
                                                <tr>
                                                    <td width="25%"></td>
                                                    <td width="35%"></td>
                                                    <th width="20%">Total Deductions</th>
                                                    <td width="20%">
                                                        <?php
                                                            $emp_other_deduction_sum = DB::table("employee_other_deductions")->
                                                            where('employeeid','=',$employeeid)->
                                                            where('otherdeduction_date','like',$salary_month.'%')->
                                                            sum('otherdeduction_installment');
                                                            $all_deduction = $q11->income_tax + $q11->ben_fund + $q11->gi_fund + $q3->gp_fund_advance + $hb_loan_deduct_amount + $motorcar_loan_deduct_amount + $motorcycle_loan_deduct_amount + $emp_other_deduction_sum;
                                                            echo number_format($all_deduction);                                                        
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <?php
                                                // echo $query->income_tax.'------';
                                                // echo $benevolent_fund.'------';
                                                // echo $group_insurance.'------';
                                                // echo $q3->gp_fund_advance.'------';
                                                // echo $hb_loan_deduct_amount.'------';
                                                // echo $motorcar_loan_deduct_amount.'------';
                                                // echo $motorcycle_loan_deduct_amount.'------';
                                                // echo $emp_other_deduction_sum.'------';
                                            ?>
                                            <table width="100%" border="1" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <th width="25%"></th>
                                                    <th width="35%">Net payable Amount</th>
                                                    <th width="20%"></th>
                                                    <th width="20%" style="border-left:3px solid black;">
                                                        <?php
                                                            $net_amount = $gross_claim - $all_deduction;
                                                            echo number_format($net_amount);
                                                        ?>
                                                    </th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table width="100%">
                                                <tr>
                                                    <th width="10%">Bank Account:</th>
                                                    <td width="90%">
                                                        @if($query->bank_account_detail != 'Nill')
                                                        {{ $query->bank_account_detail}}
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                    <?php
                                    }
                                    else
                                    {
                                    ?>
                                    <div class="row gy-4">
                                        <div class="col-lg-12">
                                            <h2 class="text-danger text-center">Salary Slip is not Generated for the Month of {{ date('F Y',strtotime($salary_month))}}</h2>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    <!--end row-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

            </div>
            <!-- container-fluid -->
        </div>
        
        @include('layouts.footer')