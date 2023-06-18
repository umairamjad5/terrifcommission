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
use App\Models\EmployeeAllowance;
use App\Models\OverAllBudget;
use App\Models\QuarterlyBudget;
use App\Models\OverAllBankBalance;
use App\Models\HeadCategoriesBudget;
use App\Models\HeadsBudget;
use App\Models\MainHeadBudget;
use App\Models\SubHeadBudget;
use App\Models\Head;
use App\Models\MainHead;
use App\Models\SubHead;
use App\Models\HeadCategoriesBalanace;
use App\Models\HeadsBalanace;
use App\Models\MainHeadsBalanace;
use App\Models\SubHeadsBalanace;

class EmployeeAllowanceController extends Controller
{
    function validate_heads_allowance($autoemployeeid)
    {
        if(Session::get('admin_id'))
        {
            return view('employees.validate_heads_allowance',compact('autoemployeeid'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function add_allowances(Request $request)
    {
        $rules = [
            'sub_head'  =>  'required|string',
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
                $sub_head = $data['sub_head'];
                $query = SubHead::where('autosubheadid','=',$sub_head)->get();
                foreach($query as $query);

                return redirect('/action_allowances/'.$query->headcategoryid.'/'.$query->headid.'/'.$query->mainheadid.'/'.$query->autosubheadid.'/'.$data['employeeid']);
            }
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function employee_allowances($autoemployeeid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array = EmployeeAllowance::join("employees","employee_allowances.employeeid",'=',"employees.autoemployeeid")
            ->join("head_categories","employee_allowances.headcategoryid",'=',"head_categories.autoheadcategoryid")
            ->join("heads","employee_allowances.headid",'=',"heads.autoheadid")
            ->join("main_heads","employee_allowances.mainheadid",'=',"main_heads.automainheadid")
            ->join("sub_heads","employee_allowances.subheadid",'=',"sub_heads.autosubheadid")
            ->where("employee_allowances.employeeid",'=',$autoemployeeid)
            ->orderby('employee_allowances.autoemployeeallowanceid','desc')->get();
            return view('employees.employee_allowances',compact('array','autoemployeeid'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function list_employee_allowances()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array = EmployeeAllowance::join("employees","employee_allowances.employeeid",'=',"employees.autoemployeeid")
            ->join("head_categories","employee_allowances.headcategoryid",'=',"head_categories.autoheadcategoryid")
            ->join("heads","employee_allowances.headid",'=',"heads.autoheadid")
            ->join("main_heads","employee_allowances.mainheadid",'=',"main_heads.automainheadid")
            ->join("sub_heads","employee_allowances.subheadid",'=',"sub_heads.autosubheadid")
            ->orderby('employee_allowances.autoemployeeallowanceid','desc')->get();
            return view('employees.list_employee_allowances',compact('array'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function action_allowances($autoemployeeid)
    {
        if(Session::get('admin_id'))
        {
            return view('employees.action_allowances',compact('autoemployeeid'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function employee_allowance_db(Request $request)
    {
        $rules = [
            'sub_head'  =>  'required|string',
            'allowance_amount'  =>  'required|string',
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
                $data['allowance_financialyear'] = '';
                $data['allowance_quarter'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['allowance_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['allowance_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                {
                    $data['allowance_quarter'] = '3rd Quarter';
                }
                if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                {
                    $data['allowance_quarter'] = '4th Quarter';
                }
                if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                {
                    $data['allowance_quarter'] = '1st Quarter';
                }
                if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                {
                    $data['allowance_quarter'] = '2nd Quarter';
                }
                $subheadids = SubHead::where('autosubheadid','=',$data['sub_head'])->get();
                foreach($subheadids as $subheadids);

                $data['headcategoryid'] = $subheadids->headcategoryid;
                $data['headid'] = $subheadids->headid;
                $data['mainheadid'] = $subheadids->mainheadid;
                $data['subheadid'] = $subheadids->autosubheadid;

                $employee_allowance = new EmployeeAllowance;
                $employee_allowance->financialyear = $data['allowance_financialyear'];
                $employee_allowance->quarter = $data['allowance_quarter'];
                $employee_allowance->employeeid = $data['employeeid'];
                $employee_allowance->headcategoryid = $data['headcategoryid'];
                $employee_allowance->headid = $data['headid'];
                $employee_allowance->mainheadid = $data['mainheadid'];
                $employee_allowance->subheadid = $data['subheadid'];
                $employee_allowance->allowance_amount = $data['allowance_amount'];
                $employee_allowance->added_by = Session::get('admin_id');

                $employee_allowance->save();
                return redirect('/employee_allowances/'.$data['employeeid'])->with('success',"Insert Successfully");
                // $query_check = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                // ->where('sub_head_financial_year','=',$data['allowance_financialyear'])
                // ->where('sub_head_quarter','=',$data['allowance_quarter'])
                // ->get();
                // if(count($query_check) > 0)
                // {
                    

                //     // $query1 = OverAllBudget::where('financialyear','=',$data['allowance_financialyear'])
                //     // ->get();
                //     // if(!empty($query1))
                //     // {
                //     //     foreach($query1 as $row1);

                //     //     $update_query1 = OverAllBudget::where('financialyear','=',$data['allowance_financialyear'])
                //     //     ->update(
                //     //         [
                //     //             'balance_amount'  =>  $row1->balance_amount - $data['allowance_amount'],
                //     //         ]
                //     //     );
                //     // }

                //     // $query1 = QuarterlyBudget::where('financialyear','=',$data['allowance_financialyear'])
                //     // ->where('quarterly','=',$data['allowance_quarter'])
                //     // ->get();
                //     // if(!empty($query1))
                //     // {
                //     //     foreach($query1 as $row1);

                //     //     $update_query1 = QuarterlyBudget::where('financialyear','=',$data['allowance_financialyear'])
                //     //     ->where('quarterly','=',$data['allowance_quarter'])
                //     //     ->update(
                //     //         [
                //     //             'quarterly_balance_amount'  =>  $row1->quarterly_balance_amount - $data['allowance_amount'],
                //     //         ]
                //     //     );
                //     // }

                //     // $query1 = OverAllBankBalance::where('bankfinancialyear','=',$data['allowance_financialyear'])
                //     // ->where('bank_quarterly','=',$data['allowance_quarter'])
                //     // ->get();
                //     // if(!empty($query1))
                //     // {
                //     //     foreach($query1 as $row1);

                //     //     $update_query1 = OverAllBankBalance::where('bankfinancialyear','=',$data['allowance_financialyear'])
                //     //     ->where('bank_quarterly','=',$data['allowance_quarter'])
                //     //     ->update(
                //     //         [
                //     //             'bank_quarterly_balance_amount'  =>  $row1->bank_quarterly_balance_amount - $data['allowance_amount'],
                //     //         ]
                //     //     );
                //     // }

                //     // $query1 = HeadCategoriesBudget::where('headcategoryid','=',$data['headcategoryid'])
                //     // ->where('head_category_financial_year','=',$data['allowance_financialyear'])
                //     // ->where('head_category_quarter','=',$data['allowance_quarter'])
                //     // ->get();
                //     // if(!empty($query1))
                //     // {
                //     //     foreach($query1 as $row1);

                //     //     $update_query1 = HeadCategoriesBudget::where('headcategoryid','=',$data['headcategoryid'])
                //     //     ->where('head_category_financial_year','=',$data['allowance_financialyear'])
                //     //     ->where('head_category_quarter','=',$data['allowance_quarter'])
                //     //     ->update(
                //     //         [
                //     //             'head_category_budget_balance'  =>  $row1->head_category_budget_balance - $data['allowance_amount'],
                //     //         ]
                //     //     );
                //     // }

                //     // $query1 = HeadsBudget::where('headid','=',$data['headid'])
                //     // ->where('head_financial_year','=',$data['allowance_financialyear'])
                //     // ->where('head_quarter','=',$data['allowance_quarter'])
                //     // ->get();
                //     // if(!empty($query1))
                //     // {
                //     //     foreach($query1 as $row1);

                //     //     $update_query1 = HeadsBudget::where('headid','=',$data['headid'])
                //     //     ->where('head_financial_year','=',$data['allowance_financialyear'])
                //     //     ->where('head_quarter','=',$data['allowance_quarter'])
                //     //     ->update(
                //     //         [
                //     //             'head_budget_balance'  =>  $row1->head_budget_balance - $data['allowance_amount'],
                //     //         ]
                //     //     );
                //     // }

                //     // $query1 = MainHeadBudget::where('mainheadid','=',$data['mainheadid'])
                //     // ->where('main_head_financial_year','=',$data['allowance_financialyear'])
                //     // ->where('main_head_quarter','=',$data['allowance_quarter'])
                //     // ->get();
                //     // if(!empty($query1))
                //     // {
                //     //     foreach($query1 as $row1);

                //     //     $update_query1 = MainHeadBudget::where('mainheadid','=',$data['mainheadid'])
                //     //     ->where('main_head_financial_year','=',$data['allowance_financialyear'])
                //     //     ->where('main_head_quarter','=',$data['allowance_quarter'])
                //     //     ->update(
                //     //         [
                //     //             'main_head_budget_balance'  =>  $row1->main_head_budget_balance - $data['allowance_amount'],
                //     //         ]
                //     //     );
                //     // }

                //     // $query1 = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                //     // ->where('sub_head_financial_year','=',$data['allowance_financialyear'])
                //     // ->where('sub_head_quarter','=',$data['allowance_quarter'])
                //     // ->get();
                //     // if(!empty($query1))
                //     // {
                //     //     foreach($query1 as $row1);

                //     //     $update_query1 = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                //     //     ->where('sub_head_financial_year','=',$data['allowance_financialyear'])
                //     //     ->where('sub_head_quarter','=',$data['allowance_quarter'])
                //     //     ->update(
                //     //         [
                //     //             'sub_head_budget_balance'  =>  $row1->sub_head_budget_balance - $data['allowance_amount'],
                //     //         ]
                //     //     );
                //     // }

                //     // //  Manage Balance

                //     // $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$data['headcategoryid'])
                //     // ->where('head_category_financial_year','=',$data['allowance_financialyear'])
                //     // ->get();
                //     // if(!empty($query1))
                //     // {
                //     //     foreach($query1 as $row1);
    
                //     //     $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$data['headcategoryid'])
                //     //     ->where('head_category_financial_year','=',$data['allowance_financialyear'])
                //     //     ->update(
                //     //         [
                //     //             'head_category_balance_amount'  =>  $row1->head_category_balance_amount - $data['allowance_amount'],
                //     //         ]
                //     //     );
                //     // }
    
                //     // $query1 = HeadsBalanace::where('headid','=',$data['headid'])
                //     // ->where('head_financial_year','=',$data['allowance_financialyear'])
                //     // ->get();
                //     // if(!empty($query1))
                //     // {
                //     //     foreach($query1 as $row1);
    
                //     //     $update_query1 = HeadsBalanace::where('headid','=',$data['headid'])
                //     //     ->where('head_financial_year','=',$data['allowance_financialyear'])
                //     //     ->update(
                //     //         [
                //     //             'head_balance_amount'  =>  $row1->head_balance_amount - $data['allowance_amount'],
                //     //         ]
                //     //     );
                //     // }
    
                //     // $query1 = MainHeadsBalanace::where('mainheadid','=',$data['mainheadid'])
                //     // ->where('main_head_financial_year','=',$data['allowance_financialyear'])
                //     // ->get();
                //     // if(!empty($query1))
                //     // {
                //     //     foreach($query1 as $row1);
    
                //     //     $update_query1 = MainHeadsBalanace::where('mainheadid','=',$data['mainheadid'])
                //     //     ->where('main_head_financial_year','=',$data['allowance_financialyear'])
                //     //     ->update(
                //     //         [
                //     //             'main_head_balance_amount'  =>  $row1->main_head_balance_amount - $data['allowance_amount'],
                //     //         ]
                //     //     );
                //     // }
    
                //     // $query1 = SubHeadsBalanace::where('subheadid','=',$data['subheadid'])
                //     // ->where('sub_head_financial_year','=',$data['allowance_financialyear'])
                //     // ->get();
                //     // if(!empty($query1))
                //     // {
                //     //     foreach($query1 as $row1);
    
                //     //     $update_query1 = SubHeadsBalanace::where('subheadid','=',$data['subheadid'])
                //     //     ->where('sub_head_financial_year','=',$data['allowance_financialyear'])
                //     //     ->update(
                //     //         [
                //     //             'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount - $data['allowance_amount'],
                //     //         ]
                //     //     );
                //     // }

                //     // return redirect('/action_allowances/'.$data['headcategoryid'].'/'.$data['headid'].'/'.$data['mainheadid'].'/'.$data['subheadid'].'/'.$data['employeeid'])->with('success',"Insert Successfully");
                //     return redirect('/employee_allowances/'.$data['employeeid'])->with('success',"Insert Successfully");
                // }
                // else
                // {
                //     return back()->with('error',"Head Budget Error Occured");
                // }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function multiple_employee_allowance_db(Request $request)
    {
        foreach($request->sub_head as $key => $i)
        {
            $amount = $request->allowance_amount[$key];
            if(!empty($amount))
            {
                $amount = $amount;
            }
            else
            {
                $amount = 0;
            }
            // echo $i.' ----- '.$amount.'new line';exit;

            $data['allowance_financialyear'] = '';
            $data['allowance_quarter'] = '';
            $thisyearmonths = date('m',time());
            
            if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
            {
                $data['allowance_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
            }
            else
            {
                $data['allowance_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
            }
            
            if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
            {
                $data['allowance_quarter'] = '3rd Quarter';
            }
            if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
            {
                $data['allowance_quarter'] = '4th Quarter';
            }
            if($thisyearmonths >= 07 AND $thisyearmonths < 10)
            {
                $data['allowance_quarter'] = '1st Quarter';
            }
            if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
            {
                $data['allowance_quarter'] = '2nd Quarter';
            }

            if(!empty($i))
            {
                // echo '0';exit;
                $subheadids = SubHead::where('autosubheadid','=',$i)->get();
                foreach($subheadids as $subheadids);
    
                $headcategoryid = $subheadids->headcategoryid;
                $headid = $subheadids->headid;
                $mainheadid = $subheadids->mainheadid;
                $subheadid = $subheadids->autosubheadid;

                $query_check = EmployeeAllowance::where('headcategoryid','=',$headcategoryid)
                ->where('headid','=',$headid)
                ->where('mainheadid','=',$mainheadid)
                ->where('subheadid','=',$subheadid)
                ->where('employeeid','=',$request->employeeid)
                ->get();
                if(count($query_check) == 1)
                {
                    continue;
                }
                else
                {
                    $employee_allowance = new EmployeeAllowance;
                    $employee_allowance->financialyear = $data['allowance_financialyear'];
                    $employee_allowance->quarter = $data['allowance_quarter'];
                    $employee_allowance->employeeid = $request->employeeid;
                    $employee_allowance->headcategoryid = $headcategoryid;
                    $employee_allowance->headid = $headid;
                    $employee_allowance->mainheadid = $mainheadid;
                    $employee_allowance->subheadid = $subheadid;
                    $employee_allowance->allowance_amount = $amount;
                    $employee_allowance->added_by = Session::get('admin_id');
        
                    $employee_allowance->save();       
                }
            }
        }
        
        return redirect('/employee_allowances/'.$request->employeeid)->with('success',"Insert Successfully");
    }

    function update_employee_allowance($autoemployeeid,$autoemployeeallowanceid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array = EmployeeAllowance::join("employees","employee_allowances.employeeid",'=',"employees.autoemployeeid")
            ->where("employee_allowances.employeeid",'=',$autoemployeeid)
            ->orderby('employee_allowances.autoemployeeallowanceid','desc')->get();
            $array1 = array();
            $array1 = EmployeeAllowance::where("autoemployeeallowanceid",'=',$autoemployeeallowanceid)
            ->get();
            // dd($array);        
            return view('employees.action_allowances',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_employee_allowance_db(Request $request)
    {
        $rules = [
            'sub_head'  =>  'required|string',
            'allowance_amount'  =>  'required|string',
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

                $data['allowance_financialyear'] = '';
                $data['allowance_quarter'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['allowance_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['allowance_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                {
                    $data['allowance_quarter'] = '3rd Quarter';
                }
                if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                {
                    $data['allowance_quarter'] = '4th Quarter';
                }
                if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                {
                    $data['allowance_quarter'] = '1st Quarter';
                }
                if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                {
                    $data['allowance_quarter'] = '2nd Quarter';
                }

                $subheadids = SubHead::where('autosubheadid','=',$data['sub_head'])->get();
                foreach($subheadids as $subheadids);

                $data['headcategoryid'] = $subheadids->headcategoryid;
                $data['headid'] = $subheadids->headid;
                $data['mainheadid'] = $subheadids->mainheadid;
                $data['subheadid'] = $subheadids->autosubheadid;

                $employee_allowance = EmployeeAllowance::where("autoemployeeallowanceid",'=',$data['autoemployeeallowanceid'])->update(
                    [
                        'financialyear' => $data['allowance_financialyear'],
                        'quarter' => $data['allowance_quarter'],
                        'employeeid' => $data['employeeid'],
                        'headcategoryid' => $data['headcategoryid'],
                        'headid' => $data['headid'],
                        'mainheadid' => $data['mainheadid'],
                        'subheadid' => $data['subheadid'],
                        'allowance_amount' => $data['allowance_amount'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );
                return redirect('/employee_allowances/'.$data['employeeid'])->with('success',"Updated Successfully");

                // $query_check = SubHeadBudget::where('subheadid','=',$data['sub_head'])
                // ->where('sub_head_financial_year','=',$data['allowance_financialyear'])
                // ->where('sub_head_quarter','=',$data['expenditure_quarter'])
                // ->get();
                // if(count($query_check) > 0)
                // {
                //     // foreach($query_check as $query_check);
                //     // $checkamount = $data['old_allowance_amount'] + $data['allowance_amount'];
                //     // if($query_check->sub_head_budget_balance > $checkamount)
                //     // {
                //     //     // $query1 = OverAllBudget::where('financialyear','=',$data['allowance_financialyear'])
                //     //     // ->get();
                //     //     // if(!empty($query1))
                //     //     // {
                //     //     //     foreach($query1 as $row1);

                //     //     //     $update_query1 = OverAllBudget::where('financialyear','=',$data['allowance_financialyear'])
                //     //     //     ->update(
                //     //     //         [
                //     //     //             'balance_amount'  =>  $row1->balance_amount + $data['old_allowance_amount'] -$data['allowance_amount'],
                //     //     //         ]
                //     //     //     );
                //     //     // }

                //     //     // $query1 = QuarterlyBudget::where('financialyear','=',$data['allowance_financialyear'])
                //     //     // ->where('quarterly','=',$data['allowance_quarter'])
                //     //     // ->get();
                //     //     // if(!empty($query1))
                //     //     // {
                //     //     //     foreach($query1 as $row1);

                //     //     //     $update_query1 = QuarterlyBudget::where('financialyear','=',$data['allowance_financialyear'])
                //     //     //     ->where('quarterly','=',$data['allowance_quarter'])
                //     //     //     ->update(
                //     //     //         [
                //     //     //             'quarterly_balance_amount'  =>  $row1->quarterly_balance_amount + $data['old_allowance_amount'] -$data['allowance_amount'],
                //     //     //         ]
                //     //     //     );
                //     //     // }

                //     //     // $query1 = OverAllBankBalance::where('bankfinancialyear','=',$data['allowance_financialyear'])
                //     //     // ->where('bank_quarterly','=',$data['allowance_quarter'])
                //     //     // ->get();
                //     //     // if(!empty($query1))
                //     //     // {
                //     //     //     foreach($query1 as $row1);

                //     //     //     $update_query1 = OverAllBankBalance::where('bankfinancialyear','=',$data['allowance_financialyear'])
                //     //     //     ->where('bank_quarterly','=',$data['allowance_quarter'])
                //     //     //     ->update(
                //     //     //         [
                //     //     //             'bank_quarterly_balance_amount'  =>  $row1->bank_quarterly_balance_amount + $data['old_allowance_amount'] -$data['allowance_amount'],
                //     //     //         ]
                //     //     //     );
                //     //     // }

                //     //     // $query1 = HeadCategoriesBudget::where('headcategoryid','=',$data['headcategoryid'])
                //     //     // ->where('head_category_financial_year','=',$data['allowance_financialyear'])
                //     //     // ->where('head_category_quarter','=',$data['allowance_quarter'])
                //     //     // ->get();
                //     //     // if(!empty($query1))
                //     //     // {
                //     //     //     foreach($query1 as $row1);

                //     //     //     $update_query1 = HeadCategoriesBudget::where('headcategoryid','=',$data['headcategoryid'])
                //     //     //     ->where('head_category_financial_year','=',$data['allowance_financialyear'])
                //     //     //     ->where('head_category_quarter','=',$data['allowance_quarter'])
                //     //     //     ->update(
                //     //     //         [
                //     //     //             'head_category_budget_balance'  =>  $row1->head_category_budget_balance + $data['old_allowance_amount'] -$data['allowance_amount'],
                //     //     //         ]
                //     //     //     );

                //     //     // }

                //     //     // $query1 = HeadsBudget::where('headid','=',$data['headid'])
                //     //     // ->where('head_financial_year','=',$data['allowance_financialyear'])
                //     //     // ->where('head_quarter','=',$data['allowance_quarter'])
                //     //     // ->get();
                //     //     // if(!empty($query1))
                //     //     // {
                //     //     //     foreach($query1 as $row1);

                //     //     //     $update_query1 = HeadsBudget::where('headid','=',$data['headid'])
                //     //     //     ->where('head_financial_year','=',$data['allowance_financialyear'])
                //     //     //     ->where('head_quarter','=',$data['allowance_quarter'])
                //     //     //     ->update(
                //     //     //         [
                //     //     //             'head_budget_balance'  =>  $row1->head_budget_balance + $data['old_allowance_amount'] -$data['allowance_amount'],
                //     //     //         ]
                //     //     //     );

                //     //     // }

                //     //     // $query1 = MainHeadBudget::where('mainheadid','=',$data['mainheadid'])
                //     //     // ->where('main_head_financial_year','=',$data['allowance_financialyear'])
                //     //     // ->where('main_head_quarter','=',$data['allowance_quarter'])
                //     //     // ->get();
                //     //     // if(!empty($query1))
                //     //     // {
                //     //     //     foreach($query1 as $row1);

                //     //     //     $update_query1 = MainHeadBudget::where('mainheadid','=',$data['mainheadid'])
                //     //     //     ->where('main_head_financial_year','=',$data['allowance_financialyear'])
                //     //     //     ->where('main_head_quarter','=',$data['allowance_quarter'])
                //     //     //     ->update(
                //     //     //         [
                //     //     //             'main_head_budget_balance'  =>  $row1->main_head_budget_balance + $data['old_allowance_amount'] -$data['allowance_amount'],
                //     //     //         ]
                //     //     //     );

                //     //     // }

                //     //     // $query1 = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                //     //     // ->where('sub_head_financial_year','=',$data['allowance_financialyear'])
                //     //     // ->where('sub_head_quarter','=',$data['allowance_quarter'])
                //     //     // ->get();
                //     //     // if(!empty($query1))
                //     //     // {
                //     //     //     foreach($query1 as $row1);

                //     //     //     $update_query1 = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                //     //     //     ->where('sub_head_financial_year','=',$data['allowance_financialyear'])
                //     //     //     ->where('sub_head_quarter','=',$data['allowance_quarter'])
                //     //     //     ->update(
                //     //     //         [
                //     //     //             'sub_head_budget_balance'  =>  $row1->sub_head_budget_balance + $data['old_allowance_amount'] -$data['allowance_amount'],
                //     //     //         ]
                //     //     //     );

                //     //     // }

                //     //     // // Manage Balances

                //     //     // $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$data['headcategoryid'])
                //     //     // ->where('head_category_financial_year','=',$data['allowance_financialyear'])
                //     //     // ->get();
                //     //     // if(!empty($query1))
                //     //     // {
                //     //     //     foreach($query1 as $row1);
        
                //     //     //     $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$data['headcategoryid'])
                //     //     //     ->where('head_category_financial_year','=',$data['allowance_financialyear'])
                //     //     //     ->update(
                //     //     //         [
                //     //     //             'head_category_balance_amount'  =>  $row1->head_category_balance_amount + $data['old_allowance_amount'] -$data['allowance_amount'],
                //     //     //         ]
                //     //     //     );
        
                //     //     // }
        
                //     //     // $query1 = HeadsBalanace::where('headid','=',$data['headid'])
                //     //     // ->where('head_financial_year','=',$data['allowance_financialyear'])
                //     //     // ->get();
                //     //     // if(!empty($query1))
                //     //     // {
                //     //     //     foreach($query1 as $row1);
        
                //     //     //     $update_query1 = HeadsBalanace::where('headid','=',$data['headid'])
                //     //     //     ->where('head_financial_year','=',$data['allowance_financialyear'])
                //     //     //     ->update(
                //     //     //         [
                //     //     //             'head_balance_amount'  =>  $row1->head_balance_amount + $data['old_allowance_amount'] -$data['allowance_amount'],
                //     //     //         ]
                //     //     //     );
        
                //     //     // }
        
                //     //     // $query1 = MainHeadsBalanace::where('mainheadid','=',$data['mainheadid'])
                //     //     // ->where('main_head_financial_year','=',$data['allowance_financialyear'])
                //     //     // ->get();
                //     //     // if(!empty($query1))
                //     //     // {
                //     //     //     foreach($query1 as $row1);
        
                //     //     //     $update_query1 = MainHeadsBalanace::where('mainheadid','=',$data['mainheadid'])
                //     //     //     ->where('main_head_financial_year','=',$data['allowance_financialyear'])
                //     //     //     ->update(
                //     //     //         [
                //     //     //             'main_head_balance_amount'  =>  $row1->main_head_balance_amount + $data['old_allowance_amount'] -$data['allowance_amount'],
                //     //     //         ]
                //     //     //     );
        
                //     //     // }
        
                //     //     // $query1 = SubHeadsBalanace::where('subheadid','=',$data['subheadid'])
                //     //     // ->where('sub_head_financial_year','=',$data['allowance_financialyear'])
                //     //     // ->get();
                //     //     // if(!empty($query1))
                //     //     // {
                //     //     //     foreach($query1 as $row1);
        
                //     //     //     $update_query1 = SubHeadsBalanace::where('subheadid','=',$data['subheadid'])
                //     //     //     ->where('sub_head_financial_year','=',$data['allowance_financialyear'])
                //     //     //     ->update(
                //     //     //         [
                //     //     //             'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount + $data['old_allowance_amount'] -$data['allowance_amount'],
                //     //     //         ]
                //     //     //     );
        
                //     //     // }

                //     //     return redirect('/employee_allowances/'.$data['employeeid'])->with('success',"Updated Successfully");
                //     // }
                //     // else
                //     // {
                //     //     return back()->with('error',"Budget Amount exceed Limit");
                //     // }    
                // }
                // else
                // {
                //     return back()->with('error',"Head Budget Error Occured");
                // }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_employee_allowance($autoemployeeid,$autoemployeeallowanceid)
    {
        if(Session::get('admin_id'))
        {
            $employee_allowance_delete = EmployeeAllowance::where("autoemployeeallowanceid",'=',$autoemployeeallowanceid);
            $employee_allowance_delete->delete();

            return redirect('/employee_allowances/'.$autoemployeeid)->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    // function delete_employee_allowance_demo($autoemployeeid,$autoemployeeallowanceid)
    // {
    //     if(Session::get('admin_id'))
    //     {
    //         // $query = EmployeeAllowance::where('autoemployeeallowanceid','=',$autoemployeeallowanceid)
    //         // ->get();
    //         // if(!empty($query))
    //         // {
    //         //     foreach($query as $row);

    //         //     $query1 = OverAllBudget::where('financialyear','=',$row['financialyear'])
    //         //     ->get();
    //         //     if(!empty($query1))
    //         //     {
    //         //         foreach($query1 as $row1);

    //         //         $update_query1 = OverAllBudget::where('financialyear','=',$row['financialyear'])
    //         //         ->update(
    //         //             [
    //         //                 'balance_amount'  =>  $row1->balance_amount + $row['allowance_amount'],
    //         //             ]
    //         //         );
    //         //     }

    //         //     $query1 = QuarterlyBudget::where('financialyear','=',$row['financialyear'])
    //         //     ->where('quarterly','=',$row['quarter'])
    //         //     ->get();
    //         //     if(!empty($query1))
    //         //     {
    //         //         foreach($query1 as $row1);

    //         //         $update_query1 = QuarterlyBudget::where('financialyear','=',$row['financialyear'])
    //         //         ->where('quarterly','=',$row['quarter'])
    //         //         ->update(
    //         //             [
    //         //                 'quarterly_balance_amount'  =>  $row1->quarterly_balance_amount + $row['allowance_amount'],
    //         //             ]
    //         //         );
    //         //     }

    //         //     $query1 = OverAllBankBalance::where('bankfinancialyear','=',$row['financialyear'])
    //         //     ->where('bank_quarterly','=',$row['quarter'])
    //         //     ->get();
    //         //     if(!empty($query1))
    //         //     {
    //         //         foreach($query1 as $row1);

    //         //         $update_query1 = OverAllBankBalance::where('bankfinancialyear','=',$row['financialyear'])
    //         //         ->where('bank_quarterly','=',$row['quarter'])
    //         //         ->update(
    //         //             [
    //         //                 'bank_quarterly_balance_amount'  =>  $row1->bank_quarterly_balance_amount + $row['allowance_amount'],
    //         //             ]
    //         //         );
    //         //     }

    //         //     $query1 = HeadCategoriesBudget::where('headcategoryid','=',$row['headcategoryid'])
    //         //     ->where('head_category_financial_year','=',$row['financialyear'])
    //         //     ->where('head_category_quarter','=',$row['quarter'])
    //         //     ->get();
    //         //     if(!empty($query1))
    //         //     {
    //         //         foreach($query1 as $row1);

    //         //         $update_query1 = HeadCategoriesBudget::where('headcategoryid','=',$row['headcategoryid'])
    //         //         ->where('head_category_financial_year','=',$row['financialyear'])
    //         //         ->where('head_category_quarter','=',$row['quarter'])
    //         //         ->update(
    //         //             [
    //         //                 'head_category_budget_balance'  =>  $row1->head_category_budget_balance + $row['allowance_amount'],
    //         //             ]
    //         //         );
    //         //     }

    //         //     $query1 = HeadsBudget::where('headid','=',$row['headid'])
    //         //     ->where('head_financial_year','=',$row['financialyear'])
    //         //     ->where('head_quarter','=',$row['quarter'])
    //         //     ->get();
    //         //     if(!empty($query1))
    //         //     {
    //         //         foreach($query1 as $row1);

    //         //         $update_query1 = HeadsBudget::where('headid','=',$row['headid'])
    //         //         ->where('head_financial_year','=',$row['financialyear'])
    //         //         ->where('head_quarter','=',$row['quarter'])
    //         //         ->update(
    //         //             [
    //         //                 'head_budget_balance'  =>  $row1->head_budget_balance + $row['allowance_amount'],
    //         //             ]
    //         //         );
    //         //     }

    //         //     $query1 = MainHeadBudget::where('mainheadid','=',$row['mainheadid'])
    //         //     ->where('main_head_financial_year','=',$row['financialyear'])
    //         //     ->where('main_head_quarter','=',$row['quarter'])
    //         //     ->get();
    //         //     if(!empty($query1))
    //         //     {
    //         //         foreach($query1 as $row1);

    //         //         $update_query1 = MainHeadBudget::where('mainheadid','=',$row['mainheadid'])
    //         //         ->where('main_head_financial_year','=',$row['financialyear'])
    //         //         ->where('main_head_quarter','=',$row['quarter'])
    //         //         ->update(
    //         //             [
    //         //                 'main_head_budget_balance'  =>  $row1->main_head_budget_balance + $row['allowance_amount'],
    //         //             ]
    //         //         );
    //         //     }

    //         //     $query1 = SubHeadBudget::where('subheadid','=',$row['subheadid'])
    //         //     ->where('sub_head_financial_year','=',$row['financialyear'])
    //         //     ->where('sub_head_quarter','=',$row['quarter'])
    //         //     ->get();
    //         //     if(!empty($query1))
    //         //     {
    //         //         foreach($query1 as $row1);

    //         //         $update_query1 = SubHeadBudget::where('subheadid','=',$row['subheadid'])
    //         //         ->where('sub_head_financial_year','=',$row['financialyear'])
    //         //         ->where('sub_head_quarter','=',$row['quarter'])
    //         //         ->update(
    //         //             [
    //         //                 'sub_head_budget_balance'  =>  $row1->sub_head_budget_balance + $row['allowance_amount'],
    //         //             ]
    //         //         );
    //         //     }

    //         //     // Manage balance

    //         //     $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
    //         //     ->where('head_category_financial_year','=',$row['allowance_financialyear'])
    //         //     ->get();
    //         //     if(!empty($query1))
    //         //     {
    //         //         foreach($query1 as $row1);

    //         //         $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
    //         //         ->where('head_category_financial_year','=',$row['allowance_financialyear'])
    //         //         ->update(
    //         //             [
    //         //                 'head_category_balance_amount'  =>  $row1->head_category_balance_amount + $row['allowance_amount'],
    //         //             ]
    //         //         );
    //         //     }

    //         //     $query1 = HeadsBalanace::where('headid','=',$row['headid'])
    //         //     ->where('head_financial_year','=',$row['allowance_financialyear'])
    //         //     ->get();
    //         //     if(!empty($query1))
    //         //     {
    //         //         foreach($query1 as $row1);

    //         //         $update_query1 = HeadsBalanace::where('headid','=',$row['headid'])
    //         //         ->where('head_financial_year','=',$row['allowance_financialyear'])
    //         //         ->update(
    //         //             [
    //         //                 'head_balance_amount'  =>  $row1->head_balance_amount + $row['allowance_amount'],
    //         //             ]
    //         //         );
    //         //     }

    //         //     $query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
    //         //     ->where('main_head_financial_year','=',$row['allowance_financialyear'])
    //         //     ->get();
    //         //     if(!empty($query1))
    //         //     {
    //         //         foreach($query1 as $row1);

    //         //         $update_query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
    //         //         ->where('main_head_financial_year','=',$row['allowance_financialyear'])
    //         //         ->update(
    //         //             [
    //         //                 'main_head_balance_amount'  =>  $row1->main_head_balance_amount + $row['allowance_amount'],
    //         //             ]
    //         //         );
    //         //     }

    //         //     $query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
    //         //     ->where('sub_head_financial_year','=',$row['allowance_financialyear'])
    //         //     ->get();
    //         //     if(!empty($query1))
    //         //     {
    //         //         foreach($query1 as $row1);

    //         //         $update_query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
    //         //         ->where('sub_head_financial_year','=',$row['allowance_financialyear'])
    //         //         ->update(
    //         //             [
    //         //                 'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount + $row['allowance_amount'],
    //         //             ]
    //         //         );
    //         //     }
    //         // }

    //         $employee_allowance_delete = EmployeeAllowance::where("autoemployeeallowanceid",'=',$autoemployeeallowanceid);
    //         $employee_allowance_delete->delete();

    //         return redirect('/employee_allowances/'.$autoemployeeid)->with('success',"Deleted Successfully");
    //     }
    //     else
    //     {
    //         return redirect('/')->with('error',"Please Login First");
    //     }
    // }

    function delete_multiple_employee_allowances(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
					EmployeeAllowance::where('autoemployeeallowanceid', $deleteid)->delete();
				}
                return back()->with('success',"Deleted successfully");	
				// return redirect('/employee_allowances/'.$row['employeeid'])->with('success',"Deleted successfully");	
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

    // function delete_multiple_employee_allowances_demo(request $request)
    // {
	// 	try{
	// 		$id = $request->id;
	// 		if(!empty($id))
	// 		{
	// 			foreach ($id as $deleteid) 
	// 			{
    //                 // $query = EmployeeAllowance::where('autoemployeeallowanceid','=',$deleteid)
    //                 // ->get();
    //                 // if(!empty($query))
    //                 // {
    //                 //     foreach($query as $row);

    //                 //     $query1 = OverAllBudget::where('financialyear','=',$row['financialyear'])
    //                 //     ->get();
    //                 //     if(!empty($query1))
    //                 //     {
    //                 //         foreach($query1 as $row1);

    //                 //         $update_query1 = OverAllBudget::where('financialyear','=',$row['financialyear'])
    //                 //         ->update(
    //                 //             [
    //                 //                 'balance_amount'  =>  $row1->balance_amount + $row['allowance_amount'],
    //                 //             ]
    //                 //         );
    //                 //     }

    //                 //     $query1 = QuarterlyBudget::where('financialyear','=',$row['financialyear'])
    //                 //     ->where('quarterly','=',$row['quarter'])
    //                 //     ->get();
    //                 //     if(!empty($query1))
    //                 //     {
    //                 //         foreach($query1 as $row1);

    //                 //         $update_query1 = QuarterlyBudget::where('financialyear','=',$row['financialyear'])
    //                 //         ->where('quarterly','=',$row['quarter'])
    //                 //         ->update(
    //                 //             [
    //                 //                 'quarterly_balance_amount'  =>  $row1->quarterly_balance_amount + $row['allowance_amount'],
    //                 //             ]
    //                 //         );
    //                 //     }

    //                 //     $query1 = OverAllBankBalance::where('bankfinancialyear','=',$row['financialyear'])
    //                 //     ->where('bank_quarterly','=',$row['quarter'])
    //                 //     ->get();
    //                 //     if(!empty($query1))
    //                 //     {
    //                 //         foreach($query1 as $row1);

    //                 //         $update_query1 = OverAllBankBalance::where('bankfinancialyear','=',$row['financialyear'])
    //                 //         ->where('bank_quarterly','=',$row['quarter'])
    //                 //         ->update(
    //                 //             [
    //                 //                 'bank_quarterly_balance_amount'  =>  $row1->bank_quarterly_balance_amount + $row['allowance_amount'],
    //                 //             ]
    //                 //         );
    //                 //     }

    //                 //     $query1 = HeadCategoriesBudget::where('headcategoryid','=',$row['headcategoryid'])
    //                 //     ->where('head_category_financial_year','=',$row['financialyear'])
    //                 //     ->where('head_category_quarter','=',$row['quarter'])
    //                 //     ->get();
    //                 //     if(!empty($query1))
    //                 //     {
    //                 //         foreach($query1 as $row1);

    //                 //         $update_query1 = HeadCategoriesBudget::where('headcategoryid','=',$row['headcategoryid'])
    //                 //         ->where('head_category_financial_year','=',$row['financialyear'])
    //                 //         ->where('head_category_quarter','=',$row['quarter'])
    //                 //         ->update(
    //                 //             [
    //                 //                 'head_category_budget_balance'  =>  $row1->head_category_budget_balance + $row['allowance_amount'],
    //                 //             ]
    //                 //         );
    //                 //     }

    //                 //     $query1 = HeadsBudget::where('headid','=',$row['headid'])
    //                 //     ->where('head_financial_year','=',$row['financialyear'])
    //                 //     ->where('head_quarter','=',$row['quarter'])
    //                 //     ->get();
    //                 //     if(!empty($query1))
    //                 //     {
    //                 //         foreach($query1 as $row1);

    //                 //         $update_query1 = HeadsBudget::where('headid','=',$row['headid'])
    //                 //         ->where('head_financial_year','=',$row['financialyear'])
    //                 //         ->where('head_quarter','=',$row['quarter'])
    //                 //         ->update(
    //                 //             [
    //                 //                 'head_budget_balance'  =>  $row1->head_budget_balance + $row['allowance_amount'],
    //                 //             ]
    //                 //         );
    //                 //     }

    //                 //     $query1 = MainHeadBudget::where('mainheadid','=',$row['mainheadid'])
    //                 //     ->where('main_head_financial_year','=',$row['financialyear'])
    //                 //     ->where('main_head_quarter','=',$row['quarter'])
    //                 //     ->get();
    //                 //     if(!empty($query1))
    //                 //     {
    //                 //         foreach($query1 as $row1);

    //                 //         $update_query1 = MainHeadBudget::where('mainheadid','=',$row['mainheadid'])
    //                 //         ->where('main_head_financial_year','=',$row['financialyear'])
    //                 //         ->where('main_head_quarter','=',$row['quarter'])
    //                 //         ->update(
    //                 //             [
    //                 //                 'main_head_budget_balance'  =>  $row1->main_head_budget_balance + $row['allowance_amount'],
    //                 //             ]
    //                 //         );
    //                 //     }

    //                 //     $query1 = SubHeadBudget::where('subheadid','=',$row['subheadid'])
    //                 //     ->where('sub_head_financial_year','=',$row['financialyear'])
    //                 //     ->where('sub_head_quarter','=',$row['quarter'])
    //                 //     ->get();
    //                 //     if(!empty($query1))
    //                 //     {
    //                 //         foreach($query1 as $row1);

    //                 //         $update_query1 = SubHeadBudget::where('subheadid','=',$row['subheadid'])
    //                 //         ->where('sub_head_financial_year','=',$row['financialyear'])
    //                 //         ->where('sub_head_quarter','=',$row['quarter'])
    //                 //         ->update(
    //                 //             [
    //                 //                 'sub_head_budget_balance'  =>  $row1->sub_head_budget_balance + $row['allowance_amount'],
    //                 //             ]
    //                 //         );
    //                 //     }

    //                 //     // Manage balance

    //                 //     $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
    //                 //     ->where('head_category_financial_year','=',$row['allowance_financialyear'])
    //                 //     ->get();
    //                 //     if(!empty($query1))
    //                 //     {
    //                 //         foreach($query1 as $row1);

    //                 //         $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
    //                 //         ->where('head_category_financial_year','=',$row['allowance_financialyear'])
    //                 //         ->update(
    //                 //             [
    //                 //                 'head_category_balance_amount'  =>  $row1->head_category_balance_amount + $row['allowance_amount'],
    //                 //             ]
    //                 //         );
    //                 //     }

    //                 //     $query1 = HeadsBalanace::where('headid','=',$row['headid'])
    //                 //     ->where('head_financial_year','=',$row['allowance_financialyear'])
    //                 //     ->get();
    //                 //     if(!empty($query1))
    //                 //     {
    //                 //         foreach($query1 as $row1);

    //                 //         $update_query1 = HeadsBalanace::where('headid','=',$row['headid'])
    //                 //         ->where('head_financial_year','=',$row['allowance_financialyear'])
    //                 //         ->update(
    //                 //             [
    //                 //                 'head_balance_amount'  =>  $row1->head_balance_amount + $row['allowance_amount'],
    //                 //             ]
    //                 //         );
    //                 //     }

    //                 //     $query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
    //                 //     ->where('main_head_financial_year','=',$row['allowance_financialyear'])
    //                 //     ->get();
    //                 //     if(!empty($query1))
    //                 //     {
    //                 //         foreach($query1 as $row1);

    //                 //         $update_query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
    //                 //         ->where('main_head_financial_year','=',$row['allowance_financialyear'])
    //                 //         ->update(
    //                 //             [
    //                 //                 'main_head_balance_amount'  =>  $row1->main_head_balance_amount + $row['allowance_amount'],
    //                 //             ]
    //                 //         );
    //                 //     }

    //                 //     $query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
    //                 //     ->where('sub_head_financial_year','=',$row['allowance_financialyear'])
    //                 //     ->get();
    //                 //     if(!empty($query1))
    //                 //     {
    //                 //         foreach($query1 as $row1);

    //                 //         $update_query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
    //                 //         ->where('sub_head_financial_year','=',$row['allowance_financialyear'])
    //                 //         ->update(
    //                 //             [
    //                 //                 'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount + $row['allowance_amount'],
    //                 //             ]
    //                 //         );
    //                 //     }
    //                 // }

	// 				EmployeeAllowance::where('autoemployeeallowanceid', $deleteid)->delete();
	// 			}
	// 			return redirect('/employee_allowances/'.$row['employeeid'])->with('success',"Deleted successfully");	
	// 		}
	// 		else
	// 		{
	// 			return back()->with('error',"Select Some Rows First!");				
	// 		}
	// 	}
	// 	catch(Exception $e){
	// 		return back()->with('error',"Error Occured");
	// 	}
    // }
}
