<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HeadCategoryController;
use App\Http\Controllers\HeadController;
use App\Http\Controllers\MainHeadController;
use App\Http\Controllers\SubHeadController;
use App\Http\Controllers\EmployeeDeductionHeadsController;
use App\Http\Controllers\OverAllBudgetController;
use App\Http\Controllers\QuarterlyBudgetController;
use App\Http\Controllers\OverAllBankBalanceController;
use App\Http\Controllers\HeadWiseBudgetController;
use App\Http\Controllers\ExpendituresController;
use App\Http\Controllers\EmployeePayScaleController;
use App\Http\Controllers\EmployeeDesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeAllowanceController;
use App\Http\Controllers\EmployeeDeductionController;
use App\Http\Controllers\EmployeeGPLoanController;
use App\Http\Controllers\EmployeeHBLoanController;
use App\Http\Controllers\EmployeeMotorCarLoanController;
use App\Http\Controllers\EmployeeMotorCycleLoanController;
use App\Http\Controllers\ThirdPartyController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\EmployeeOtherDeductionsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// **************************           ADMIN ROUTES             ****************************** //

Route::get('/',[AdminLoginController::class,'index']);
Route::post('/getadmin',[AdminLoginController::class,'getdata'])->name('login-admin');
Route::get('/logout',[AdminLoginController::class,'logout'])->name('admin-logout');

// Dashbaord
Route::get('/dashboard',[DashboardController::class,'index']);
Route::get('/myprofile',[DashboardController::class,'myprofile']);
Route::post('/myprofiledb',[DashboardController::class,'myprofiledb'])->name('myprofiledb');
Route::post('/mypassworddb',[DashboardController::class,'mypassworddb'])->name('mypassworddb');
Route::get('/datafetchcategory',[DashboardController::class,'datafetchcategory']);
Route::get('/datafetchhead',[DashboardController::class,'datafetchhead']);
Route::get('/datafetchmainhead',[DashboardController::class,'datafetchmainhead']);
Route::get('/datafetchsubhead',[DashboardController::class,'datafetchsubhead']);
Route::get('/update_gp_loan_employee_balances',[DashboardController::class,'update_gp_loan_employee_balances']);
Route::get('/update_hb_loan_employee_balances',[DashboardController::class,'update_hb_loan_employee_balances']);
Route::get('/update_motorcar_loan_employee_balances',[DashboardController::class,'update_motorcar_loan_employee_balances']);
Route::get('/update_motorcycle_loan_employee_balances',[DashboardController::class,'update_motorcycle_loan_employee_balances']);

// Head Categories
Route::get('/head_categories',[HeadCategoryController::class,'head_categories']);
Route::get('/action_head_categories',[HeadCategoryController::class,'action_head_categories']);
Route::post('/head_category_db',[HeadCategoryController::class,'head_category_db'])->name('head-category-db');
Route::get('/update_head_category/{id}',[HeadCategoryController::class,'update_head_category']);
Route::post('/update_head_category_db',[HeadCategoryController::class,'update_head_category_db'])->name('update-head-category-db');
Route::get('/delete_head_category/{id}',[HeadCategoryController::class,'delete_head_category']);
Route::post('/delete_multiple_head_categories',[HeadCategoryController::class,'delete_multiple_head_categories'])->name('delete-multiple-head-categories');

// Heads
Route::get('/heads',[HeadController::class,'heads']);
Route::get('/action_heads',[HeadController::class,'action_heads']);
Route::post('/head_db',[HeadController::class,'head_db'])->name('head-db');
Route::get('/update_head/{id}',[HeadController::class,'update_head']);
Route::post('/update_head_db',[HeadController::class,'update_head_db'])->name('update-head-db');
Route::get('/delete_head/{id}',[HeadController::class,'delete_head']);
Route::post('/delete_multiple_heads',[HeadController::class,'delete_multiple_heads'])->name('delete-multiple-heads');

