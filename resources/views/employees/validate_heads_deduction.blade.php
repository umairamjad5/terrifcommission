        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Deductions</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-8 m-auto">
                        <form action="{{ route('add-to-deductions')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Select Heads For Deduction</h4>
                                </div>
                                <input type="hidden" value="{{ $autoemployeeid}}" name="employeeid">
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-lg-6">
                                                <label for="head_category_enter_deduction">Head Category<span class="text-danger">*</span></label>
                                                <select name="head_category" id="head_category_enter_deduction" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    <?php
                                                        $query = DB::table('head_categories')->orderby('autoheadcategoryid')->get();
                                                    ?>
                                                    @if(!empty($query))
                                                    @foreach($query as $query)
                                                    <option value="{{ $query->autoheadcategoryid}}" <?php if($query->autoheadcategoryid == old('head_category')) echo 'selected';?>>{{ $query->head_category_name}}</option>    
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger">@error('head_category') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="head_enter_deduction">Head<span class="text-danger">*</span></label>
                                                <select name="head" id="head_enter_deduction" class="form-control select2"></select>
                                                <span class="text-danger">@error('head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="main_head_enter_deduction">Main Head<span class="text-danger">*</span></label>
                                                <select name="main_head" id="main_head_enter_deduction" class="form-control select2"></select>
                                                <span class="text-danger">@error('main_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="sub_head_enter_deduction">Sub Head<span class="text-danger">*</span></label>
                                                <select name="sub_head" id="sub_head_enter_deduction" class="form-control select2"></select>
                                                <span class="text-danger">@error('sub_head') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="submit" class="btn btn-success">Enter to Deductions</button>
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