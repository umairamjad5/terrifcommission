        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row mt-4"></div>
                <div class="row">
                    <div class="col-lg-12 m-auto">
                        <form action="{{ route('gp-subscription-db')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">GP Subscription</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-xl-4">
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
                                            <div class="col-lg-4">
                                                <label for="closing_bal">Closing Balance<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="closing_bal">
                                                <span class="text-danger">@error('closing_bal') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="insjul">Installment<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="insjul">
                                                <span class="text-danger">@error('insjul') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="insjul">Withdrawl Installment</label>
                                                <input type="number" class="form-control" name="withdrawl">
                                                <span class="text-danger">@error('withdrawl') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="insjul">Transfer Amount</label>
                                                <input type="number" class="form-control" name="transfer">
                                                <span class="text-danger">@error('transfer') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="insjul">Withdrawl Amount</label>
                                                <input type="number" class="form-control" name="widamount">
                                                <span class="text-danger">@error('widamount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-12">
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
                <!--end row-->

            </div>
            <!-- container-fluid -->
        </div>
        
        @include('layouts.footer')