// Main Heads
Route::get('/main_heads',[MainHeadController::class,'main_heads']);
Route::get('/action_main_heads',[MainHeadController::class,'action_main_heads']);
Route::post('/main_head_db',[MainHeadController::class,'main_head_db'])->name('main-head-db');
Route::get('/update_main_head/{id}',[MainHeadController::class,'update_main_head']);
Route::post('/update_main_head_db',[MainHeadController::class,'update_main_head_db'])->name('update-main-head-db');
Route::get('/delete_main_head/{id}',[MainHeadController::class,'delete_main_head']);
Route::post('/delete_multiple_main_heads',[MainHeadController::class,'delete_multiple_main_heads'])->name('delete-multiple-main-heads');

// Sub Heads
Route::get('/sub_heads',[SubHeadController::class,'sub_heads']);
Route::get('/action_sub_heads',[SubHeadController::class,'action_sub_heads']);
Route::post('/sub_head_db',[SubHeadController::class,'sub_head_db'])->name('sub-head-db');
Route::get('/update_sub_head/{id}',[SubHeadController::class,'update_sub_head']);
Route::post('/update_sub_head_db',[SubHeadController::class,'update_sub_head_db'])->name('update-sub-head-db');
Route::get('/delete_sub_head/{id}',[SubHeadController::class,'delete_sub_head']);
Route::post('/delete_multiple_sub_heads',[SubHeadController::class,'delete_multiple_sub_heads'])->name('delete-multiple-sub-heads');

// Employee Deduction Heads
Route::get('/employee_deduction_heads',[EmployeeDeductionHeadsController::class,'employee_deduction_heads']);
Route::get('/action_employee_deduction_heads',[EmployeeDeductionHeadsController::class,'action_employee_deduction_heads']);
Route::post('/employee_deduction_head_db',[EmployeeDeductionHeadsController::class,'employee_deduction_head_db'])->name('employee-deduction-head-db');
Route::get('/update_employee_deduction_head/{id}',[EmployeeDeductionHeadsController::class,'update_employee_deduction_head']);
Route::post('/update_employee_deduction_head_db',[EmployeeDeductionHeadsController::class,'update_employee_deduction_head_db'])->name('update-employee-deduction-head-db');
Route::get('/delete_employee_deduction_head/{id}',[EmployeeDeductionHeadsController::class,'delete_employee_deduction_head']);
Route::post('/delete_multiple_employee_deduction_heads',[EmployeeDeductionHeadsController::class,'delete_multiple_employee_deduction_heads'])->name('delete-multiple-employee-deduction-heads');

// Over All Budget
Route::get('/over_all_budgets',[OverAllBudgetController::class,'over_all_budgets']);
Route::get('/action_over_all_budgets',[OverAllBudgetController::class,'action_over_all_budgets']);
Route::post('/over_all_budget_db',[OverAllBudgetController::class,'over_all_budget_db'])->name('over-all-budget-db');
Route::get('/update_over_all_budget/{id}',[OverAllBudgetController::class,'update_over_all_budget']);
Route::post('/update_over_all_budget_db',[OverAllBudgetController::class,'update_over_all_budget_db'])->name('update-over-all-budget-db');
Route::get('/delete_over_all_budget/{id}',[OverAllBudgetController::class,'delete_over_all_budget']);
Route::post('/delete_multiple_over_all_budgets',[OverAllBudgetController::class,'delete_multiple_over_all_budgets'])->name('delete-multiple-over-all-budgets');

// Quarterly Budget
Route::get('/quarterly_budgets',[QuarterlyBudgetController::class,'quarterly_budgets']);
Route::get('/action_quarterly_budgets',[QuarterlyBudgetController::class,'action_quarterly_budgets']);
Route::post('/quarterly_budget_db',[QuarterlyBudgetController::class,'quarterly_budget_db'])->name('quarterly-budget-db');
Route::get('/update_quarterly_budget/{id}',[QuarterlyBudgetController::class,'update_quarterly_budget']);
Route::post('/update_quarterly_budget_db',[QuarterlyBudgetController::class,'update_quarterly_budget_db'])->name('update-quarterly-budget-db');
Route::get('/delete_quarterly_budget/{id}',[QuarterlyBudgetController::class,'delete_quarterly_budget']);
Route::post('/delete_multiple_quarterly_budgets',[QuarterlyBudgetController::class,'delete_multiple_quarterly_budgets'])->name('delete-multiple-quarterly-budgets');

