        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row mt-4"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Employee GP Balance</h4>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th></th>
                                        <th>Employee</th>
                                        <th>Balance Amount</th>
                                    </tr>
                                    <?php
                                        $array = DB::table("employees")
                                        ->where('employee_scale','!=','25')
                                        ->where('employee_scale','!=','26')
                                        ->get();
                                        $count = 1;
                                    ?>
                                    @if(!empty($array))
                                    @foreach($array as $row)
                                    <tr>
                                        <td>{{ $count++}}</td>
                                        <td>
                                            {{ $row->autoemployeeid}}
                                            --------
                                            {{ $row->employee_name}}
                                        </td>
                                        <td>
                                            <?php
                                                $array1 = DB::table("g_p_fund_subscription_balances")
                                                ->where('employeeid','=',$row->autoemployeeid)
                                                ->get();
                                                if(count($array1) == 1)
                                                {
                                                    foreach($array1 as $row1);
                                                    echo number_format($row1->gp_fund_balance);
                                                }  
                                                else
                                                {
                                                    // echo 'Required Balances';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->

            </div>
            <!-- container-fluid -->
        </div>
        
        @include('layouts.footer')