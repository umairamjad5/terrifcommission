<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Hash;
use Session;
use Illuminate\Support\Str;
use Image;
use App\Models\Employee;
use App\Models\EmployeeSalarySlip;
use App\Models\EmployeeSalaryReport;
use App\Models\DeductionStatment;
use App\Models\EmployeeGPFundSubscription;
use App\Models\EmployeeGPLoan;
use App\Models\EmployeeHBDeduction;
use App\Models\EmployeeHBLoan;
use App\Models\EmployeeHBLoanBalance;
use App\Models\EmployeeMotorCarDeduction;
use App\Models\EmployeeMotorCarLoan;
use App\Models\EmployeeMotorCarLoanBalance;
use App\Models\EmployeeMotorCycleDeduction;
use App\Models\EmployeeMotorCycleLoan;
use App\Models\EmployeeMotorCycleLoanBalance;
use App\Models\EmployeeOtherDeductions;
use App\Models\EmployeePayScale;
use App\Models\GPFundSubscriptionBalance;
use App\Models\OtherDeductionStatment;
use App\Models\GPFundSubscriptionStatment;
use App\Models\EmployeeGPLoanBalance;
use App\Models\SubHead;
use App\Models\EmployeeAllowance;

class EmployeeController extends Controller
{
    function employees()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array = Employee::
            // where('employee_scale','!=','25')
            // ->where('employee_scale','!=','26')
            // ->
            orderby('autoemployeeid','desc')->get();
            return view('employees.employees',compact('array'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function action_employees()
    {
        if(Session::get('admin_id'))
        {
            return view('employees.action_employees');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function employee_db(Request $request)
    {
        $rules = [
            'employee_name'  =>  'required|string',
            'employee_designation'  =>  'required|string',
            'employee_scale'  =>  'required|string',
            'date_of_joining'  =>  'required|string',
            'basic_salary'  =>  'required|string',
            'personal_salary'  =>  'required|string',
            'qualification_salary'  =>  'required|string',
            'special_salary'  =>  'required|string',
            'bank_account_detail'  =>  'required|string',
            'income_tax'  =>  'required|string',
            'group_insurance'  =>  'required|string',
            'hb_advance'  =>  'required|string',
		];

		$validator = Validator::make($request->all(),$rules);
		if ($validator->fails()) {
			return back()
			->withInput()
			->withErrors($validator);
		}
		else{
            $data = $request->input();
			try{
				// dd($data);
				$employee = new Employee;
                $employee->employee_name = $data['employee_name'];
                $employee->employee_designation = $data['employee_designation'];
                $employee->employee_scale = $data['employee_scale'];
                $employee->employee_stages = $data['employee_stages'];
                $employee->date_of_joining = $data['date_of_joining'];
                $employee->basic_salary = $data['basic_salary'];
                $employee->personal_salary = $data['personal_salary'];
                $employee->qualification_salary = $data['qualification_salary'];
                $employee->special_salary = $data['special_salary'];
                $employee->email_address = $data['email_address'];
                $employee->bank_account_detail = $data['bank_account_detail'];
                $employee->date_of_retirement = $data['date_of_retirement'];
                $employee->income_tax = $data['income_tax'];
                $employee->group_insurance = $data['group_insurance'];
                $employee->hb_advance = $data['hb_advance'];
                $employee->added_by = Session::get('admin_id');

				$employee->save();

				return redirect('/employees')->with('success',"Insert Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_employee($autoemployeeid)
    {
        if(Session::get('admin_id'))
        {
            $array1 = array();
            $array1 = Employee::where("autoemployeeid",'=',$autoemployeeid)
            ->get();
            // dd($array);        
            return view('employees.action_employees',compact('array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_employee_db(Request $request)
    {
        $rules = [
            'employee_name'  =>  'required|string',
            'employee_designation'  =>  'required|string',
            'employee_scale'  =>  'required|string',
            'date_of_joining'  =>  'required|string',
            'basic_salary'  =>  'required|string',
            'personal_salary'  =>  'required|string',
            'qualification_salary'  =>  'required|string',
            'special_salary'  =>  'required|string',
            'bank_account_detail'  =>  'required|string',
            'income_tax'  =>  'required|string',
            'group_insurance'  =>  'required|string',
            'hb_advance'  =>  'required|string',
		];

		$validator = Validator::make($request->all(),$rules);
		if ($validator->fails()) {
			return back()
			->withInput()
			->withErrors($validator);
		}
		else{
            $data = $request->input();
			try{
				// dd($data);
				$employee = Employee::where("autoemployeeid",'=',$data['autoemployeeid'])->update(
                    [
                        'employee_name' => $data['employee_name'],
                        'employee_designation' => $data['employee_designation'],
                        'employee_scale' => $data['employee_scale'],
                        'employee_stages' => $data['employee_stages'],
                        'date_of_joining' => $data['date_of_joining'],
                        'basic_salary' => $data['basic_salary'],
                        'personal_salary' => $data['personal_salary'],
                        'qualification_salary' => $data['qualification_salary'],
                        'special_salary' => $data['special_salary'],
                        'email_address' => $data['email_address'],
                        'bank_account_detail' => $data['bank_account_detail'],
                        'date_of_retirement' => $data['date_of_retirement'],
                        'income_tax' => $data['income_tax'],
                        'group_insurance' => $data['group_insurance'],
                        'hb_advance' => $data['hb_advance'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

				return redirect('/employees')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_employee($autoemployeeid)
    {
        if(Session::get('admin_id'))
        {
            $employee_delete = Employee::where("autoemployeeid",'=',$autoemployeeid);
            $employee_delete->delete();

            return redirect('/employees')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_employees(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
					Employee::where('autoemployeeid', $deleteid)->delete();
				}
				return redirect('/employees')->with('success',"Deleted successfully");	
			}
			else
			{
				return back()->with('error',"Select Some Rows First!");				
			}
		}
		catch(Exception $e){
			return back()->with('error',"Error Occured");
		}
    }

    function validate_salary_slip($autoemployeeid)
    {
        if(Session::get('admin_id'))
        {
            return view('employees.validate_salary_slip',compact('autoemployeeid'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function add_to_salary_slip(Request $request)
    {
        $rules = [
            'salary_month'  =>  'required|string',
		];

		$validator = Validator::make($request->all(),$rules);
		if ($validator->fails()) {
			return back()
			->withInput()
			->withErrors($validator);
		}
		else{
            $data = $request->input();
			try{
				// dd($data);
				return redirect('/employee_salary_slip/'.$data['employeeid'].'/'.$data['salary_month']);
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function employee_salary_slip($employeeid,$salary_month)
    {
        if(Session::get('admin_id'))
        {
            return view('employees.employee_salary_slip',compact('employeeid','salary_month'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function employee_salary_slips_all($salary_month)
    {
        if(Session::get('admin_id'))
        {
            return view('employees.employee_salary_slips_all',compact('salary_month'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function generate_salary_slips(Request $request)
    {
        $rules = [
            'salary_month'  =>  'required|string',
		];

		$validator = Validator::make($request->all(),$rules);
		if ($validator->fails()) {
			return back()
			->withInput()
			->withErrors($validator);
		}
		else{
            $data = $request->input();
			try{
				// dd($data);

                $q = Employee::orderby('autoemployeeid','asc')
                // ->where('autoemployeeid','=','87')
                ->get();
                if(count($q) > 0)
                {
                    foreach($q as $q)
                    {
                        $q1 = EmployeeSalarySlip::where('employeeid','=',$q->autoemployeeid)
                        ->where('salary_month','=',$data['salary_month'])
                        ->get();
                        if(count($q1) == 1)
                        {
                            // return back()->with('error',"Already Generate Slip");
                            continue;
                        }
                        else
                        {
                            $emp_salary_slip = new EmployeeSalarySlip;
                            $emp_salary_slip->employeeid = $q->autoemployeeid;
                            $emp_salary_slip->salary_month = $data['salary_month'];
                            $emp_salary_slip->added_by = Session::get('admin_id');
                            $emp_salary_slip->save();

                            $q2 = EmployeePayScale::where('autoemployeepayscaleid','=',$q->employee_scale)
                            ->get();
                            foreach($q2 as $q2);
                            $income_tax = $q->income_tax;
                            
                            $emp_scale_salary = $q2->minimum_pay;
                            if($q->employee_stages > $q2->scale_stages)
                            {
                                $emp_scale_increment = $q2->scale_stages * $q2->increment_amount;  
                            }
                            else
                            {
                                $emp_scale_increment = $q->employee_stages * $q2->increment_amount;  
                            }

                            if($q->basic_salary == 0)
                            {
                                $emp_salary = $emp_scale_salary + $emp_scale_increment;
                            }
                            else
                            {
                                $emp_salary = $q->basic_salary;
                            }

                            if($q->personal_salary == 0)
                            {
                                if($q->employee_stages > $q2->scale_stages)
                                {
                                    $diff_scale_stages = $q->employee_stages - $q2->scale_stages;
                                    $emp_personal_salary = $q2->increment_amount * $diff_scale_stages;
                                }
                                else
                                {
                                    $emp_personal_salary = 0;
                                }
                            }
                            else
                            {
                                $emp_personal_salary = $q->personal_salary;
                            }

                            $basic_pay = $emp_salary;
                            $personal_pay = $emp_personal_salary;
                            $special_pay = $q->special_salary;
                            $qualification_pay = $q->qualification_salary;

                            $all_1 = Subhead::where('sub_head_code','=','A01216')
                            ->get();
                            if(!empty($all_1))
                            {
                                foreach($all_1 as $all_1);
                                $all_1_1 = EmployeeAllowance::where('subheadid','=',$all_1->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_1_1) == 1)
                                {
                                    foreach($all_1_1 as $all_1_1);
                                    $qualification_all = $all_1_1->allowance_amount;
                                }
                                else
                                {
                                    $qualification_all = 0;
                                }
                            }
                            $all_2 = Subhead::where('sub_head_code','=','A01202')
                            ->get();
                            if(!empty($all_2))
                            {
                                foreach($all_2 as $all_2);
                                $all_2_2 = EmployeeAllowance::where('subheadid','=',$all_2->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_2_2) == 1)
                                {
                                    foreach($all_2_2 as $all_2_2);
                                    $house_rent_all = $all_2_2->allowance_amount;
                                }
                                else
                                {
                                    $house_rent_all = 0;
                                }
                            }

                            $all_3 = Subhead::where('sub_head_code','=','A01203')
                            ->get();
                            if(!empty($all_3))
                            {
                                foreach($all_3 as $all_3);
                                $all_3_3 = EmployeeAllowance::where('subheadid','=',$all_3->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_3_3) == 1)
                                {
                                    foreach($all_3_3 as $all_3_3);
                                    $conveyance_all = $all_3_3->allowance_amount;
                                }
                                else
                                {
                                    $conveyance_all = 0;
                                }
                            }

                            $all_4 = Subhead::where('sub_head_code','=','A01217')
                            ->get();
                            if(!empty($all_4))
                            {
                                foreach($all_4 as $all_4);
                                $all_4_4 = EmployeeAllowance::where('subheadid','=',$all_4->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_4_4) == 1)
                                {
                                    foreach($all_4_4 as $all_4_4);
                                    $medical_all = $all_4_4->allowance_amount;
                                }
                                else
                                {
                                    $medical_all = 0;
                                }
                            }

                            $all_5 = Subhead::where('sub_head_code','=','A01224')
                            ->get();
                            if(!empty($all_5))
                            {
                                foreach($all_5 as $all_5);
                                $all_5_5 = EmployeeAllowance::where('subheadid','=',$all_5->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_5_5) == 1)
                                {
                                    foreach($all_5_5 as $all_5_5);
                                    $entertainment_all = $all_5_5->allowance_amount;
                                }
                                else
                                {
                                    $entertainment_all = 0;
                                }
                            }

                            $all_6 = Subhead::where('sub_head_code','=','A01228')
                            ->get();
                            if(!empty($all_6))
                            {
                                foreach($all_6 as $all_6);
                                $all_6_6 = EmployeeAllowance::where('subheadid','=',$all_6->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_6_6) == 1)
                                {
                                    foreach($all_6_6 as $all_6_6);
                                    $orderly_all = $all_6_6->allowance_amount;
                                }
                                else
                                {
                                    $orderly_all = 0;
                                }
                            }

                            $all_7 = Subhead::where('sub_head_code','=','A0124R')
                            ->get();
                            if(!empty($all_7))
                            {
                                foreach($all_7 as $all_7);
                                $all_7_7 = EmployeeAllowance::where('subheadid','=',$all_7->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_7_7) == 1)
                                {
                                    foreach($all_7_7 as $all_7_7);
                                    $adhoc_relief = $all_7_7->allowance_amount;
                                }
                                else
                                {
                                    $adhoc_relief = 0;
                                }
                            }

                            $all_8 = Subhead::where('sub_head_code','=','A0124C')
                            ->get();
                            if(!empty($all_8))
                            {
                                foreach($all_8 as $all_8);
                                $all_8_8 = EmployeeAllowance::where('subheadid','=',$all_8->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_8_8) == 1)
                                {
                                    foreach($all_8_8 as $all_8_8);
                                    $disparity_reduction_21 = $all_8_8->allowance_amount;
                                }
                                else
                                {
                                    $disparity_reduction_21 = 0;
                                }
                            }

                            $all_9 = Subhead::where('sub_head_code','=','A0124N')
                            ->get();
                            if(!empty($all_9))
                            {
                                foreach($all_9 as $all_9);
                                $all_9_9 = EmployeeAllowance::where('subheadid','=',$all_9->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_9_9) == 1)
                                {
                                    foreach($all_9_9 as $all_9_9);
                                    $disparity_reduction_22 = $all_9_9->allowance_amount;
                                }
                                else
                                {
                                    $disparity_reduction_22 = 0;
                                }
                            }

                            $all_10 = Subhead::where('sub_head_code','=','A01274')
                            ->get();
                            if(!empty($all_10))
                            {
                                foreach($all_10 as $all_10);
                                $all_10_10 = EmployeeAllowance::where('subheadid','=',$all_10->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_10_10) == 1)
                                {
                                    foreach($all_10_10 as $all_10_10);
                                    $medical_charges = $all_10_10->allowance_amount;
                                }
                                else
                                {
                                    $medical_charges = 0;
                                }
                            }

                            $all_11 = Subhead::where('sub_head_code','=','A01239')
                            ->get();
                            if(!empty($all_11))
                            {
                                foreach($all_11 as $all_11);
                                $all_11_11 = EmployeeAllowance::where('subheadid','=',$all_11->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_11_11) == 1)
                                {
                                    foreach($all_11_11 as $all_11_11);
                                    $special_all = $all_11_11->allowance_amount;
                                }
                                else
                                {
                                    $special_all = 0;
                                }
                            }

                            $all_12 = Subhead::where('sub_head_code','=','A01270')
                            ->get();
                            if(!empty($all_12))
                            {
                                foreach($all_12 as $all_12);
                                $all_12_12 = EmployeeAllowance::where('subheadid','=',$all_12->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_12_12) == 1)
                                {
                                    foreach($all_12_12 as $all_12_12);
                                    $utilities = $all_12_12->allowance_amount;
                                }
                                else
                                {
                                    $utilities = 0;
                                }
                            }

                            $all_13 = Subhead::where('sub_head_code','=','A0122L')
                            ->get();
                            if(!empty($all_13))
                            {
                                foreach($all_13 as $all_13);
                                $all_13_13 = EmployeeAllowance::where('subheadid','=',$all_13->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_13_13) == 1)
                                {
                                    foreach($all_13_13 as $all_13_13);
                                    $mobile_phone_all = $all_13_13->allowance_amount;
                                }
                                else
                                {
                                    $mobile_phone_all = 0;
                                }
                            }

                            $all_14 = Subhead::where('sub_head_code','=','A0120D')
                            ->get();
                            if(!empty($all_14))
                            {
                                foreach($all_14 as $all_14);
                                $all_14_14 = EmployeeAllowance::where('subheadid','=',$all_14->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_14_14) == 1)
                                {
                                    foreach($all_14_14 as $all_14_14);
                                    $integrated_all = $all_14_14->allowance_amount;
                                }
                                else
                                {
                                    $integrated_all = 0;
                                }
                            }

                            $all_15 = Subhead::where('sub_head_code','=','A01238')
                            ->get();
                            if(!empty($all_15))
                            {
                                foreach($all_15 as $all_15);
                                $all_15_15 = EmployeeAllowance::where('subheadid','=',$all_15->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_15_15) == 1)
                                {
                                    foreach($all_15_15 as $all_15_15);
                                    $additional_charges = $all_15_15->allowance_amount;
                                }
                                else
                                {
                                    $additional_charges = 0;
                                }
                            }

                            $all_16 = Subhead::where('sub_head_code','=','A0121J')
                            ->get();
                            if(!empty($all_16))
                            {
                                foreach($all_16 as $all_16);
                                $all_16_16 = EmployeeAllowance::where('subheadid','=',$all_16->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_16_16) == 1)
                                {
                                    foreach($all_16_16 as $all_16_16);
                                    $monitization_of_residential_trasport = $all_16_16->allowance_amount;
                                }
                                else
                                {
                                    $monitization_of_residential_trasport = 0;
                                }
                            }

                            $all_17 = Subhead::where('sub_head_code','=','A0122L_1')
                            ->get();
                            if(!empty($all_17))
                            {
                                foreach($all_17 as $all_17);
                                $all_17_17 = EmployeeAllowance::where('subheadid','=',$all_17->autosubheadid)
                                ->where('employeeid','=',$q->autoemployeeid)
                                ->get();
                                if(count($all_17_17) == 1)
                                {
                                    foreach($all_17_17 as $all_17_17);
                                    $monitization_of_residential_telephone = $all_17_17->allowance_amount;
                                }
                                else
                                {
                                    $monitization_of_residential_telephone = 0;
                                }
                            }

                            $benevolent_fund = 0;
                            if($emp_salary <= 5000)
                            {
                                $benevolent_fund = 120;
                            }
                            if($emp_salary > 5000 && $emp_salary <= 5500)
                            {
                                $benevolent_fund = 126;
                            }
                            if($emp_salary > 5500 && $emp_salary <= 6000)
                            {
                                $benevolent_fund = 138;
                            }
                            if($emp_salary > 6000 && $emp_salary <= 6500)
                            {
                                $benevolent_fund = 150;
                            }
                            if($emp_salary > 6500 && $emp_salary <= 7000)
                            {
                                $benevolent_fund = 162;
                            }
                            if($emp_salary > 7000 && $emp_salary <= 7500)
                            {
                                $benevolent_fund = 174;
                            }
                            if($emp_salary > 7500 && $emp_salary <= 8000)
                            {
                                $benevolent_fund = 186;
                            }
                            if($emp_salary > 8000 && $emp_salary <= 8500)
                            {
                                $benevolent_fund = 198;
                            }
                            if($emp_salary > 8500 && $emp_salary <= 9000)
                            {
                                $benevolent_fund = 210;
                            }
                            if($emp_salary > 9000 && $emp_salary <= 9500)
                            {
                                $benevolent_fund = 222;
                            }
                            if($emp_salary > 9500 && $emp_salary <= 11000)
                            {
                                $benevolent_fund = 246;
                            }
                            if($emp_salary > 11000 && $emp_salary <= 13000)
                            {
                                $benevolent_fund = 288;
                            }
                            if($emp_salary > 13000 && $emp_salary <= 15000)
                            {
                                $benevolent_fund = 336;
                            }
                            if($emp_salary > 15000 && $emp_salary <= 17000)
                            {
                                $benevolent_fund = 384;
                            }
                            if($emp_salary > 17000 && $emp_salary <= 19000)
                            {
                                $benevolent_fund = 432;
                            }
                            if($emp_salary > 19000 && $emp_salary <= 21000)
                            {
                                $benevolent_fund = 480;
                            }
                            if($emp_salary > 21000 && $emp_salary <= 23000)
                            {
                                $benevolent_fund = 528;
                            }
                            if($emp_salary > 23000 && $emp_salary <= 25000)
                            {
                                $benevolent_fund = 576;
                            }
                            if($emp_salary > 25000 && $emp_salary <= 27000)
                            {
                                $benevolent_fund = 624;
                            }
                            if($emp_salary > 27000 && $emp_salary <= 29000)
                            {
                                $benevolent_fund = 672;
                            }
                            if($emp_salary > 29000 && $emp_salary <= 31000)
                            {
                                $benevolent_fund = 720;
                            }
                            if($emp_salary > 31000 && $emp_salary <= 33000)
                            {
                                $benevolent_fund = 768;
                            }
                            if($emp_salary > 33000 && $emp_salary <= 35000)
                            {
                                $benevolent_fund = 816;
                            }
                            if($emp_salary > 35000 && $emp_salary <= 37000)
                            {
                                $benevolent_fund = 864;
                            }
                            if($emp_salary > 37000 && $emp_salary <= 39000)
                            {
                                $benevolent_fund = 912;
                            }
                            if($emp_salary > 39000)
                            {
                                $benevolent_fund = 960;
                            }
                            if($q2->bps_scale == 0)
                            {
                                $benevolent_fund = 0;
                            }
                            if($q2->bps_scale == 1)
                            {
                                $benevolent_fund = 0;
                            }

                            $group_insurance = $q->group_insurance;
                            // if($emp_salary <= 5000)
                            // {
                            //     $group_insurance = 381;
                            // }
                            // if($emp_salary > 5000 && $emp_salary <= 10000)
                            // {
                            //     $group_insurance = 436;
                            // }
                            // if($emp_salary > 10000 && $emp_salary <= 15000)
                            // {
                            //     $group_insurance = 490;
                            // }
                            // if($emp_salary > 15000 && $emp_salary <= 20000)
                            // {
                            //     $group_insurance = 545;
                            // }
                            // if($emp_salary > 20000 && $emp_salary <= 25000)
                            // {
                            //     $group_insurance = 600;
                            // }
                            // if($emp_salary > 25000 && $emp_salary <= 30000)
                            // {
                            //     $group_insurance = 654;
                            // }
                            // if($emp_salary > 30000 && $emp_salary <= 35000)
                            // {
                            //     $group_insurance = 709;
                            // }
                            // if($emp_salary > 35000 && $emp_salary <= 40000)
                            // {
                            //     $group_insurance = 763;
                            // }
                            // if($emp_salary > 40000 && $emp_salary <= 45000)
                            // {
                            //     $group_insurance = 818;
                            // }
                            // if($emp_salary > 45000 && $emp_salary <= 50000)
                            // {
                            //     $group_insurance = 872;
                            // }
                            // if($emp_salary > 50000 && $emp_salary <= 55000)
                            // {
                            //     $group_insurance = 926;
                            // }
                            // if($emp_salary > 55000 && $emp_salary <= 60000)
                            // {
                            //     $group_insurance = 981;
                            // }
                            // if($emp_salary > 60000 && $emp_salary <= 65000)
                            // {
                            //     $group_insurance = 1036;
                            // }
                            // if($emp_salary > 65000)
                            // {
                            //     $group_insurance = 1090;
                            // }

                            $gp_fund_subscription = $q2->gp_fund_advance;

                            $q3 = new EmployeeGPFundSubscription;
                            $q3->employeeid = $q->autoemployeeid;
                            $q3->gp_fund_month = $data['salary_month'];
                            $q3->gp_fund_amount = $gp_fund_subscription;
                            $q3->added_by = Session::get('admin_id');
                            $q3->save();

                            $q4 = GPFundSubscriptionBalance::where('employeeid','=',$q->autoemployeeid)
                            ->get();
                            if(count($q4) == 1)
                            {
                                foreach($q4 as $q4);
                                $q5 = GPFundSubscriptionBalance::where('employeeid','=',$q->autoemployeeid)
                                ->update(
                                    [
                                        'gp_fund_amount'    =>  $q4->gp_fund_amount + $gp_fund_subscription,
                                        'gp_fund_balance'    =>  $q4->gp_fund_balance + $gp_fund_subscription,
                                        'updated_by' => Session::get('admin_id'),
                                    ]
                                );
                            }
                            else
                            {
                                $q5 = new GPFundSubscriptionBalance;
                                $q5->employeeid = $q->autoemployeeid;
                                $q5->gp_fund_amount = $gp_fund_subscription;
                                $q5->gp_fund_balance = $gp_fund_subscription;
                                $q5->added_by = Session::get('admin_id');
                                $q5->save();
                            }

                            $q5_1 = EmployeeGPLoan::where('employeeid','=',$q->autoemployeeid)
                            ->where('gp_loan_type','=','Refundable')
                            ->orderby('autoemployeegploanid','desc')
                            ->get();
                            if(count($q5_1) > 0)
                            {
                                foreach($q5_1 as $q5_1);
                                $q5_1_1 = EmployeeGPLoanBalance::where('employeeid','=',$q->autoemployeeid)
                                ->sum('gp_balance_amount');
                                if($q5_1_1 > $q5_1->gp_loan_installment)
                                {
                                    $gp_adv_ins = $q5_1->gp_loan_installment;
                                }
                                else
                                {
                                    $gp_adv_ins = $q5_1_1;
                                }
                            }
                            else
                            {
                                $gp_adv_ins = '0';
                            }

                            $q5_2 = EmployeeGPLoanBalance::where('employeeid','=',$q->autoemployeeid)
                            ->get();
                            if(count($q5_2) > 0)
                            {
                                foreach($q5_2 as $q5_2);

                                $q5_3 = EmployeeGPLoanBalance::where('employeeid','=',$q->autoemployeeid)
                                ->update(
                                    [
                                        'gp_balance_amount'    =>  $q5_2->gp_balance_amount - $gp_adv_ins,
                                        'updated_by' => Session::get('admin_id'),
                                    ]
                                );
                            }

                            $q5_3 = EmployeeGPLoan::where('employeeid','=',$q->autoemployeeid)
                            ->where('gp_loan_date','like',$data['salary_month'].'%')
                            ->where('gp_loan_type','=','Refundable')
                            ->get();
                            if(count($q5_3) > 0)
                            {
                                foreach($q5_3 as $q5_3);
                                $gp_widrawl = $q5_3->gp_loan_amount;
                            }
                            else
                            {
                                $gp_widrawl = '0';
                            }
                            $lastmonth = date("Y-m",strtotime('-1 month',strtotime($data['salary_month'])));
                            $q5_4 = GPFundSubscriptionStatment::where('employeeid','=',$q->autoemployeeid)
                            ->where('gp_fund_statment_month','=',$lastmonth)
                            ->orderby('autoemployeegploanstatmentid','desc')
                            ->get();
                            if(count($q5_4) > 0)
                            {
                                foreach($q5_4 as $q5_4);
                                $progressivebalance = $q5_4->progressive_balance;
                            }
                            else
                            {
                                $progressivebalance = '0';
                            }

                            $contributed_by_amount = '0';
                            $transfer_amount = '0';

                            $progressive_balance = $progressivebalance + $gp_fund_subscription + $gp_adv_ins + $contributed_by_amount + $transfer_amount - $gp_widrawl;

                            $q5_5 = new GPFundSubscriptionStatment;
                            $q5_5->employeeid = $q->autoemployeeid;
                            $q5_5->gp_fund_statment_month = $data['salary_month'];
                            $q5_5->monthly_subscription = $gp_fund_subscription;
                            $q5_5->refund_installment_amount = $gp_adv_ins;
                            $q5_5->contributed_by_amount = $contributed_by_amount;
                            $q5_5->transfer_amount = $transfer_amount;
                            $q5_5->widrawl_amount = $gp_widrawl;
                            $q5_5->progressive_balance = $progressive_balance;
                            $q5_5->added_by = Session::get('admin_id');
                            $q5_5->save();

                            $q6 = EmployeeHBLoan::where('employeeid','=',$q->autoemployeeid)
                            ->get();
                            if(count($q6) > 0)
                            {
                                $q7 = EmployeeHBLoanBalance::where('employeeid','=',$q->autoemployeeid)
                                ->sum('hb_balance_amount');                                
                                if($q7 > 0)
                                {
                                    if($q7 > $q->hb_advance)
                                    {
                                        $hb_deduct_amount = $q->hb_advance;
                                    }   
                                    else
                                    {
                                        $hb_deduct_amount = $q7;
                                    }

                                    $q7_1 = EmployeeHBDeduction::where('employeeid','=',$q->autoemployeeid)
                                    ->orderby('autoemployeehbdeductionid','desc')
                                    ->offset(0)->limit(1)
                                    ->get();
                                    if(count($q7_1) > 0)
                                    {
                                        foreach($q7_1 as $q7_1);
                                        if($q7_1->hb_deduction_month != $data['salary_month'])
                                        {
                                            $q7_2 = new EmployeeHBDeduction;
                                            $q7_2->employeeid = $q->autoemployeeid;
                                            $q7_2->hb_deduction_month = $data['salary_month'];
                                            $q7_2->hb_deduction_amount = $hb_deduct_amount;
                                            $q7_2->hb_deduction_prograssive = $hb_deduct_amount + $q7_1->hb_deduction_prograssive;
                                            $q7_2->hb_deduction_rem_bal = $q7_1->hb_deduction_rem_bal - $hb_deduct_amount;
                                            $q7_2->added_by = Session::get('admin_id');
                                            $q7_2->save();    
                                        }
                                        else
                                        {
                                            continue;
                                        }
                                    }
                                    else
                                    {
                                        $q8 = new EmployeeHBDeduction;
                                        $q8->employeeid = $q->autoemployeeid;
                                        $q8->hb_deduction_month = $data['salary_month'];
                                        $q8->hb_deduction_amount = $hb_deduct_amount;
                                        $q8->hb_deduction_prograssive = $hb_deduct_amount;
                                        $q8->hb_deduction_rem_bal = $q7 - $hb_deduct_amount;
                                        $q8->added_by = Session::get('admin_id');
                                        $q8->save();    
                                    }

                                    $q9 = EmployeeHBLoanBalance::where('employeeid','=',$q->autoemployeeid)
                                    ->update(
                                        [
                                            'hb_balance_amount'    =>  $q7 - $hb_deduct_amount,
                                            'updated_by' => Session::get('admin_id'),
                                        ]
                                    );
                                }
                                else
                                {
                                    $hb_deduct_amount = '0';
                                }
                            }
                            else
                            {
                                $hb_deduct_amount = '0';
                            }

                            $q10 = EmployeeMotorCarLoan::where('employeeid','=',$q->autoemployeeid)
                            ->get();
                            if(count($q10) > 0)
                            {
                                foreach($q10 as $q10);
                                $q11 = EmployeeMotorCarLoanBalance::where('employeeid','=',$q->autoemployeeid)
                                ->sum('motorcar_balance_amount');  
                                if($q11 > 0)
                                {
                                    $q11_1 = EmployeeMotorCarLoan::where('employeeid','=',$q->autoemployeeid)
                                    ->sum('motorcar_approved_amount');  
                                    $motorcar_installment = $q10->motorcar_installment_amount;; 
                                                   
                                    if($q11 > $motorcar_installment)
                                    {
                                        $motorcar_deduct_amount = $motorcar_installment;
                                    }   
                                    else
                                    {
                                        $motorcar_deduct_amount = $q11;
                                    }

                                    $q11_2 = EmployeeMotorCarDeduction::where('employeeid','=',$q->autoemployeeid)
                                    ->orderby('autoemployeemotorcardeductionid','desc')
                                    ->offset(0)->limit(1)
                                    ->get();
                                    if(count($q11_2) > 0)
                                    {
                                        foreach($q11_2 as $q11_2);
                                        if($q11_2->motorcar_deduction_month != $data['salary_month'])
                                        {
                                            $q11_3 = new EmployeeMotorCarDeduction;
                                            $q11_3->employeeid = $q->autoemployeeid;
                                            $q11_3->motorcar_deduction_month = $data['salary_month'];
                                            $q11_3->motorcar_deduction_amount = $motorcar_deduct_amount;
                                            $q11_3->motorcar_deduction_prograssive = $motorcar_deduct_amount + $q11_2->motorcar_deduction_prograssive;
                                            $q11_3->motorcar_deduction_rem_bal = $q11_2->motorcar_deduction_rem_bal - $motorcar_deduct_amount;
                                            $q11_3->added_by = Session::get('admin_id');
                                            $q11_3->save();        
                                        }
                                        else
                                        {
                                            continue;
                                        }
                                    }
                                    else
                                    {
                                        $q12 = new EmployeeMotorCarDeduction;
                                        $q12->employeeid = $q->autoemployeeid;
                                        $q12->motorcar_deduction_month = $data['salary_month'];
                                        $q12->motorcar_deduction_amount = $motorcar_deduct_amount;
                                        $q12->motorcar_deduction_prograssive = $motorcar_deduct_amount;
                                        $q12->motorcar_deduction_rem_bal = $q11 - $motorcar_deduct_amount;
                                        $q12->added_by = Session::get('admin_id');
                                        $q12->save();
                                    }

                                    $q13 = EmployeeMotorCarLoanBalance::where('employeeid','=',$q->autoemployeeid)
                                    ->update(
                                        [
                                            'motorcar_balance_amount'    =>  $q11 - $motorcar_deduct_amount,
                                            'updated_by' => Session::get('admin_id'),
                                        ]
                                    );
                                }
                                else
                                {
                                    $motorcar_deduct_amount = '0';
                                }
                            }
                            else
                            {
                                $motorcar_deduct_amount = '0';
                            }

                            $q14 = EmployeeMotorCycleLoan::where('employeeid','=',$q->autoemployeeid)
                            ->get();
                            if(count($q14) > 0)
                            {
                                foreach($q14 as $q14);
                                $q15 = EmployeeMotorCycleLoanBalance::where('employeeid','=',$q->autoemployeeid)
                                ->sum('motorcycle_balance_amount');  
                                if($q15 > 0)
                                {
                                    $q15_1 = EmployeeMotorCycleLoan::where('employeeid','=',$q->autoemployeeid)
                                    ->sum('motorcycle_approved_amount');  
                                    $motorcycle_installment = $q14->motorcycle_installment_amount;                          
                                    if($q15 > $motorcycle_installment)
                                    {
                                        $motorcycle_deduct_amount = $motorcycle_installment;
                                    }   
                                    else
                                    {
                                        $motorcycle_deduct_amount = $q15;
                                    }
    
                                    $q15_2 = EmployeeMotorCycleDeduction::where('employeeid','=',$q->autoemployeeid)
                                    ->orderby('autoemployeemotorcycledeductionid','desc')
                                    ->offset(0)->limit(1)
                                    ->get();
                                    if(count($q15_2) > 0)
                                    {
                                        foreach($q15_2 as $q15_2);
                                        if($q15_2->motorcycle_deduction_month != $data['salary_month'])
                                        {
                                            $q15_3 = new EmployeeMotorCycleDeduction;
                                            $q15_3->employeeid = $q->autoemployeeid;
                                            $q15_3->motorcycle_deduction_month = $data['salary_month'];
                                            $q15_3->motorcycle_deduction_amount = $motorcycle_deduct_amount;
                                            $q15_3->motorcycle_deduction_prograssive = $motorcycle_deduct_amount + $q15_2->motorcycle_deduction_prograssive;
                                            $q15_3->motorcycle_deduction_rem_bal = $q15_2->motorcycle_deduction_rem_bal - $motorcycle_deduct_amount;
                                            $q15_3->added_by = Session::get('admin_id');
                                            $q15_3->save();        
                                        }
                                        else
                                        {
                                            continue;
                                        }
                                    }
                                    else
                                    {
                                        $q16 = new EmployeeMotorCycleDeduction;
                                        $q16->employeeid = $q->autoemployeeid;
                                        $q16->motorcycle_deduction_month = $data['salary_month'];
                                        $q16->motorcycle_deduction_amount = $motorcycle_deduct_amount;
                                        $q16->motorcycle_deduction_prograssive = $motorcycle_deduct_amount;
                                        $q16->motorcycle_deduction_rem_bal = $q15 - $motorcycle_deduct_amount;
                                        $q16->added_by = Session::get('admin_id');
                                        $q16->save();
                                    }
    
                                    $q17 = EmployeeMotorCycleLoanBalance::where('employeeid','=',$q->autoemployeeid)
                                    ->update(
                                        [
                                            'motorcycle_balance_amount'    =>  $q15 - $motorcycle_deduct_amount,
                                            'updated_by' => Session::get('admin_id'),
                                        ]
                                    );
                                }
                                else
                                {
                                    $motorcycle_deduct_amount = '0';
                                }
                            }
                            else
                            {
                                $motorcycle_deduct_amount = '0';
                            }

                            $q18 = EmployeeOtherDeductions::where('employeeid','=',$q->autoemployeeid)
                            ->get();
                            if(count($q18) > 0)
                            {
                                foreach($q18 as $q18)
                                {
                                    if($q18->otherdeduction_balance != '0')
                                    {
                                        if($q18->otherdeduction_balance > $q18->otherdeduction_installment)
                                        {
                                            $otherbal = $q18->otherdeduction_installment;
                                        }
                                        else
                                        {
                                            $otherbal = $q18->otherdeduction_balance;
                                        }
                                        $q19 = new OtherDeductionStatment;
                                        $q19->employeeotherdeductionid = $q18->autoemployeeotherdeductionid;
                                        $q19->employeeid = $q->autoemployeeid;
                                        $q19->otherdeduction_amount = $q18->otherdeduction_installment;
                                        $q19->otherdeduction_balance = $q18->otherdeduction_balance - $otherbal;
                                        $q19->otherdeduction_month = $data['salary_month'];
    
                                        $q19->save();
    
                                        $q20 = EmployeeOtherDeductions::where('autoemployeeotherdeductionid','=',$q18->autoemployeeotherdeductionid)
                                        ->update(
                                            [
                                                'otherdeduction_balance'    =>  $q18->otherdeduction_balance - $otherbal,
                                            ]
                                        );    
                                    }
                                }
                            }

                            $q18_1 = EmployeeOtherDeductions::where('employeeid','=',$q->autoemployeeid)
                            ->where('description','like','LFP%')
                            ->get();
                            if(count($q18_1) > 0)
                            {
                                foreach($q18_1 as $q18_1);
                                $lfp = $q18_1->otherdeduction_installment;
                            }
                            else
                            {
                                $lfp = 0;
                            }

                            $q21 = new DeductionStatment;
                            $q21->employeeid = $q->autoemployeeid;
                            $q21->month = $data['salary_month'];
                            $q21->income_tax = $income_tax;
                            $q21->ben_fund = $benevolent_fund;
                            $q21->gi_fund = $group_insurance;
                            $q21->hb_advance = $hb_deduct_amount;
                            $q21->hb_interest = '0';
                            $q21->m_car_adv = $motorcar_deduct_amount;
                            $q21->m_cycle_adv = $motorcycle_deduct_amount;
                            $q21->added_by = Session::get('admin_id');

                            $q21->save();

                            $emp_salary_report = new EmployeeSalaryReport;
                            $emp_salary_report->employeeid = $q->autoemployeeid;
                            $emp_salary_report->salary_month = $data['salary_month'];
                            $emp_salary_report->basic_pay = $basic_pay;
                            $emp_salary_report->qualification_all = $qualification_all;
                            $emp_salary_report->personal_pay = $personal_pay;
                            $emp_salary_report->special_pay = $special_pay;
                            $emp_salary_report->qualification_pay = $qualification_pay;
                            $emp_salary_report->house_rent_all = $house_rent_all;
                            $emp_salary_report->conveyance_all = $conveyance_all;
                            $emp_salary_report->medical_all = $medical_all;
                            $emp_salary_report->entertainment_all = $entertainment_all;
                            $emp_salary_report->orderly_all = $orderly_all;
                            $emp_salary_report->adhoc_relief = $adhoc_relief;
                            $emp_salary_report->disparity_reduction_21 = $disparity_reduction_21;
                            $emp_salary_report->disparity_reduction_22 = $disparity_reduction_22;
                            $emp_salary_report->medical_charges = $medical_charges;
                            $emp_salary_report->special_all = $special_all;
                            $emp_salary_report->utilities = $utilities;
                            $emp_salary_report->mobile_phone_all = $mobile_phone_all;
                            $emp_salary_report->monitization_of_residential_telephone = $monitization_of_residential_telephone;
                            $emp_salary_report->integrated_all = $integrated_all;
                            $emp_salary_report->additional_charges = $additional_charges;
                            $emp_salary_report->monitization_of_residential_trasport = $monitization_of_residential_trasport;
                            $emp_salary_report->income_tax = $income_tax;
                            $emp_salary_report->ben_fund = $benevolent_fund;
                            $emp_salary_report->gi_fund = $group_insurance;
                            $emp_salary_report->gpf_adv = $gp_adv_ins;
                            $emp_salary_report->gpf_subs = $gp_fund_subscription;
                            $emp_salary_report->hb_interest = 0;
                            $emp_salary_report->hb_adv = $hb_deduct_amount;
                            $emp_salary_report->lfp = $lfp;
                            $emp_salary_report->motorcar_interest = 0;
                            $emp_salary_report->motorcar_adv = $motorcar_deduct_amount;
                            $emp_salary_report->motorcycle_interest = 0;
                            $emp_salary_report->motorcycle_adv = $motorcycle_deduct_amount;
                            $emp_other_deduction_sum = EmployeeOtherDeductions::where('employeeid','=',$q->autoemployeeid)
                            ->where('otherdeduction_date','like',$data['salary_month'].'%')
                            ->sum('otherdeduction_installment');
                            $emp_salary_report->others = $emp_other_deduction_sum - $lfp;

                            $emp_salary_report->save();
                        }
                    }

                    return redirect('/employee_salary_slips_all/'.$data['salary_month'])->with('success',"Created Successfully");
                }
                else
                {
                    return back()->with('error',"No Employee Found");
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_employee_salary_slip($employeeid,$salary_month)
    {
        if(Session::get('admin_id'))
        {
            $q = Employee::orderby('autoemployeeid','asc')
            ->where('autoemployeeid','=',$employeeid)
            ->get();
            if(count($q) > 0)
            {
                foreach($q as $q);
                $q1 = EmployeeSalarySlip::where('employeeid','=',$employeeid)
                ->where('salary_month','=',$salary_month)
                ->get();
                if(count($q1) == 1)
                {
                    $q2 = EmployeeGPFundSubscription::where('employeeid','=',$employeeid)
                    ->where('gp_fund_month','=',$salary_month)
                    ->get();
                    foreach($q2 as $q2);

                    $q3 = GPFundSubscriptionBalance::where('employeeid','=',$employeeid)
                    ->get();
                    if(count($q3) == 1)
                    {
                        foreach($q3 as $q3);
                        $q4 = GPFundSubscriptionBalance::where('employeeid','=',$employeeid)
                        ->update(
                            [
                                'gp_fund_amount'    =>  $q3->gp_fund_amount - $q2->gp_fund_amount,
                                'gp_fund_balance'    =>  $q3->gp_fund_balance - $q2->gp_fund_amount,
                            ]
                        );
                    }

                    $q5_1 = EmployeeGPLoan::where('employeeid','=',$employeeid)
                    ->orderby('autoemployeegploanid','desc')
                    ->get();
                    if(count($q5_1) > 0)
                    {
                        foreach($q5_1 as $q5_1);
                        $gp_adv_ins = $q5_1->gp_loan_installment;
                    }
                    else
                    {
                        $gp_adv_ins = '0';
                    }

                    $q5_2 = EmployeeGPLoanBalance::where('employeeid','=',$employeeid)
                    ->get();
                    if(count($q5_2) > 0)
                    {
                        foreach($q5_2 as $q5_2);

                        $q5_3 = EmployeeGPLoanBalance::where('employeeid','=',$employeeid)
                        ->update(
                            [
                                'gp_balance_amount'    =>  $q5_2->gp_balance_amount + $gp_adv_ins,
                                'updated_by' => Session::get('admin_id'),
                            ]
                        );
                    }

                    $q5 = EmployeeHBDeduction::where('employeeid','=',$employeeid)
                    ->where('hb_deduction_month','=',$salary_month)
                    ->get();
                    if(count($q5) > 0)
                    {
                        foreach($q5 as $q5);

                        $q6 = EmployeeHBLoanBalance::where('employeeid','=',$employeeid)
                        ->sum('hb_balance_amount');    

                        $q7 = EmployeeHBLoanBalance::where('employeeid','=',$employeeid)
                        ->update(
                            [
                                'hb_balance_amount'    =>  $q6 + $q5->hb_deduction_amount,
                            ]
                        );
                    }

                    $q8 = EmployeeMotorCarDeduction::where('employeeid','=',$employeeid)
                    ->where('motorcar_deduction_month','=',$salary_month)
                    ->get();
                    if(count($q8) > 0)
                    {
                        foreach($q8 as $q8);

                        $q9 = EmployeeMotorCarLoanBalance::where('employeeid','=',$employeeid)
                        ->sum('motorcar_balance_amount');

                        $q10 = EmployeeMotorCarLoanBalance::where('employeeid','=',$employeeid)
                        ->update(
                            [
                                'motorcar_balance_amount'    =>  $q9 + $q8->motorcar_deduction_amount,
                                'updated_by' => Session::get('admin_id'),
                            ]
                        );
                    }

                    $q11 = EmployeeMotorCycleDeduction::where('employeeid','=',$employeeid)
                    ->where('motorcycle_deduction_month','=',$salary_month)
                    ->get();
                    if(count($q11) > 0)
                    {
                        foreach($q11 as $q11);

                        $q12 = EmployeeMotorCycleLoanBalance::where('employeeid','=',$employeeid)
                        ->sum('motorcycle_balance_amount');

                        $q13 = EmployeeMotorCycleLoanBalance::where('employeeid','=',$employeeid)
                        ->update(
                            [
                                'motorcycle_balance_amount'    =>  $q12 + $q11->motorcycle_deduction_amount,
                                'updated_by' => Session::get('admin_id'),
                            ]
                        );
                    }

                    $q14 = OtherDeductionStatment::where('employeeid','=',$employeeid)
                    ->where('otherdeduction_month','=',$salary_month)
                    ->get();
                    if(count($q14) > 0)
                    {
                        foreach($q14 as $q14)
                        {
                            $q15 = EmployeeOtherDeductions::where('autoemployeeotherdeductionid','=',$q14->employeeotherdeductionid)
                            ->update(
                                [
                                    'otherdeduction_balance'    =>  $q14->otherdeduction_balance + $q14->otherdeduction_installment,
                                ]
                            );
                        }
                    }

                    $q15 = EmployeeSalarySlip::where('employeeid','=',$employeeid)
                    ->where('salary_month','=',$salary_month)
                    ->delete();
                    
                    $q16 = EmployeeGPFundSubscription::where('employeeid','=',$employeeid)
                    ->where('gp_fund_month','=',$salary_month)
                    ->delete();

                    $q17 = EmployeeHBDeduction::where('employeeid','=',$employeeid)
                    ->where('hb_deduction_month','=',$salary_month)
                    ->delete();

                    $q18 = EmployeeMotorCarDeduction::where('employeeid','=',$employeeid)
                    ->where('motorcar_deduction_month','=',$salary_month)
                    ->delete();

                    $q19 = EmployeeMotorCycleDeduction::where('employeeid','=',$employeeid)
                    ->where('motorcycle_deduction_month','=',$salary_month)
                    ->delete();

                    $q20 = OtherDeductionStatment::where('employeeid','=',$employeeid)
                    ->where('otherdeduction_month','=',$salary_month)
                    ->delete();

                    $q21 = DeductionStatment::where('employeeid','=',$employeeid)
                    ->where('month','=',$salary_month)
                    ->delete();

                    $q22 = GPFundSubscriptionStatment::where('employeeid','=',$employeeid)
                    ->where('gp_fund_statment_month','=',$salary_month)
                    ->delete();

                    $q23 = EmployeeSalaryReport::where('employeeid','=',$employeeid)
                    ->where('salary_month','=',$salary_month)
                    ->delete();

                    return redirect('/employees')->with('success',"Deleted Successfully");
                }
                else
                {
                    return back()->with('error',"No Salary Data Found");
                }
            }
            else
            {
                return back()->with('error',"No Employee Found");
            }
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function dummydatahbdeduction()
    {
        // $q = EmployeeHBLoan::orderby('autoemployeehbloanid','desc')->get();
        // if(count($q) > 0)
        // {
        //     foreach($q as $q)
        //     {
        //         $q15_3 = new EmployeeHBLoanBalance;
        //         $q15_3->employeeid = $q->employeeid;
        //         $q15_3->hb_balance_amount = $q->hb_approved_amount;
        //         $q15_3->added_by = Session::get('admin_id');
        //         $q15_3->save();    
        //     }
        // }
        $q = EmployeeHBLoan::orderby('autoemployeehbloanid','desc')
        ->get();
        if(count($q) > 0)
        {
            foreach($q as $q)
            {
                $toDate = Carbon::parse($q->hb_loan_date);
                $fromDate = Carbon::parse(date('Y-m-d',time()));

                $months = $toDate->diffInMonths($fromDate);
                if($months != '0')
                {
                    // echo $q->employeeid.'----'.$months;
                    // echo '-------';
                    for($i=0;$i<$months-1;$i++)
                    {
                        $salary_month = date('Y-m',strtotime("+".$i." month",strtotime($q->hb_loan_date)));
                        // echo $salary_month.'<br>';
                        $q1 = Employee::where('autoemployeeid','=',$q->employeeid)->get();
                        if(count($q1) > 0)
                        {
                            foreach($q1 as $q1);

                            $q7 = EmployeeHBLoanBalance::where('employeeid','=',$q->employeeid)
                            ->sum('hb_balance_amount');                                
                            if($q7 > 0)
                            {
                                if($q7 > $q1->hb_advance)
                                {
                                    $hb_deduct_amount = $q1->hb_advance;
                                }   
                                else
                                {
                                    $hb_deduct_amount = $q7;
                                }
                                $q7_1 = EmployeeHBDeduction::where('employeeid','=',$q->employeeid)
                                ->orderby('autoemployeehbdeductionid','desc')
                                ->offset(0)->limit(1)
                                ->get();
                                if(count($q7_1) > 0)
                                {
                                    foreach($q7_1 as $q7_1);
                                    $q7_2 = new EmployeeHBDeduction;
                                    $q7_2->employeeid = $q->employeeid;
                                    $q7_2->hb_deduction_month = $salary_month;
                                    $q7_2->hb_deduction_amount = $hb_deduct_amount;
                                    $q7_2->hb_deduction_prograssive = $q7_1->hb_deduction_prograssive + $hb_deduct_amount;
                                    $q7_2->hb_deduction_rem_bal = $q7_1->hb_deduction_rem_bal - $hb_deduct_amount;
                                    $q7_2->added_by = Session::get('admin_id');
                                    $q7_2->save();  
                                }
                                else
                                {
                                    $q8 = new EmployeeHBDeduction;
                                    $q8->employeeid = $q->employeeid;
                                    $q8->hb_deduction_month = $salary_month;
                                    $q8->hb_deduction_amount = $hb_deduct_amount;
                                    $q8->hb_deduction_prograssive = $hb_deduct_amount;
                                    $q8->hb_deduction_rem_bal = $q7 - $hb_deduct_amount;
                                    $q8->added_by = Session::get('admin_id');
                                    $q8->save();    
                                }

                                $q9 = EmployeeHBLoanBalance::where('employeeid','=',$q->employeeid)
                                ->update(
                                    [
                                        'hb_balance_amount'    =>  $q7 - $hb_deduct_amount,
                                        'updated_by' => Session::get('admin_id'),
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    function dummydatamotorcardeduction()
    {
        // $q = EmployeeMotorCarLoan::orderby('autoemployeemotorcarloanid','desc')->get();
        // if(count($q) > 0)
        // {
        //     foreach($q as $q)
        //     {
        //         $q15_3 = new EmployeeMotorCarLoanBalance;
        //         $q15_3->employeeid = $q->employeeid;
        //         $q15_3->motorcar_balance_amount = $q->motorcar_approved_amount;
        //         $q15_3->added_by = Session::get('admin_id');
        //         $q15_3->save();    
        //     }
        // }
        $q = EmployeeMotorCarLoan::orderby('autoemployeemotorcarloanid','desc')->get();
        if(count($q) > 0)
        {
            foreach($q as $q)
            {
                $toDate = Carbon::parse($q->motorcar_loan_date);
                $fromDate = Carbon::parse(date('Y-m-d',time()));

                $months = $toDate->diffInMonths($fromDate);
                // echo $q->employeeid.'----'.$months;
                // echo '-------';
                if($months != '0')
                {
                    for($i=0;$i<$months-1;$i++)
                    {
                        $salary_month = date('Y-m',strtotime("+".$i." month",strtotime($q->motorcar_loan_date)));
                        $q1 = Employee::where('autoemployeeid','=',$q->employeeid)->get();
                        if(count($q1) > 0)
                        {
                            foreach($q1 as $q1);
    
                            $q15 = EmployeeMotorCarLoanBalance::where('employeeid','=',$q->employeeid)
                            ->sum('motorcar_balance_amount');  
                            if($q15 > 0)
                            {
                                $q15_1 = EmployeeMotorCarLoan::where('employeeid','=',$q->employeeid)
                                ->sum('motorcar_approved_amount');  
                                $motorcar_installment = $q15_1 / 60;                           
                                if($q15 > $motorcar_installment)
                                {
                                    $motorcar_deduct_amount = $motorcar_installment;
                                }   
                                else
                                {
                                    $motorcar_deduct_amount = $q15;
                                }

                                $q15_2 = EmployeeMotorCarDeduction::where('employeeid','=',$q->employeeid)
                                ->orderby('autoemployeemotorcardeductionid','desc')
                                ->offset(0)->limit(1)
                                ->get();
                                if(count($q15_2) > 0)
                                {
                                    foreach($q15_2 as $q15_2);

                                    if($q15_2->motorcar_deduction_month != $salary_month)
                                    {
                                        $q15_3 = new EmployeeMotorCarDeduction;
                                        $q15_3->employeeid = $q->employeeid;
                                        $q15_3->motorcar_deduction_month = $salary_month;
                                        $q15_3->motorcar_deduction_amount = $motorcar_deduct_amount;
                                        $q15_3->motorcar_deduction_prograssive = $motorcar_deduct_amount + $q15_2->motorcar_deduction_prograssive;
                                        $q15_3->motorcar_deduction_rem_bal = $q15_2->motorcar_deduction_rem_bal - $motorcar_deduct_amount;
                                        $q15_3->added_by = Session::get('admin_id');
                                        $q15_3->save();    
                                    }
                                    else
                                    {
                                        continue;
                                    }
                                }
                                else
                                {
                                    $q16 = new EmployeeMotorCarDeduction;
                                    $q16->employeeid = $q->employeeid;
                                    $q16->motorcar_deduction_month = $salary_month;
                                    $q16->motorcar_deduction_amount = $motorcar_deduct_amount;
                                    $q16->motorcar_deduction_prograssive = $motorcar_deduct_amount;
                                    $q16->motorcar_deduction_rem_bal = $q15 - $motorcar_deduct_amount;
                                    $q16->added_by = Session::get('admin_id');
                                    $q16->save();
                                }
    
                                $q17 = EmployeeMotorCarLoanBalance::where('employeeid','=',$q->employeeid)
                                ->update(
                                    [
                                        'motorcar_balance_amount'    =>  $q15 - $motorcar_deduct_amount,
                                        'updated_by' => Session::get('admin_id'),
                                    ]
                                );
                            }
                        }
                    }    
                }
            }
        }
    }

    function dummydatamotorcyclededuction()
    {
        // $q = EmployeeMotorCycleLoan::orderby('autoemployeemotorcycleloanid','desc')
        // ->get();
        // if(count($q) > 0)
        // {
        //     foreach($q as $q)
        //     {
        //         $q15_3 = new EmployeeMotorCycleLoanBalance;
        //         $q15_3->employeeid = $q->employeeid;
        //         $q15_3->motorcycle_balance_amount = $q->motorcycle_approved_amount;
        //         $q15_3->added_by = Session::get('admin_id');
        //         $q15_3->save();  
        //     }
        // }
        $q = EmployeeMotorCycleLoan::orderby('autoemployeemotorcycleloanid','desc')
        ->where('employeeid','=','87')
        ->get();
        if(count($q) > 0)
        {
            foreach($q as $q)
            {
                $toDate = Carbon::parse($q->motorcycle_loan_date);
                $fromDate = Carbon::parse(date('Y-m-d',time()));

                $months = $toDate->diffInMonths($fromDate);
                // echo $q->employeeid.'----'.$months;
                // echo '-------';
                if($months != '0')
                {
                    for($i=0;$i<$months-1;$i++)
                    {
                        $salary_month = date('Y-m',strtotime("+".$i." month",strtotime($q->motorcycle_loan_date)));
                        // echo '-------';
                        $q1 = Employee::where('autoemployeeid','=',$q->employeeid)->get();
                        if(count($q1) > 0)
                        {
                            foreach($q1 as $q1);
    
                            $q15 = EmployeeMotorCycleLoanBalance::where('employeeid','=',$q->employeeid)
                            ->sum('motorcycle_balance_amount');  
                            
                            $q15_1 = EmployeeMotorCycleLoan::where('employeeid','=',$q->employeeid)
                            ->sum('motorcycle_approved_amount');  
                            // $motorcycle_installment = $q15_1 / 60;
                            $motorcycle_installment = $q->motorcycle_installment_amount;   
                            if($q15 > $motorcycle_installment)
                            {
                                $motorcycle_deduct_amount = $motorcycle_installment;
                            }   
                            else
                            {
                                $motorcycle_deduct_amount = $q15;
                            }
    
                            $q15_2 = EmployeeMotorCycleDeduction::where('employeeid','=',$q->employeeid)
                            ->orderby('autoemployeemotorcycledeductionid','desc')
                            ->offset(0)->limit(1)
                            ->get();
                            if(count($q15_2) > 0)
                            {
                                foreach($q15_2 as $q15_2);
                                if($q15_2->motorcycle_deduction_month != $salary_month)
                                {
                                    $q15_3 = new EmployeeMotorCycleDeduction;
                                    $q15_3->employeeid = $q->employeeid;
                                    $q15_3->motorcycle_deduction_month = $salary_month;
                                    $q15_3->motorcycle_deduction_amount = $motorcycle_deduct_amount;
                                    $q15_3->motorcycle_deduction_prograssive = $motorcycle_deduct_amount + $q15_2->motorcycle_deduction_prograssive;
                                    $q15_3->motorcycle_deduction_rem_bal = $q15_2->motorcycle_deduction_rem_bal - $motorcycle_deduct_amount;
                                    $q15_3->added_by = Session::get('admin_id');
                                    $q15_3->save();    
                                }
                                else
                                {
                                    continue;
                                }
                            }
                            else
                            {
                                $q16 = new EmployeeMotorCycleDeduction;
                                $q16->employeeid = $q->employeeid;
                                $q16->motorcycle_deduction_month = $salary_month;
                                $q16->motorcycle_deduction_amount = $motorcycle_deduct_amount;
                                $q16->motorcycle_deduction_prograssive = $motorcycle_deduct_amount;
                                $q16->motorcycle_deduction_rem_bal = $q15 - $motorcycle_deduct_amount;
                                $q16->added_by = Session::get('admin_id');
                                $q16->save();
                            }
    
                            $q17 = EmployeeMotorCycleLoanBalance::where('employeeid','=',$q->employeeid)
                            ->update(
                                [
                                    'motorcycle_balance_amount'    =>  $q15 - $motorcycle_deduct_amount,
                                    'updated_by' => Session::get('admin_id'),
                                ]
                            );
                        }
                    }    
                }
            }
        }
    }

    function checkhbdeductionbalance()
    {
        if(Session::get('admin_id'))
        {
            return view('checkhbdeductionbalance');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function checkmotorcardeductionbalance()
    {
        if(Session::get('admin_id'))
        {
            return view('checkmotorcardeductionbalance');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function checkmotorcycledeductionbalance()
    {
        if(Session::get('admin_id'))
        {
            return view('checkmotorcycledeductionbalance');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function checkgpbalance()
    {
        if(Session::get('admin_id'))
        {
            return view('checkgpbalance');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }
    
    function gp_subscription()
    {
        if(Session::get('admin_id'))
        {
            return view('gp_subscription');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function gp_subscription_db(Request $request)
    {
        $rules = [
            'employee'  =>  'required|string',
            'closing_bal'  =>  'required|string',
            'insjul'  =>  'required|string',
		];

		$validator = Validator::make($request->all(),$rules);
		if ($validator->fails()) {
			return back()
			->withInput()
			->withErrors($validator);
		}
		else{
            $data = $request->input();
			try{
				// dd($data);
                $query = GPFundSubscriptionBalance::where('employeeid','=',$data['employee'])
                ->get();
                if(count($query) > 0)
                {
                    return redirect('/gp_subscription')->with('error',"Already Added");
                }
                else
                {
                    $ins = $data['insjul'] / 2;
                    if($data['withdrawl']!= '')
                    {
                        $inswid = $data['withdrawl'] / 2;
                    }
                    else
                    {
                        $inswid = 0;
                    }
                    if($data['transfer']!= '')
                    {
                        $transfer = $data['transfer'];
                    }
                    else
                    {
                        $transfer = 0;
                    }
                    if($data['widamount']!= '')
                    {
                        $widamount = $data['widamount'];
                    }
                    else
                    {
                        $widamount = 0;
                    }
    
                    $q1 = new GPFundSubscriptionStatment;
                    $q1->employeeid = $data['employee'];
                    $q1->gp_fund_statment_month = '2022-06';
                    $q1->progressive_balance = $data['closing_bal'];
                    $q1->added_by = Session::get('admin_id');
                    $q1->save();	

                    $projul = $data['closing_bal'] + $ins + $inswid;

                    $q2 = new GPFundSubscriptionStatment;
                    $q2->employeeid = $data['employee'];
                    $q2->gp_fund_statment_month = '2022-07';
                    $q2->monthly_subscription = $ins;
                    $q2->refund_installment_amount = $inswid;
                    $q2->progressive_balance = $projul;
                    $q2->added_by = Session::get('admin_id');
                    $q2->save();	

                    $proaug = $projul + $ins + $inswid + $transfer - $widamount;

                    $q3 = new GPFundSubscriptionStatment;
                    $q3->employeeid = $data['employee'];
                    $q3->gp_fund_statment_month = '2022-08';
                    $q3->monthly_subscription = $ins;
                    $q3->refund_installment_amount = $inswid;
                    $q3->transfer_amount = $transfer;
                    $q3->widrawl_amount = $widamount;
                    $q3->progressive_balance = $proaug;
                    $q3->added_by = Session::get('admin_id');
                    $q3->save();	

                    $q = new GPFundSubscriptionBalance;
                    $q->employeeid = $data['employee'];
                    $q->gp_fund_amount = $proaug;
                    $q->gp_fund_balance = $proaug;
                    $q->added_by = Session::get('admin_id');
                    $q->save();
                    
                    return redirect('/gp_subscription')->with('success',"Added Successfully");
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }
}
