            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu">

                <div class="h-100" data-simplebar>
                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <ul id="side-menu">

                            <li>
                                <a href="{{ url('/dashboard')}}">
                                    <i data-feather="home"></i>
                                    <span> Dashboard </span>
                                </a>
                            </li>
                            <li>
                                <a href="#heads" data-bs-toggle="collapse">
                                    <i data-feather="activity"></i>
                                    <span> Heads </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="heads">
                                    <ul class="nav-second-level">
                                        <li><a href="{{ url('/head_categories')}}"> Head Categories </a></li>
                                        <li><a href="{{ url('/heads')}}"> Heads </a></li>
                                        <li><a href="{{ url('/main_heads')}}"> Main Heads </a></li>
                                        <li><a href="{{ url('/sub_heads')}}"> Sub Heads  </a></li>
                                        <li><a href="{{ url('/employee_deduction_heads')}}"> Deduction Heads  </a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#budget" data-bs-toggle="collapse">
                                    <i data-feather="activity"></i>
                                    <span> Budget </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="budget">
                                    <ul class="nav-second-level">
                                        <li><a href="{{ url('/over_all_budgets')}}">Over All Budget </a></li>
                                        <li><a href="{{ url('/quarterly_budgets')}}">Quarterly Budget </a></li>
                                        <li><a href="{{ url('/bank_budgets')}}">Bank Budget </a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#heads_budget" data-bs-toggle="collapse">
                                    <i data-feather="activity"></i>
                                    <span> Head Wise Budget </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="heads_budget">
                                    <ul class="nav-second-level">
                                        <li><a href="{{ url('/head_categories_budgets')}}">Head Categories Budget</a></li>
                                        <li><a href="{{ url('/heads_budgets')}}">Heads Budget</a></li>
                                        <li><a href="{{ url('/main_head_budgets')}}">Main Heads Budget</a></li>
                                        <li><a href="{{ url('/sub_head_budgets')}}">Sub Heads Budget</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#reappropiations" data-bs-toggle="collapse">
                                    <i data-feather="activity"></i>
                                    <span> Reappropiations </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="reappropiations">
                                    <ul class="nav-second-level">
                                        <li><a href="{{ url('/action_reappropiations')}}">Add New</a></li>
                                        <li><a href="{{ url('/reappropiations')}}">List All</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#additional_funds" data-bs-toggle="collapse">
                                    <i data-feather="activity"></i>
                                    <span> Additionals Funds </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="additional_funds">
                                    <ul class="nav-second-level">
                                        <li><a href="{{ url('/action_additional_funds')}}">Add New</a></li>
                                        <li><a href="{{ url('/additional_funds')}}">List All</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#surrenders" data-bs-toggle="collapse">
                                    <i data-feather="activity"></i>
                                    <span> Surrenders </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="surrenders">
                                    <ul class="nav-second-level">
                                        <li><a href="{{ url('/action_surrenders')}}">Add New</a></li>
                                        <li><a href="{{ url('/surrenders')}}">List All</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#expenditures" data-bs-toggle="collapse">
                                    <i data-feather="activity"></i>
                                    <span> Ledger/Bills </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="expenditures">
                                    <ul class="nav-second-level">
                                        <li><a href="{{ url('/action_new_expenditures')}}">Add New</a></li>
                                        <li><a href="{{ url('/expenditures')}}">List All</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#payroll" data-bs-toggle="collapse">
                                    <i data-feather="activity"></i>
                                    <span> Payroll </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="payroll">
                                    <ul class="nav-second-level">
                                        <li><a href="{{ url('/action_employees')}}">Register New Employee</a></li>
                                        <li><a href="{{ url('/employees')}}">List Employees</a></li>
                                        <!-- <li><a href="{{ url('/list_employee_allowances')}}">Employee Allowances</a></li>
                                        <li><a href="{{ url('/list_employee_deductions')}}">Employee Deduction</a></li> -->
                                        <li><a href="{{ url('/employee_pay_scales')}}">Employee Pay Scales</a></li>
                                        <li><a href="{{ url('/employee_designations')}}">Employee Designations</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#loans" data-bs-toggle="collapse">
                                    <i data-feather="activity"></i>
                                    <span> Loans </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="loans">
                                    <ul class="nav-second-level">
                                        <li><a href="{{ url('/gp_loans')}}">GP Loan </a></li>
                                        <li><a href="{{ url('/hb_loans')}}">HB Loan </a></li>
                                        <li><a href="{{ url('/motorcar_loans')}}">Motor Car Loan </a></li>
                                        <li><a href="{{ url('/motorcycle_loans')}}">Motor Cycle Loan </a></li>
                                        <li><a href="{{ url('/gp_subscription')}}">GP Subscription </a></li>
                                    </ul>
                                </div>
                            </li>
                            <!-- <li>
                                <a href="#third_parties" data-bs-toggle="collapse">
                                    <i data-feather="activity"></i>
                                    <span> Third Parties </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="third_parties">
                                    <ul class="nav-second-level">
                                        <li><a href="{{ url('/validate_heads_third_party')}}">Add New</a></li>
                                        <li><a href="{{ url('/third_parties')}}">Third parties Bill List</a></li>
                                    </ul>
                                </div>
                            </li> -->
                            <li>
                                <a href="#reports" data-bs-toggle="collapse">
                                    <i data-feather="activity"></i>
                                    <span> Reports </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="reports">
                                    <ul class="nav-second-level">
                                        <li><a href="{{ url('/ledger_report')}}">Ledger Report</a></li>
                                        <li><a href="{{ url('/fund_position')}}">Fund Position</a></li>
                                        <li><a href="{{ url('/trial_balance')}}">Trial Balance</a></li>
                                        <li>
                                            <a href="#salary_report" data-bs-toggle="collapse">
                                                Salary Report <span class="menu-arrow"></span>
                                            </a>
                                            <div class="collapse" id="salary_report">
                                                <ul class="nav-second-level">
                                                    <li><a href="{{ url('officer_salary_report')}}">Officer</a></li>
                                                    <li><a href="{{ url('staff_salary_report')}}">Staff</a></li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="#bank_report" data-bs-toggle="collapse">
                                                Bank Report <span class="menu-arrow"></span>
                                            </a>
                                            <div class="collapse" id="bank_report">
                                                <ul class="nav-second-level">
                                                    <li><a href="{{ url('hbl_report')}}">HBL</a></li>
                                                    <li><a href="{{ url('nbp_report')}}">NBP</a></li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#loan_reports" data-bs-toggle="collapse">
                                    <i data-feather="activity"></i>
                                    <span> Loan Reports </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="loan_reports">
                                    <ul class="nav-second-level">
                                        <li><a href="{{ url('/hb_loan_report')}}">HB Loan Report</a></li>
                                        <li><a href="{{ url('/motorcar_loan_report')}}">Motor Car Loan Report</a></li>
                                        <li><a href="{{ url('/motorcycle_loan_report')}}">Motor Cycle Loan Report</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>

                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->
            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">