        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Third Party Bills</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('delete-multiple-third-parties')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">List Third Party Bills</h4>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch form-switch-right form-switch-md">
                                            <button type="submit" onclick="return confirm('Are you sure')" class="btn btn-soft-danger"><i class="uil-trash-alt"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4 mb-3">
                                        <div class="col-sm-auto">
                                            <div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="basic-datatable" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="select-all" id="select-all">
                                                            <label class="form-check-label" for="select-all"></label>
                                                        </div>
                                                    </th>
                                                    <th>Head Category Code</th>
                                                    <th>Head Code</th>
                                                    <th>Main Head Code</th>
                                                    <th>Sub Head Code</th>
                                                    <th>Bill No</th>
                                                    <th>Description of Payment</th>
                                                    <th>Payable To</th>
                                                    <th>Gross Amount</th>
                                                    <th>GST</th>
                                                    <th>GST Ratio</th>
                                                    <th>Income Tax</th>
                                                    <th>Net amount</th>
                                                    <th>Balance Available</th>
                                                    <th>Amount date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($array))
                                                @foreach($array as $row)
                                                <tr>
                                                    <td scope="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="id[]" value="{{ $row->autothirdpartyid}}" id="headcheckid">
                                                            <label class="form-check-label" for="headcheckid"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $row->head_category_code}}</td>
                                                    <td>{{ $row->head_code}}</td>
                                                    <td>{{ $row->main_head_code}}</td>
                                                    <td>{{ $row->sub_head_code}}</td>
                                                    <td>{{ $row->bill_no}}</td>
                                                    <td>{{ $row->description}}</td>
                                                    <td>{{ $row->payable_to}}</td>
                                                    <td>Rs {{ number_format($row->gross_amount)}}</td>
                                                    <td>Rs {{ number_format($row->gst)}}</td>
                                                    <td>{{ $row->gst_ratio}}</td>
                                                    <td>Rs {{ number_format($row->income_tax)}}</td>
                                                    <td>Rs {{ number_format($row->net_amount)}}</td>
                                                    <td>Rs {{ number_format($row->balance_available)}}</td>
                                                    <td>{{ $row->amount_date}}</td>
                                                    <td>
                                                        <div class="hstack gap-3 fs-15">
                                                            <a href="{{ url('/invoice_third_party/'.$row->autothirdpartyid)}}" class="link-primary"><i class='uil uil-print'></i></a>
                                                            <a href="{{ url('/update_third_party/'.$row->autothirdpartyid)}}" class="link-primary"><i class='uil uil-comment-edit'></i></a>
                                                            <a href="{{ url('/delete_third_party/'.$row->autothirdpartyid)}}" onclick="return confirm('Are you sure')" class="link-danger"><i class='uil-trash-alt'></i></a>
                                                        </div>
                                                    </td>
                                                </tr>  
                                                @endforeach
                                                @endif                                      
                                            </tbody>
                                        </table>
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