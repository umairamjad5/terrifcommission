        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">NBP Report</h4>
                        </div>
                    </div>
                </div>
                @if(empty($month))
                <div class="row">
                    <div class="col-lg-12 m-auto">
                        <form action="{{ route('nbp-report-db')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">NBP Report</h4>
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
                                    NBP Report
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
                                                    <th class="text-center">Sr.No</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Designation</th>
                                                    <th class="text-center">Account Number</th>
                                                    <th class="text-center">Amount (Rs)</th>
                                                </tr>
                                                <?php
                                                    $count = 1;
                                                    $q = DB::table("employee_designations")
                                                    ->where('bps_name','>','16')
                                                    ->orderby('bps_name','desc')
                                                    ->get();
                                                ?>
                                                @if(!empty($q))
                                                @foreach($q as $q)
                                                    <?php
                                                        $q1 = DB::table('employees')
                                                        ->where('employee_designation','=',$q->autoemployeedesignationid)
                                                        ->get();
                                                    ?>
                                                    @if(!empty($q1))
                                                    @foreach($q1 as $q1)
                                                        <?php
                                                            $bank = explode('-',$q1->bank_account_detail);
                                                        ?>
                                                        @if($bank[0] == 'NBP')
                                                        <tr>
                                                            <td>{{ $count++}}</td>
                                                            <td>{{ $q1->employee_name}}</td>
                                                            <td>{{ $q->designation_name}}</td>
                                                            <td>{{ $bank[1]}}</td>
                                                            <td>
                                                                <?php
                                                                    $q2 = DB::table('employee_salary_reports')
                                                                    ->where('employeeid','=',$q1->autoemployeeid)
                                                                    ->where('salary_month','=',$month)
                                                                    ->get();
                                                                    if(count($q2) > 0)
                                                                    {
                                                                        foreach($q2 as $q2);
                                                                        $total_all = $q2->basic_pay + $q2->qualification_all + $q2->personal_pay + $q2->house_rent_all + $q2->medical_charges + $q2->conveyance_all + $q2->special_all + $q2->adhoc_relief + $q2->disparity_reduction_21 + $q2->disparity_reduction_22 + $q2->medical_all + $q2->integrated_all + $q2->additional_charges;
                                                                        $total_all_ded = $q2->others + $q2->income_tax + $q2->ben_fund + $q2->gpf_subs + $q2->gpf_adv + $q2->lfp + $q2->motorcar_adv + $q2->motorcycle_adv + $q2->hb_adv;
                                                                        $net_total = $total_all - $total_all_ded;
                                                                        echo number_format($net_total);
                                                                    }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                @endforeach
                                                @endif
                                                <tr>
                                                    <th class="text-center">Sr.No</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Designation</th>
                                                    <th class="text-center">Account Number</th>
                                                    <th class="text-center">Amount (Rs)</th>
                                                </tr>
                                                <?php
                                                    $count = 1;
                                                    $q = DB::table("employee_designations")
                                                    ->where('bps_name','>=','01')
                                                    ->where('bps_name','<=','16')
                                                    ->orderby('bps_name','desc')
                                                    ->get();
                                                ?>
                                                @if(!empty($q))
                                                @foreach($q as $q)
                                                    <?php
                                                        $q1 = DB::table('employees')
                                                        ->where('employee_designation','=',$q->autoemployeedesignationid)
                                                        ->get();
                                                    ?>
                                                    @if(!empty($q1))
                                                    @foreach($q1 as $q1)
                                                        <?php
                                                            $bank = explode('-',$q1->bank_account_detail);
                                                        ?>
                                                        @if($bank[0] == 'NBP')
                                                        <tr>
                                                            <td>{{ $count++}}</td>
                                                            <td>{{ $q1->employee_name}}</td>
                                                            <td>{{ $q->designation_name}}</td>
                                                            <td>{{ $bank[1]}}</td>
                                                            <td>
                                                                <?php
                                                                    $q2 = DB::table('employee_salary_reports')
                                                                    ->where('employeeid','=',$q1->autoemployeeid)
                                                                    ->where('salary_month','=',$month)
                                                                    ->get();
                                                                    if(count($q2) > 0)
                                                                    {
                                                                        foreach($q2 as $q2);
                                                                        $total_all = $q2->basic_pay + $q2->qualification_all + $q2->personal_pay + $q2->house_rent_all + $q2->medical_charges + $q2->conveyance_all + $q2->special_all + $q2->adhoc_relief + $q2->disparity_reduction_21 + $q2->disparity_reduction_22 + $q2->medical_all + $q2->integrated_all + $q2->additional_charges;
                                                                        $total_all_ded = $q2->others + $q2->income_tax + $q2->ben_fund + $q2->gpf_subs + $q2->gpf_adv + $q2->lfp + $q2->motorcar_adv + $q2->motorcycle_adv + $q2->hb_adv;
                                                                        $net_total = $total_all - $total_all_ded;
                                                                        echo number_format($net_total);
                                                                    }
                                                                ?>
                                                            </td>
                                                        </tr>
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