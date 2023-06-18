        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Action Reappropiations</h4>
                        </div>
                    </div>
                </div>
                <?php
                    $thisyearmonths = date('m',time());
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
                        <form action="{{ route('update-reappropiation-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Update</h4>
                                </div>
                                <input type="hidden" value="{{ $array1->autoreappropiationid}}" name="autoreappropiationid">
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label for="from_head">From Head<span class="text-danger">*</span></label>
                                                <select name="from_head" id="from_head" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('sub_heads')->where('autosubheadid',$array1->fromsubheadid)->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autosubheadid}}" <?php if($query->autosubheadid == $array1->fromsubheadid) echo 'selected';?>>{{ $query->sub_head_code.'-'.$query->sub_head_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('from_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="to_head">To Head<span class="text-danger">*</span></label>
                                                <select name="to_head" id="to_head" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('sub_heads')->where('autosubheadid',$array1->tosubheadid)->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autosubheadid}}" <?php if($query->autosubheadid == $array1->tosubheadid) echo 'selected';?>>{{ $query->sub_head_code.'-'.$query->sub_head_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('to_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="quarter">Quarter<span class="text-danger">*</span></label>
                                                <select name="quarter" id="quarter" class="form-control">
                                                    <option value="">Select One</option>
                                                    <option value="1st Quarter" <?php if($array1->reappropiation_quarter== "1st Quarter") echo 'selected';?>>1st Quarter</option>
                                                    <option value="2nd Quarter" <?php if($array1->reappropiation_quarter== "2nd Quarter") echo 'selected';?>>2nd Quarter</option>
                                                    <option value="3rd Quarter" <?php if($array1->reappropiation_quarter== "3rd Quarter") echo 'selected';?>>3rd Quarter</option>
                                                    <option value="4th Quarter" <?php if($array1->reappropiation_quarter== "4th Quarter") echo 'selected';?>>4th Quarter</option>
                                                </select>
                                                <span class="text-danger">@error('quarter') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="date">Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="date" value="{{ $array1->date}}" id="date">
                                                <span class="text-danger">@error('date') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="amount">Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="amount" value="{{ $array1->amount}}" id="amount">
                                                <input type="hidden" class="form-control" name="old_amount" value="{{ $array1->amount}}" id="old_amount">
                                                <span class="text-danger">@error('amount') {{ $message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="d-flex align-items-start gap-3 mt-2">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
                @else
                <div class="row h-100">
                    <div class="col-lg-12">
                        <form action="{{ route('reappropiation-db')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Add New</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label for="from_head">From Head<span class="text-danger">*</span></label>
                                                <select name="from_head" id="from_head" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('sub_heads')->orderby('autosubheadid')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autosubheadid}}" <?php if($query->autosubheadid == old('from_head')) echo 'selected';?>>{{ $query->sub_head_code.'-'.$query->sub_head_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('from_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="to_head">To Head<span class="text-danger">*</span></label>
                                                <select name="to_head" id="to_head" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('sub_heads')->orderby('autosubheadid')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autosubheadid}}" <?php if($query->autosubheadid == old('to_head')) echo 'selected';?>>{{ $query->sub_head_code.'-'.$query->sub_head_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('to_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="quarter">Quarter<span class="text-danger">*</span></label>
                                                <select name="quarter" id="quarter" class="form-control">
                                                    <option value="">Select One</option>
                                                    <option value="1st Quarter" <?php if(old('quarter') == "1st Quarter") echo 'selected';?>>1st Quarter</option>
                                                    <option value="2nd Quarter" <?php if(old('quarter') == "2nd Quarter") echo 'selected';?>>2nd Quarter</option>
                                                    <option value="3rd Quarter" <?php if(old('quarter') == "3rd Quarter") echo 'selected';?>>3rd Quarter</option>
                                                    <option value="4th Quarter" <?php if(old('quarter') == "4th Quarter") echo 'selected';?>>4th Quarter</option>
                                                </select>
                                                <span class="text-danger">@error('quarter') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="date">Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="date" value="{{ old('date')}}" id="date">
                                                <span class="text-danger">@error('date') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="amount">Amount<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="amount" value="{{ old('amount')}}" id="amount">
                                                <span class="text-danger">@error('amount') {{ $message}} @enderror</span>
                                            </div>
                                        </div>
                                        <div class="row">
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