// Bank Budget
Route::get('/bank_budgets',[OverAllBankBalanceController::class,'bank_budgets']);
Route::get('/action_bank_budgets',[OverAllBankBalanceController::class,'action_bank_budgets']);
Route::post('/bank_budget_db',[OverAllBankBalanceController::class,'bank_budget_db'])->name('bank-budget-db');
Route::get('/update_bank_budget/{id}',[OverAllBankBalanceController::class,'update_bank_budget']);
Route::post('/update_bank_budget_db',[OverAllBankBalanceController::class,'update_bank_budget_db'])->name('update-bank-budget-db');
Route::get('/delete_bank_budget/{id}',[OverAllBankBalanceController::class,'delete_bank_budget']);
Route::post('/delete_multiple_bank_budgets',[OverAllBankBalanceController::class,'delete_multiple_bank_budgets'])->name('delete-multiple-bank-budgets');

// Head Categories Budget
Route::get('/head_categories_budgets',[HeadWiseBudgetController::class,'head_categories_budgets']);
Route::get('/action_head_categories_budgets',[HeadWiseBudgetController::class,'action_head_categories_budgets']);
Route::post('/head_category_db_budget',[HeadWiseBudgetController::class,'head_category_db_budget'])->name('head-category-budget-db');
Route::get('/update_head_category_budget/{id}',[HeadWiseBudgetController::class,'update_head_category_budget']);
Route::post('/update_head_category_db_budget',[HeadWiseBudgetController::class,'update_head_category_db_budget'])->name('update-head-category-budget-db');
Route::get('/delete_head_category_budget/{id}',[HeadWiseBudgetController::class,'delete_head_category_budget']);
Route::post('/delete_multiple_head_categories_budgets',[HeadWiseBudgetController::class,'delete_multiple_head_categories_budgets'])->name('delete-multiple-head-categories-budgets');

// Heads Budget
Route::get('/heads_budgets',[HeadWiseBudgetController::class,'heads_budgets']);
Route::get('/action_heads_budgets',[HeadWiseBudgetController::class,'action_heads_budgets']);
Route::post('/head_db_budget',[HeadWiseBudgetController::class,'head_db_budget'])->name('head-budget-db');
Route::get('/update_head_budget/{id}',[HeadWiseBudgetController::class,'update_head_budget']);
Route::post('/update_head_db_budget',[HeadWiseBudgetController::class,'update_head_db_budget'])->name('update-head-budget-db');
Route::get('/delete_head_budget/{id}',[HeadWiseBudgetController::class,'delete_head_budget']);
Route::post('/delete_multiple_heads_budgets',[HeadWiseBudgetController::class,'delete_multiple_heads_budgets'])->name('delete-multiple-head-budgets');

// Main Head Budget
Route::get('/main_head_budgets',[HeadWiseBudgetController::class,'main_head_budgets']);
Route::get('/action_main_head_budgets',[HeadWiseBudgetController::class,'action_main_head_budgets']);
Route::post('/main_head_db_budget',[HeadWiseBudgetController::class,'main_head_db_budget'])->name('main-head-budget-db');
Route::get('/update_main_head_budget/{id}',[HeadWiseBudgetController::class,'update_main_head_budget']);
Route::post('/update_main_head_db_budget',[HeadWiseBudgetController::class,'update_main_head_db_budget'])->name('update-main-head-budget-db');
Route::get('/delete_main_head_budget/{id}',[HeadWiseBudgetController::class,'delete_main_head_budget']);
Route::post('/delete_multiple_main_head_budgets',[HeadWiseBudgetController::class,'delete_multiple_main_head_budgets'])->name('delete-multiple-main-head-budgets');

