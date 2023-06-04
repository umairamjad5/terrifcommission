        @include('layouts.header')
        @include('layouts.left')
        <div class="content">
            <div class="container-fluid">

                <!-- start page title -->
                
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('delete-multiple-third-parties')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    @if(!empty($array1))
                                    @foreach($array1 as $row)
                                    <table border="0" width="100%">
                                        <tr>
                                            <th style="text-align:center;padding:5px;">
                                                National Tariff Commission <br> Islamabad
                                            </th>
                                        </tr>
                                    </table>
                                    <table border="0" width="100%">
                                        <tr>
                                            <th style="text-align:center;padding:5px;">
                                                Payment Order
                                            </th>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="50%">Bill No</td>
                                            <td width="50%">{{ $row->bill_no}}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Description of Payment</td>
                                            <td width="50%">
                                                {{ $row->description}}
                                                <br>
                                                Payable To:- {{$row->payable_to}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Head of Account</td>
                                            <td width="50%">
                                                <?php
                                                    $q = DB::table('sub_heads')->where('autosubheadid','=',$row->subheadid)->get();
                                                    foreach($q as $q);
                                                    echo $q->sub_head_code;
                                                ?>                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Gross Amount</td>
                                            <td width="50%">Rs {{ number_format($row->gross_amount)}}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Amount of GST</td>
                                            <td width="50%">Rs {{ number_format($row->gst)}}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">GST withhold @ 0.2</td>
                                            <td width="50%">{{ $row->gst_ratio}}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Income tax @ 4.5%</td>
                                            <td width="50%">Rs {{ number_format($row->income_tax)}}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Net Amount</td>
                                            <td width="50%">Rs {{ number_format($row->net_amount)}}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Balance Available</td>
                                            <td width="50%">Rs {{ number_format($row->balance)}}</td>
                                        </tr>
                                    </table>
                                    <table border="0" width="100%">
                                        <tr>
                                            <th style="text-align:left;">
                                                LP=
                                            </th>
                                        </tr>
                                    </table>
                                    <table border="0" width="100%" style="margin-top:20px">
                                        <tr>
                                            <th width="50%" style="text-align:center;">
                                                <u>Accounts Section</u>
                                            </th>
                                            <th width="50%" style="text-align:center;">
                                                <u>D.D.O Section</u>
                                            </th>
                                        </tr>
                                    </table>
                                    <table border="0" width="100%">
                                        <tr>
                                            <td width="48%">
                                                <table width="100%" style="margin-top:5px">
                                                    <tr>
                                                        <td width="35%">
                                                            Bills Prepared by:
                                                        </td>
                                                        <td width="65%" style="border-bottom:0.5px solid black;"></td>
                                                    </tr>
                                                </table>
                                                <table width="100%" style="margin-top:5px">
                                                    <tr>
                                                        <td width="35%">
                                                            Bills Checked by:
                                                        </td>
                                                        <td width="65%" style="border-bottom:0.5px solid black;"></td>
                                                    </tr>
                                                </table>
                                                <table width="100%" style="margin-top:5px">
                                                    <tr>
                                                        <td width="35%">
                                                            Accounts Officer:
                                                        </td>
                                                        <td width="65%" style="border-bottom:0.5px solid black;"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td width="4%"></td>
                                            <td width="48%">
                                                <table width="100%" style="margin-top:5px">
                                                    <tr>
                                                        <td width="65%">
                                                            Drawing and Disbursing Officer:
                                                        </td>
                                                        <td width="35%" style="border-bottom:0.5px solid black;"></td>
                                                    </tr>
                                                </table>
                                                <table width="100%" style="margin-top:5px">
                                                    <tr>
                                                        <td width="25%">
                                                            Cheaque No:
                                                        </td>
                                                        <td width="25%" style="border-bottom:0.5px solid black;"></td>
                                                        <td width="20%">
                                                            Date:
                                                        </td>
                                                        <td width="30%" style="border-bottom:0.5px solid black;"></td>
                                                    </tr>
                                                </table>
                                                <table width="100%" style="margin-top:5px">
                                                    <tr>
                                                        <td width="50%">
                                                            Cheaque Prepared by:
                                                        </td>
                                                        <td width="50%" style="border-bottom:0.5px solid black;"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <table border="1" width="40%" style="margin-top:70px;boder:1px solid blue;">
                                        <tr>
                                            <td width="100%" colspan="2" style="text-align:center;border:1px solid blue;padding:5px;"><b>National Tariff Commission</b></td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Gross Amount</td>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Rs {{ number_format($row->gross_amount)}}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Amount of GST</td>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Rs {{ number_format($row->gst)}}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">GST withhold @ 0.2</td>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">{{ $row->gst_ratio}}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Income tax @ 4.5%</td>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Rs {{ number_format($row->income_tax)}}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Net Amount Rs</td>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Rs {{ number_format($row->net_amount)}}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Net Amount</td>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">
                                                <?php
                                                    $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                                    echo $digit->format($row->net_amount);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Balance Available</td>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Rs {{ number_format($row->balance)}}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Bills Prepared by</td>
                                            <td width="50%" style="border:1px solid blue;padding:5px;"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Bills Checked by</td>
                                            <td width="50%" style="border:1px solid blue;padding:5px;"></td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="border:1px solid blue;padding:5px;">Accounts Officer</td>
                                            <td width="50%" style="border:1px solid blue;padding:5px;"></td>
                                        </tr>
                                    </table>
                                    @endforeach
                                    @endif   
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