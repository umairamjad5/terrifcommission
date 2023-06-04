        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Action Employee Deductions</h4>
                        </div>
                    </div>
                </div>
                @if(!empty($array1))
                @foreach($array1 as $array1)
                <div class="row h-100">
                    <div class="col-lg-12">
                        <form action="{{ route('update-employee-deduction-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <input type="hidden" value="{{ $array1->autoemployeedeductionid}}" name="autoemployeedeductionid">
                                <input type="hidden" value="{{ $array1->employeeid}}" name="employeeid">
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-lg-12">
                                                <label for="description">Description Head<span class="text-danger">*</span></label>
                                                <select name="deduction_head" id="deduction_head" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('employee_deduction_heads')
                                                        ->orderby('autoemployeedeductionheadid')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autoemployeedeductionheadid}}" <?php if($query->autoemployeedeductionheadid == $array1->deductionheadid) echo 'selected';?>>{{ $query->deduction_head_code.' - '.$query->deduction_head_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('deduction_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="description">Description<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="description" value="{{ $array1->description}}" id="description">
                                                <span class="text-danger">@error('description') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="deduction_amount">Deduction Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="deduction_amount" value="{{ $array1->deduction_amount}}" id="deduction_amount">
                                                <span class="text-danger">@error('amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="deduction_date">Deduction Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="deduction_date" value="{{ $array1->deduction_date}}" id="deduction_date">
                                                <span class="text-danger">@error('deduction_date') {{ $message}} @enderror</span>
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
                @endforeach
                @else
                <div class="row h-100">
                    <div class="col-lg-12">
                        <form action="{{ route('employee-deduction-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <input type="hidden" value="{{ $employeeid}}" name="employeeid">
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-lg-12">
                                                <label for="description">Description Head<span class="text-danger">*</span></label>
                                                <select name="deduction_head" id="deduction_head" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('employee_deduction_heads')
                                                        ->orderby('autoemployeedeductionheadid')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autoemployeedeductionheadid}}" <?php if($query->autoemployeedeductionheadid == old('deduction_head')) echo 'selected';?>>{{ $query->deduction_head_code.' - '.$query->deduction_head_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('deduction_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="description">Description</label>
                                                <input type="text" class="form-control" name="description" value="{{ old('description')}}" id="description">
                                                <span class="text-danger">@error('description') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="deduction_amount">Deduction Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="deduction_amount" value="{{ old('deduction_amount')}}" id="deduction_amount">
                                                <span class="text-danger">@error('deduction_amount') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="deduction_date">Deduction Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="deduction_date" value="{{ old('deduction_date')}}" id="deduction_date">
                                                <span class="text-danger">@error('deduction_date') {{ $message}} @enderror</span>
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