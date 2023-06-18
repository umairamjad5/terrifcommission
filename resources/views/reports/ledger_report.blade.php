        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Ledger</h4>
                        </div>
                    </div>
                </div>
                @if(empty($array))
                <div class="row">
                    <div class="col-lg-12 m-auto">
                        <form action="{{ route('ledger-db')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Ledger</h4>
                                </div>
                                <div class="card-body">
                                    <div class="live-preview">
                                        <div class="row gy-4">
                                            <div class="col-lg-5">
                                                <label for="start_date">Start Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name='start_date' value="{{ old('start_date')}}">
                                                <span class="text-danger">@error('start_date') {{ $message}} @enderror</span>
                                            </div>
                                            <div class="col-lg-5">
                                                <label for="end_date">End Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name='end_date' value="{{ old('end_date')}}">
                                                <span class="text-danger">@error('end_date') {{ $message}} @enderror</span>
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
                                    Ledge
                                </h4>
                                <div class="flex-shrink-0">
                                    <div class="form-check form-switch form-switch-right form-switch-md">
                                        <button type="button" class="btn btn-soft-success" onclick="exportReport()"><i class="uil-print"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table" id="ExportTable">
                                    <tr>
                                        <th></th>
                                        <th>Date</th>
                                        <th>Debit Head</th>
                                        <th>Debit Amount</th>
                                        <th>Description</th>
                                        <th>Credit Head</th>
                                        <th>Credit Amount</th>
                                        <th>Description</th>
                                        <th>Balance</th>
                                    </tr>
                                    @foreach($array as $row)
                                    <tr>
                                        <td>{{$row->autoledgerid}}</td>
                                        <td>{{ $row->amount_date}}</td>
                                        <td>
                                            @if($row->subheadid != 0)
                                            @php
                                                $arr = \App\Models\SubHead::where('autosubheadid',$row->subheadid)->first();
                                            @endphp
                                            {{$arr->sub_head_name}} ({{$arr->sub_head_code}})
                                            @endif
                                        </td>
                                        <td>{{ number_format($row->debit_amount)}}</td>
                                        <td>{{ $row->description}} <br>({{ $row->payable_to}})</td>
                                        <td>Bank</td>
                                        <td>{{ number_format($row->credit_amount)}}</td>
                                        <td>{{ $row->description_from}}</td>
                                        <td>{{ number_format($row->balance_amount)}}</td>
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