// Sub Head Budget
Route::get('/sub_head_budgets',[HeadWiseBudgetController::class,'sub_head_budgets']);
Route::get('/action_sub_head_budgets',[HeadWiseBudgetController::class,'action_sub_head_budgets']);
Route::post('/sub_head_db_budget',[HeadWiseBudgetController::class,'sub_head_db_budget'])->name('sub-head-budget-db');
Route::get('/update_sub_head_budget/{id}',[HeadWiseBudgetController::class,'update_sub_head_budget']);
Route::post('/update_sub_head_db_budget',[HeadWiseBudgetController::class,'update_sub_head_db_budget'])->name('update-sub-head-budget-db');
Route::get('/delete_sub_head_budget/{id}',[HeadWiseBudgetController::class,'delete_sub_head_budget']);
Route::post('/delete_multiple_sub_head_budgets',[HeadWiseBudgetController::class,'delete_multiple_sub_head_budgets'])->name('delete-multiple-sub-head-budgets');

// Expenditures
Route::get('/validate_heads_expenditure',[ExpendituresController::class,'validate_heads_expenditure']);
Route::post('/add_expenditures',[ExpendituresController::class,'add_expenditures'])->name('add-to-expenditures');
Route::get('/expenditures',[ExpendituresController::class,'expenditures']);
Route::get('/action_expenditures/{id}/{id1}/{id2}/{id3}',[ExpendituresController::class,'action_expenditures']);
Route::get('/action_new_expenditures',[ExpendituresController::class,'action_new_expenditures']);
Route::post('/expenditure_db',[ExpendituresController::class,'expenditure_db'])->name('expenditure-db');
Route::get('/update_expenditure/{id}',[ExpendituresController::class,'update_expenditure']);
Route::post('/update_expenditure_db',[ExpendituresController::class,'update_expenditure_db'])->name('update-expenditure-db');
Route::get('/delete_expenditure/{id}',[ExpendituresController::class,'delete_expenditure']);
Route::post('/delete_multiple_expenditures',[ExpendituresController::class,'delete_multiple_expenditures'])->name('delete-multiple-expenditures');

// Employee Pay Scales
Route::get('/employee_pay_scales',[EmployeePayScaleController::class,'employee_pay_scales']);
Route::get('/action_employee_pay_scales',[EmployeePayScaleController::class,'action_employee_pay_scales']);
Route::post('/employee_pay_scale_db',[EmployeePayScaleController::class,'employee_pay_scale_db'])->name('employee-pay-scale-db');
Route::get('/update_employee_pay_scale/{id}',[EmployeePayScaleController::class,'update_employee_pay_scale']);
Route::post('/update_employee_pay_scale_db',[EmployeePayScaleController::class,'update_employee_pay_scale_db'])->name('update-employee-pay-scale-db');
Route::get('/delete_employee_pay_scale/{id}',[EmployeePayScaleController::class,'delete_employee_pay_scale']);
Route::post('/delete_multiple_employee_pay_scales',[EmployeePayScaleController::class,'delete_multiple_employee_pay_scales'])->name('delete-multiple-employee-pay-scales');

// Employee Designations
Route::get('/employee_designations',[EmployeeDesignationController::class,'employee_designations']);
Route::get('/action_employee_designations',[EmployeeDesignationController::class,'action_employee_designations']);
Route::post('/employee_designation_db',[EmployeeDesignationController::class,'employee_designation_db'])->name('employee-designation-db');
Route::get('/update_employee_designation/{id}',[EmployeeDesignationController::class,'update_employee_designation']);
Route::post('/update_employee_designation_db',[EmployeeDesignationController::class,'update_employee_designation_db'])->name('update-employee-designation-db');
Route::get('/delete_employee_designation/{id}',[EmployeeDesignationController::class,'delete_employee_designation']);
Route::post('/delete_multiple_employee_designations',[EmployeeDesignationController::class,'delete_multiple_employee_designations'])->name('delete-multiple-employee-designations');

// Employee
Route::get('/employees',[EmployeeController::class,'employees']);
Route::get('/action_employees',[EmployeeController::class,'action_employees']);
Route::post('/employee_db',[EmployeeController::class,'employee_db'])->name('employee-db');
Route::get('/update_employee/{id}',[EmployeeController::class,'update_employee']);
Route::post('/update_employee_db',[EmployeeController::class,'update_employee_db'])->name('update-employee-db');
Route::get('/delete_employee/{id}',[EmployeeController::class,'delete_employee']);
Route::post('/delete_multiple_employees',[EmployeeController::class,'delete_multiple_employees'])->name('delete-multiple-employees');
Route::get('/validate_salary_slip/{id}',[EmployeeController::class,'validate_salary_slip']);
Route::post('/add_to_salary_slip',[EmployeeController::class,'add_to_salary_slip'])->name('add-to-salary-slip');
Route::get('/employee_salary_slip/{id}/{id1}',[EmployeeController::class,'employee_salary_slip']);
Route::post('/generate_salary_slips',[EmployeeController::class,'generate_salary_slips'])->name('generate-salary-slips');
Route::get('/delete_employee_salary_slip/{id}/{id1}',[EmployeeController::class,'delete_employee_salary_slip']);
Route::get('/employee_salary_slips_all/{id}',[EmployeeController::class,'employee_salary_slips_all']);

