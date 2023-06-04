<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Hash;
use Session;
use Illuminate\Support\Str;
use Image;
use App\Models\AdminLogin;
use App\Models\HeadCategoriesBudget;
use App\Models\HeadsBudget;
use App\Models\MainHeadBudget;
use App\Models\SubHeadBudget;
use App\Models\Head;
use App\Models\MainHead;
use App\Models\SubHead;
use App\Models\Ledger;
use App\Models\HeadCategoriesBalanace;
use App\Models\HeadsBalanace;
use App\Models\MainHeadsBalanace;
use App\Models\SubHeadsBalanace;
use App\Models\EmployeeGPLoan;
use App\Models\EmployeeGPLoanBalance;
use App\Models\EmployeeHBLoan;
use App\Models\EmployeeHBLoanBalance;
use App\Models\EmployeeMotorCarLoan;
use App\Models\EmployeeMotorCarLoanBalance;
use App\Models\EmployeeMotorCycleLoan;
use App\Models\EmployeeMotorCycleLoanBalance;
use App\Models\Employee;

class DashboardController extends Controller
{
    function index()
    {
        if(Session::get('admin_id'))
        {
            return view('dashboard.index');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function myprofile()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array = AdminLogin::find(Session::get('admin_id'));
            return view('dashboard.myprofile',compact('array'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function myprofiledb(Request $request)
    {
        $rules = [
            'name'  =>  'required|string',
            'email'  =>  'required|email',
            'gender'  =>  'required|string',
            'phone'  =>  'required|string',
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
				
				$admin = AdminLogin::find(Session::get('admin_id'));
                $admin->name = $data['name'];
                $admin->email = $data['email'];
                $admin->gender = $data['gender'];
                $admin->phone = $data['phone'];                

				$admin->save();
				return redirect('/myprofile')->with('success',"Update Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function mypassworddb(Request $request)
    {
        $rules = [
            'password'  =>  'required|string',
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
				
				$admin = AdminLogin::find(Session::get('admin_id'));
                $admin->password = Hash::make($data['password']);

				$admin->save();
				return redirect('/myprofile')->with('success',"Update Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_data()
    {
        $q = SubHeadBudget::get();
        if(!empty($q))
        {
            foreach($q as $q)
            {
                $q1 = SubHeadBudget::where('subheadid','=',$q['subheadid'])
                ->update(
                    [
                        'sub_head_budget_balance'  =>  $q['sub_head_budget_amount']
                    ]
                );

                $q2 = SubHeadsBalanace::where('subheadid','=',$q['subheadid'])
                ->update(
                    [
                        'sub_head_balance_amount'  =>  $q['sub_head_budget_amount']
                    ]
                );
            }
        }
    }

    function datafetchcategory()
    {
        $query = HeadCategoriesBudget::get();
        dd($query);
        if(!empty($query))
        {
            foreach($query as $query)
            {
                $query1 = new HeadCategoriesBalanace;
                $query1->headcategoryid = $query->headcategoryid;
                $query1->head_category_financial_year = $query->head_category_financial_year;
                $query1->head_category_total_amount = $query->head_category_budget_amount;
                $query1->head_category_balance_amount = $query->head_category_budget_balance;
                $query1->added_by = 1;
                $query1->save();
            }
        }
    }

    function datafetchhead()
    {
        $query = HeadsBudget::get();
        dd($query);
        if(!empty($query))
        {
            foreach($query as $query)
            {
                $query1 = new HeadsBalanace;
                $query1->headid = $query->headid;
                $query1->head_financial_year = $query->head_financial_year;
                $query1->head_total_amount = $query->head_budget_amount;
                $query1->head_balance_amount = $query->head_budget_balance;
                $query1->added_by = 1;
                $query1->save();
            }
        }
    }

    function datafetchmainhead()
    {
        $query = MainHeadBudget::get();
        dd($query);
        if(!empty($query))
        {
            foreach($query as $query)
            {
                $query1 = new MainHeadsBalanace;
                $query1->mainheadid = $query->mainheadid;
                $query1->main_head_financial_year = $query->main_head_financial_year;
                $query1->main_head_total_amount = $query->main_head_budget_amount;
                $query1->main_head_balance_amount = $query->main_head_budget_balance;
                $query1->added_by = 1;
                $query1->save();
            }
        }
    }

    function datafetchsubhead()
    {
        $query = SubHeadBudget::get();
        dd($query);
        if(!empty($query))
        {
            foreach($query as $query)
            {
                $query1 = new SubHeadsBalanace;
                $query1->subheadid = $query->subheadid;
                $query1->sub_head_financial_year = $query->sub_head_financial_year;
                $query1->sub_head_total_amount = $query->sub_head_budget_amount;
                $query1->sub_head_balance_amount = $query->sub_head_budget_balance;
                $query1->added_by = 1;
                $query1->save();
            }
        }
    }

    // function update_gp_loan_employee_balances()
    // {
    //     $q = EmployeeGPLoan::where('gp_loan_date','<','2022-09-01')
    //     // ->where('employeeid','=','85')
    //     ->get();
    //     if(!empty($q))
    //     {
    //         $count = 1;
    //         foreach($q as $q)
    //         {
    //             // $toDate = Carbon::parse($q->gp_loan_date);
    //             // $fromDate = Carbon::parse(date('2022-08-01',time()));

    //             // $months = $toDate->diffInMonths($fromDate);
    //             // echo $count++.'______'.$months.'_____'.$q->employeeid.'_________';
    //             // echo $q->gp_loan_date.'<br>';

    //             // $q5_3 = EmployeeGPLoanBalance::where('employeeid','=',$q->employeeid)
    //             //     ->update(
    //             //         [
    //             //             'gp_balance_amount'    =>  $q->gp_loan_amount,
    //             //         ]
    //             //     );

    //             // $result = CarbonPeriod::create($q->gp_loan_date, '1 month', '2022-08-01');
    //             // foreach ($result as $dt) 
    //             // {
    //             //     $q1 = EmployeeGPLoanBalance::where('employeeid','=',$q->employeeid)
    //             //     ->get();
    //             //     if(!empty($q1))
    //             //     {
    //             //         foreach($q1 as $q1);

    //             //         if($q1->gp_balance_amount > $q->gp_loan_installment)
    //             //         {
    //             //             $ins_amount = $q->gp_loan_installment;
    //             //         }
    //             //         else
    //             //         {
    //             //             $ins_amount = $q1->gp_balance_amount;
    //             //         }
    //             //         $q5_3 = EmployeeGPLoanBalance::where('employeeid','=',$q->employeeid)
    //             //         ->update(
    //             //             [
    //             //                 'gp_balance_amount'    =>  $q1->gp_balance_amount - $ins_amount,
    //             //             ]
    //             //         );
    //             //     }
    //             //     // echo $dt->format("Y-m").'------';
    //             // }  
    //             // echo $count++.'______'.$q->employeeid.'<br>';         
    //         }
    //     }

    // }

    // function update_hb_loan_employee_balances()
    // {
    //     $q = EmployeeHBLoan::where('hb_loan_date','<','2022-09-01')
    //     // ->where('employeeid','=','85')
    //     ->get();
    //     if(!empty($q))
    //     {
    //         $count = 1;
    //         foreach($q as $q)
    //         {
    //             // $q5_3 = EmployeeHBLoanBalance::where('employeeid','=',$q->employeeid)
    //             // ->update(
    //             //     [
    //             //         'hb_balance_amount'    =>  $q->hb_loan_amount,
    //             //     ]
    //             // );
    //             // $result = CarbonPeriod::create($q->hb_loan_date, '1 month', '2022-08-01');
    //             // foreach ($result as $dt) 
    //             // {
    //             //     $q1 = EmployeeHBLoanBalance::where('employeeid','=',$q->employeeid)
    //             //     ->get();
    //             //     if(!empty($q1))
    //             //     {
    //             //         foreach($q1 as $q1);

    //             //         $qq = Employee::where('autoemployeeid','=',$q->employeeid)->get();
    //             //         if(!empty($qq))
    //             //         {
    //             //             foreach($qq as $qq);
    //             //             if($q1->hb_balance_amount > $qq->hb_advance)
    //             //             {
    //             //                 $ins_amount = $qq->hb_advance;
    //             //             }
    //             //             else
    //             //             {
    //             //                 $ins_amount = $q1->hb_balance_amount;
    //             //             }
    //             //             $q5_3 = EmployeeHBLoanBalance::where('employeeid','=',$q->employeeid)
    //             //             ->update(
    //             //                 [
    //             //                     'hb_balance_amount'    =>  $q1->hb_balance_amount - $ins_amount,
    //             //                 ]
    //             //             );
    //             //         }
    //             //     }
    //             //     echo $dt->format("Y-m").'------';
    //             // }  
    //             // echo $count++.'______'.$q->employeeid.'<br>';         
    //         }
    //     }

    // }

    function update_motorcar_loan_employee_balances()
    {
        $q = EmployeeMotorCarLoan::where('motorcar_loan_date','<','2022-09-01')
        // ->where('employeeid','=','85')
        ->get();
        if(!empty($q))
        {
            $count = 1;
            foreach($q as $q)
            {
                // $toDate = Carbon::parse($q->motorcar_loan_date);
                // $fromDate = Carbon::parse(date('2022-08-01',time()));

                // $months = $toDate->diffInMonths($fromDate);
                // echo $count++.'______'.$months.'_____'.$q->employeeid.'_________';
                // echo $q->motorcar_loan_date.'<br>';

                // $q5_3 = EmployeeMotorCarLoanBalance::where('employeeid','=',$q->employeeid)
                //     ->update(
                //         [
                //             'motorcar_balance_amount'    =>  $q->motorcar_loan_amount,
                //         ]
                //     );

                $result = CarbonPeriod::create($q->motorcar_loan_date, '1 month', '2022-08-01');
                foreach ($result as $dt) 
                {
                    $q1 = EmployeeMotorCarLoanBalance::where('employeeid','=',$q->employeeid)
                    ->get();
                    if(count($q1) > 0)
                    {
                        foreach($q1 as $q1);

                        $motorcar_installment = $q->motorcar_installment_amount; 

                        if($q1->motorcar_balance_amount > $motorcar_installment)
                        {
                            $ins_amount = $motorcar_installment;
                        }
                        else
                        {
                            $ins_amount = $q1->motorcar_balance_amount;
                        }
                        $q5_3 = EmployeeMotorCarLoanBalance::where('employeeid','=',$q->employeeid)
                        ->update(
                            [
                                'motorcar_balance_amount'    =>  $q1->motorcar_balance_amount - $ins_amount,
                            ]
                        );
                    }
                    // echo $dt->format("Y-m").'------';
                }  
                // echo $count++.'______'.$q->employeeid.'<br>';         
            }
        }

    }

    function update_motorcycle_loan_employee_balances()
    {
        $q = EmployeeMotorCycleLoan::where('motorcycle_loan_date','<','2022-09-01')
        // ->where('employeeid','=','87')
        ->get();
        if(!empty($q))
        {
            $count = 1;
            foreach($q as $q)
            {
                // $toDate = Cyclebon::parse($q->motorcycle_loan_date);
                // $fromDate = Cyclebon::parse(date('2022-08-01',time()));

                // $months = $toDate->diffInMonths($fromDate);
                // echo $count++.'______'.$months.'_____'.$q->employeeid.'_________';
                // echo $q->motorcycle_loan_date.'<br>';

                // $q5_3 = EmployeeMotorCycleLoanBalance::where('employeeid','=',$q->employeeid)
                //     ->update(
                //         [
                //             'motorcycle_balance_amount'    =>  $q->motorcycle_loan_amount,
                //         ]
                //     );

                $result = CarbonPeriod::create($q->motorcycle_loan_date, '1 month', '2022-08-01');
                foreach ($result as $dt) 
                {
                    $q1 = EmployeeMotorCycleLoanBalance::where('employeeid','=',$q->employeeid)
                    ->get();
                    if(count($q1) > 0)
                    {
                        foreach($q1 as $q1);

                        $motorcycle_installment = $q->motorcycle_installment_amount; 

                        if($q1->motorcycle_balance_amount > $motorcycle_installment)
                        {
                            $ins_amount = $motorcycle_installment;
                        }
                        else
                        {
                            $ins_amount = $q1->motorcycle_balance_amount;
                        }
                        $q5_3 = EmployeeMotorCycleLoanBalance::where('employeeid','=',$q->employeeid)
                        ->update(
                            [
                                'motorcycle_balance_amount'    =>  $q1->motorcycle_balance_amount - $ins_amount,
                            ]
                        );
                    }
                    // echo $dt->format("Y-m").'------';
                }  
                // echo $count++.'______'.$q->employeeid.'<br>';         
            }
        }

    }
}
