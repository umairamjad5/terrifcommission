        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Staff Salary Report</h4>
                        </div>
                    </div>
                </div>
                @if(empty($month))
                <div class="row">
                    <div class="col-lg-12 m-auto">
                        <form action="{{ route('staff-salary-report-db')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Filter Staff Salary Report</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-lg-10">
                                                <label for="month">Month<span class="text-danger">*</span></label>
                                                <input type="month" class="form-control" name='month' value="{{ old('month')}}">
                                                <span class="text-danger">@error('month') {{ $message}} @enderror</span>
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
                                    Staff Salary Report
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
                                                    <th class="text-center" rowspan="2">Sr.No</th>
                                                    <th class="text-center" rowspan="2">Name & Designation</th>
                                                    <th class="text-center" colspan="13">Gross Claim</th>
                                                    <th class="text-center" rowspan="2">Total</th>
                                                    <th class="text-center" colspan="10">Deductions</th>
                                                    <th class="text-center" rowspan="2">Net Amount</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">Basic Pay</th>
                                                    <th class="text-center">Qualification Allowance</th>
                                                    <th class="text-center">Personal Pay</th>
                                                    <th class="text-center">H.R.A</th>
                                                    <th class="text-center">Medical Charges</th>
                                                    <th class="text-center">C.A</th>
                                                    <th class="text-center">Special Allowance</th>
                                                    <th class="text-center">A.R 2022</th>
                                                    <th class="text-center">Disparity Reduction Allowance (2021)</th>
                                                    <th class="text-center">Disparity Reduction Allowance (2022)</th>
                                                    <th class="text-center">M. Allow.</th>
                                                    <th class="text-center">I.A</th>
                                                    <th class="text-center">Add/Charge Allowance </th>
                                                    <th class="text-center">Others</th>
                                                    <th class="text-center">Income Tax</th>
                                                    <th class="text-center">Ben. Fund</th>
                                                    <th class="text-center">GP Fund Subscription</th>
                                                    <th class="text-center">G.P. Fund Advance</th>
                                                    <th class="text-center">LFP</th>
                                                    <th class="text-center">Motor Car Adv</th>
                                                    <th class="text-center">Motor Cyc Adv</th>
                                                    <th class="text-center">H.B Advance</th>
                                                    <th class="text-center">Total</th>
                                                </tr>
                                                <?php
                                                    $count = 1;
                                                    $q = DB::table('employee_designations')
                                                    ->where('bps_name','>=','01')
                                                    ->where('bps_name','<=','16')
                                                    ->where('designation_name','!=','Assistant Private Secretary')
                                                    ->orderby('bps_name','desc')
                                                    ->get();
                                                ?>
                                                @if(!empty($q))
                                                @foreach($q as $q)
                                                    <tr>
                                                        <td colspan="27">{{ $q->designation_name}} (BPS-{{ $q->bps_name}})</td>
                                                    </tr>
                                                    <?php
                                                        $q1 = DB::table('employees')
                                                        ->where('employee_designation','=',$q->autoemployeedesignationid)
                                                        ->get();
                                                        
                                                    ?>
                                                    @if(!empty($q1))
                                                    @foreach($q1 as $q1)
                                                        <?php
                                                            $q2 = DB::table('employee_salary_reports')
                                                            ->where('employeeid','=',$q1->autoemployeeid)
                                                            ->where('salary_month','=',$month)
                                                            ->get();
                                                            
                                                        ?>
                                                        @if(!empty($q2))
                                                        @foreach($q2 as $q2)
                                                            <tr>
                                                                <td>{{ $count++}}</td>
                                                                <td>{{ $q1->employee_name}}</td>
                                                                <td>
                                                                    @if($q2->basic_pay != 0)
                                                                    {{ number_format($q2->basic_pay)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->qualification_all != 0)
                                                                    {{ number_format($q2->qualification_all)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->personal_pay != 0)
                                                                    {{ number_format($q2->personal_pay)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->house_rent_all != 0)
                                                                    {{ number_format($q2->house_rent_all)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->medical_charges != 0)
                                                                    {{ number_format($q2->medical_charges)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->conveyance_all != 0)
                                                                    {{ number_format($q2->conveyance_all)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->special_all != 0)
                                                                    {{ number_format($q2->special_all)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->adhoc_relief != 0)
                                                                    {{ number_format($q2->adhoc_relief)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->disparity_reduction_21 != 0)
                                                                    {{ number_format($q2->disparity_reduction_21)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->disparity_reduction_22 != 0)
                                                                    {{ number_format($q2->disparity_reduction_22)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->medical_all != 0)
                                                                    {{ number_format($q2->medical_all)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->integrated_all != 0)
                                                                    {{ number_format($q2->integrated_all)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->additional_charges != 0)
                                                                    {{ number_format($q2->additional_charges)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $total_all = $q2->basic_pay + $q2->qualification_all + $q2->personal_pay + $q2->house_rent_all + $q2->medical_charges + $q2->conveyance_all + $q2->special_all + $q2->adhoc_relief + $q2->disparity_reduction_21 + $q2->disparity_reduction_22 + $q2->medical_all + $q2->integrated_all + $q2->additional_charges;
                                                                        echo number_format($total_all);
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    @if($q2->others != 0)
                                                                    {{ number_format($q2->others)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->income_tax != 0)
                                                                    {{ number_format($q2->income_tax)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->ben_fund != 0)
                                                                    {{ number_format($q2->ben_fund)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->gpf_subs != 0)
                                                                    {{ number_format($q2->gpf_subs)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->gpf_adv != 0)
                                                                    {{ number_format($q2->gpf_adv)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->lfp != 0)
                                                                    {{ number_format($q2->lfp)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->motorcar_adv != 0)
                                                                    {{ number_format($q2->motorcar_adv)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->motorcycle_adv != 0)
                                                                    {{ number_format($q2->motorcycle_adv)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($q2->hb_adv != 0)
                                                                    {{ number_format($q2->hb_adv)}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $total_all_ded = $q2->others + $q2->income_tax + $q2->ben_fund + $q2->gpf_subs + $q2->gpf_adv + $q2->lfp + $q2->motorcar_adv + $q2->motorcycle_adv + $q2->hb_adv;
                                                                        echo number_format($total_all_ded);
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $net_total = $total_all - $total_all_ded;
                                                                        echo number_format($net_total);
                                                                    ?>
                                                                </td>
                                                            </tr>
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