Route::get('/dummydatahbdeduction',[EmployeeController::class,'dummydatahbdeduction']);
Route::get('/dummydatamotorcardeduction',[EmployeeController::class,'dummydatamotorcardeduction']);
Route::get('/dummydatamotorcyclededuction',[EmployeeController::class,'dummydatamotorcyclededuction']);
Route::get('/checkhbdeductionbalance',[EmployeeController::class,'checkhbdeductionbalance']);
Route::get('/checkmotorcardeductionbalance',[EmployeeController::class,'checkmotorcardeductionbalance']);
Route::get('/checkmotorcycledeductionbalance',[EmployeeController::class,'checkmotorcycledeductionbalance']);
Route::get('/checkgpbalance',[EmployeeController::class,'checkgpbalance']);
Route::get('/gp_subscription',[EmployeeController::class,'gp_subscription']);
Route::post('/gp_subscription_db',[EmployeeController::class,'gp_subscription_db'])->name('gp-subscription-db');

// Employee Allowances
Route::get('/list_employee_allowances',[EmployeeAllowanceController::class,'list_employee_allowances']);
Route::get('/validate_heads_allowance/{id}',[EmployeeAllowanceController::class,'validate_heads_allowance']);
Route::post('/add_allowances',[EmployeeAllowanceController::class,'add_allowances'])->name('add-to-allowances');
Route::get('/action_allowances/{id}',[EmployeeAllowanceController::class,'action_allowances']);
Route::post('/employee_allowance_db',[EmployeeAllowanceController::class,'employee_allowance_db'])->name('employee-allowance-db');
Route::post('/multiple_employee_allowance_db',[EmployeeAllowanceController::class,'multiple_employee_allowance_db'])->name('multiple-employee-allowance-db');
Route::get('/employee_allowances/{id}',[EmployeeAllowanceController::class,'employee_allowances']);
Route::get('/update_employee_allowance/{id}/{id1}',[EmployeeAllowanceController::class,'update_employee_allowance']);
Route::post('/update_employee_allowance_db',[EmployeeAllowanceController::class,'update_employee_allowance_db'])->name('update-employee-allowance-db');
Route::get('/delete_employee_allowance/{id}/{id1}',[EmployeeAllowanceController::class,'delete_employee_allowance']);
Route::post('/delete_multiple_employee_allowances',[EmployeeAllowanceController::class,'delete_multiple_employee_allowances'])->name('delete-multiple-employee-allowances');

// Employee Deductions
Route::get('/list_employee_deductions',[EmployeeDeductionController::class,'list_employee_deductions']);
Route::get('/action_deductions/{id}',[EmployeeDeductionController::class,'action_deductions']);
Route::post('/employee_deduction_db',[EmployeeDeductionController::class,'employee_deduction_db'])->name('employee-deduction-db');
Route::get('/employee_deductions/{id}',[EmployeeDeductionController::class,'employee_deductions']);
Route::get('/update_employee_deduction/{id}/{id1}',[EmployeeDeductionController::class,'update_employee_deduction']);
Route::post('/update_employee_deduction_db',[EmployeeDeductionController::class,'update_employee_deduction_db'])->name('update-employee-deduction-db');
Route::get('/delete_employee_deduction/{id}/{id1}',[EmployeeDeductionController::class,'delete_employee_deduction']);
Route::post('/delete_multiple_employee_deductions',[EmployeeDeductionController::class,'delete_multiple_employee_deductions'])->name('delete-multiple-employee-deductions');

