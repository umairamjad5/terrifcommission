        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Motor Car Loan Report</h4>
                        </div>
                    </div>
                </div>
                @if(empty($array))
                <div class="row">
                    <div class="col-lg-12 m-auto">
                        <form action="{{ route('motorcar-loan-report-db')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Motor Car Loan Report</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xl-10">
                                                <label for="employee">Employee<span class="text-danger">*</span></label>
                                                <select class="form-control select2" name="employee" id="employee">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('employees')->orderby('autoemployeeid')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autoemployeeid}}" <?php if($query->autoemployeeid == old('employeeid')) echo 'selected';?>>{{ $query->employee_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('employee') {{ $message}} @enderror</span>
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
                                <h4 class="card-title mb-0 flex-grow-1">Employee Motor Car Deduction Report</h4>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th></th>
                                        <th>Month</th>
                                        <th>Deduction Amount</th>
                                        <th>Prograssive Amount</th>
                                        <th>Balance Amount</th>
                                    </tr>
                                    @foreach($array as $row)
                                    <tr>
                                        <td></td>
                                        <td>{{ date('F Y',strtotime($row->motorcar_deduction_month))}}</td>
                                        <td>{{ number_format($row->motorcar_deduction_amount)}}</td>
                                        <td>{{ number_format($row->motorcar_deduction_prograssive)}}</td>
                                        <td>{{ number_format($row->motorcar_deduction_rem_bal)}}</td>
                                    </tr>
                                    @endforeach
                                </table>
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