// Employee Other Deductions
Route::get('/list_employee_other_deductions',[EmployeeOtherDeductionsController::class,'list_employee_other_deductions']);
Route::get('/action_deductions/{id}',[EmployeeOtherDeductionsController::class,'action_deductions']);
Route::post('/employee_other_deduction_db',[EmployeeOtherDeductionsController::class,'employee_other_deduction_db'])->name('other-employee-deduction-db');
Route::get('/employee_other_deductions/{id}',[EmployeeOtherDeductionsController::class,'employee_other_deductions']);
Route::get('/update_employee_other_deduction/{id}',[EmployeeOtherDeductionsController::class,'update_employee_other_deduction']);
Route::post('/update_employee_other_deduction_db',[EmployeeOtherDeductionsController::class,'update_employee_other_deduction_db'])->name('update-other-employee-deduction-db');
Route::get('/delete_employee_other_deduction/{id}',[EmployeeOtherDeductionsController::class,'delete_employee_other_deduction']);
Route::post('/delete_multiple_employee_other_deductions',[EmployeeOtherDeductionsController::class,'delete_multiple_employee_deductions'])->name('delete-multiple-other-employee-deductions');

// GP Loans
Route::get('/gp_loans',[EmployeeGPLoanController::class,'gp_loans']);
Route::get('/action_gp_loans',[EmployeeGPLoanController::class,'action_gp_loans']);
Route::post('/gp_loan_db',[EmployeeGPLoanController::class,'gp_loan_db'])->name('gp-loan-db');
Route::get('/update_gp_loan/{id}',[EmployeeGPLoanController::class,'update_gp_loan']);
Route::post('/update_gp_loan_db',[EmployeeGPLoanController::class,'update_gp_loan_db'])->name('update-gp-loan-db');
Route::get('/delete_gp_loan/{id}',[EmployeeGPLoanController::class,'delete_gp_loan']);
Route::post('/delete_multiple_gp_loans',[EmployeeGPLoanController::class,'delete_multiple_gp_loans'])->name('delete-multiple-gp-loans');
Route::get('/chechurl/{id}',[EmployeeGPLoanController::class,'chechurl']);

// HB Loans
Route::get('/hb_loans',[EmployeeHBLoanController::class,'hb_loans']);
Route::get('/action_hb_loans',[EmployeeHBLoanController::class,'action_hb_loans']);
Route::post('/hb_loan_db',[EmployeeHBLoanController::class,'hb_loan_db'])->name('hb-loan-db');
Route::get('/update_hb_loan/{id}',[EmployeeHBLoanController::class,'update_hb_loan']);
Route::post('/update_hb_loan_db',[EmployeeHBLoanController::class,'update_hb_loan_db'])->name('update-hb-loan-db');
Route::get('/delete_hb_loan/{id}',[EmployeeHBLoanController::class,'delete_hb_loan']);
Route::post('/delete_multiple_hb_loans',[EmployeeHBLoanController::class,'delete_multiple_hb_loans'])->name('delete-multiple-hb-loans');

// Motor Car Loans
Route::get('/motorcar_loans',[EmployeeMotorCarLoanController::class,'motorcar_loans']);
Route::get('/action_motorcar_loans',[EmployeeMotorCarLoanController::class,'action_motorcar_loans']);
Route::post('/motorcar_loan_db',[EmployeeMotorCarLoanController::class,'motorcar_loan_db'])->name('motorcar-loan-db');
Route::get('/update_motorcar_loan/{id}',[EmployeeMotorCarLoanController::class,'update_motorcar_loan']);
Route::post('/update_motorcar_loan_db',[EmployeeMotorCarLoanController::class,'update_motorcar_loan_db'])->name('update-motorcar-loan-db');
Route::get('/delete_motorcar_loan/{id}',[EmployeeMotorCarLoanController::class,'delete_motorcar_loan']);
Route::post('/delete_multiple_motorcar_loans',[EmployeeMotorCarLoanController::class,'delete_multiple_motorcar_loans'])->name('delete-multiple-motorcar-loans');

// Motor Cycle Loans
Route::get('/motorcycle_loans',[EmployeeMotorCycleLoanController::class,'motorcycle_loans']);
Route::get('/action_motorcycle_loans',[EmployeeMotorCycleLoanController::class,'action_motorcycle_loans']);
Route::post('/motorcycle_loan_db',[EmployeeMotorCycleLoanController::class,'motorcycle_loan_db'])->name('motorcycle-loan-db');
Route::get('/update_motorcycle_loan/{id}',[EmployeeMotorCycleLoanController::class,'update_motorcycle_loan']);
Route::post('/update_motorcycle_loan_db',[EmployeeMotorCycleLoanController::class,'update_motorcycle_loan_db'])->name('update-motorcycle-loan-db');
Route::get('/delete_motorcycle_loan/{id}',[EmployeeMotorCycleLoanController::class,'delete_motorcycle_loan']);
Route::post('/delete_multiple_motorcycle_loans',[EmployeeMotorCycleLoanController::class,'delete_multiple_motorcycle_loans'])->name('delete-multiple-motorcycle-loans');

// Third Parties
Route::get('/validate_heads_third_party',[ThirdPartyController::class,'validate_heads_third_party']);
Route::post('/add_third_parties',[ThirdPartyController::class,'add_third_parties'])->name('add-to-third-parties');
Route::get('/third_parties',[ThirdPartyController::class,'third_parties']);
Route::get('/action_third_parties/{id}/{id1}/{id2}/{id3}',[ThirdPartyController::class,'action_third_parties']);
Route::post('/third_party_db',[ThirdPartyController::class,'third_party_db'])->name('third-party-db');
Route::get('/update_third_party/{id}',[ThirdPartyController::class,'update_third_party']);
Route::post('/update_third_party_db',[ThirdPartyController::class,'update_third_party_db'])->name('update-third-party-db');
Route::get('/delete_third_party/{id}',[ThirdPartyController::class,'delete_third_party']);
Route::post('/delete_multiple_third_parties',[ThirdPartyController::class,'delete_multiple_third_parties'])->name('delete-multiple-third-parties');
Route::get('/invoice_third_party/{id}',[ThirdPartyController::class,'invoice_third_party']);

// Reports
Route::get('/ledger_report',[ReportsController::class,'ledger_report']);
Route::post('/ledger_db',[ReportsController::class,'ledger_db'])->name('ledger-db');
Route::get('/fund_position',[ReportsController::class,'fund_position']);
Route::post('/fund_position_db',[ReportsController::class,'fund_position_db'])->name('fund-position-db');
Route::get('/trial_balance',[ReportsController::class,'trial_balance']);
Route::post('/trial_balance_db',[ReportsController::class,'trial_balance_db'])->name('trial-balance-db');
Route::get('/staff_salary_report',[ReportsController::class,'staff_salary_report']);
Route::post('/staff_salary_report_db',[ReportsController::class,'staff_salary_report_db'])->name('staff-salary-report-db');
Route::get('/officer_salary_report',[ReportsController::class,'officer_salary_report']);
Route::post('/officer_salary_report_db',[ReportsController::class,'officer_salary_report_db'])->name('officer-salary-report-db');
Route::get('/hbl_report',[ReportsController::class,'hbl_report']);
Route::post('/hbl_report_db',[ReportsController::class,'hbl_report_db'])->name('hbl-report-db');
Route::get('/nbp_report',[ReportsController::class,'nbp_report']);
Route::post('/nbp_report_db',[ReportsController::class,'nbp_report_db'])->name('nbp-report-db');

// Loan Reports
Route::get('/hb_loan_report',[ReportsController::class,'hb_loan_report']);
Route::post('/hb_loan_report_db',[ReportsController::class,'hb_loan_report_db'])->name('hb-loan-report-db');
Route::get('/motorcar_loan_report',[ReportsController::class,'motorcar_loan_report']);
Route::post('/motorcar_loan_report_db',[ReportsController::class,'motorcar_loan_report_db'])->name('motorcar-loan-report-db');
Route::get('/motorcycle_loan_report',[ReportsController::class,'motorcycle_loan_report']);
Route::post('/motorcycle_loan_report_db',[ReportsController::class,'motorcycle_loan_report_db'])->name('motorcycle-loan-report-db');