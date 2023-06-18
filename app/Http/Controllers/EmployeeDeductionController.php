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
use App\Models\EmployeeDeduction;
use App\Models\OverAllBudget;
use App\Models\QuarterlyBudget;
use App\Models\HeadCategoriesBudget;
use App\Models\HeadsBudget;
use App\Models\MainHeadBudget;
use App\Models\SubHeadBudget;
use App\Models\Head;
use App\Models\MainHead;
use App\Models\SubHead;

class EmployeeDeductionController extends Controller
{
    function list_employee_deductions()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array = EmployeeDeduction::join("employees","employee_deductions.employeeid",'=',"employees.autoemployeeid")
            ->join("employee_deduction_heads","employee_deductions.deductionheadid",'=',"employee_deduction_heads.autoemployeedeductionheadid")
            ->orderby('employee_deductions.autoemployeedeductionid','desc')->get();
            return view('employees.list_employee_deductions',compact('array'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function employee_deductions($autoemployeeid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array = EmployeeDeduction::join("employees","employee_deductions.employeeid",'=',"employees.autoemployeeid")
            ->join("employee_deduction_heads","employee_deductions.deductionheadid",'=',"employee_deduction_heads.autoemployeedeductionheadid")
            ->where("employee_deductions.employeeid",'=',$autoemployeeid)
            ->orderby('employee_deductions.autoemployeedeductionid','desc')->get();
            return view('employees.employee_deductions',compact('array','autoemployeeid'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function action_deductions($employeeid)
    {
        if(Session::get('admin_id'))
        {
            return view('employees.action_deductions',compact('employeeid'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function employee_deduction_db(Request $request)
    {
        $rules = [
            'deduction_head'  =>  'required|string',
            'deduction_amount'  =>  'required|string',
            'deduction_date'  =>  'required|string',
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
                $data['deduction_financialyear'] = '';
                $data['deduction_quarter'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['deduction_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['deduction_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                {
                    $data['deduction_quarter'] = '3rd Quarter';
                }
                if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                {
                    $data['deduction_quarter'] = '4th Quarter';
                }
                if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                {
                    $data['deduction_quarter'] = '1st Quarter';
                }
                if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                {
                    $data['deduction_quarter'] = '2nd Quarter';
                }
                
				$employee_deduction = new EmployeeDeduction;
                $employee_deduction->financialyear = $data['deduction_financialyear'];
                $employee_deduction->quarter = $data['deduction_quarter'];
                $employee_deduction->employeeid = $data['employeeid'];
                $employee_deduction->deductionheadid = $data['deduction_head'];
                $employee_deduction->description = $data['description'];
                $employee_deduction->deduction_amount = $data['deduction_amount'];
                $employee_deduction->deduction_date = $data['deduction_date'];
                $employee_deduction->added_by = Session::get('admin_id');

				$employee_deduction->save();

                // return redirect('/action_deductions/'.$data['headcategoryid'].'/'.$data['headid'].'/'.$data['mainheadid'].'/'.$data['subheadid'].'/'.$data['employeeid'])->with('success',"Insert Successfully");
				return redirect('/employee_deductions/'.$data['employeeid'])->with('success',"Insert Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_employee_deduction($autoemployeeid,$autoemployeedeductionid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array = EmployeeDeduction::join("employees","employee_deductions.employeeid",'=',"employees.autoemployeeid")
            ->where("employee_deductions.employeeid",'=',$autoemployeeid)
            ->orderby('employee_deductions.autoemployeedeductionid','desc')->get();
            $array1 = array();
            $array1 = EmployeeDeduction::where("autoemployeedeductionid",'=',$autoemployeedeductionid)
            ->get();
            // dd($array);        
            return view('employees.action_deductions',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_employee_deduction_db(Request $request)
    {
        $rules = [
            'deduction_head'  =>  'required|string',
            'deduction_amount'  =>  'required|string',
            'deduction_date'  =>  'required|string',
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

                $data['deduction_financialyear'] = '';
                $data['deduction_quarter'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['deduction_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['deduction_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                {
                    $data['deduction_quarter'] = '3rd Quarter';
                }
                if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                {
                    $data['deduction_quarter'] = '4th Quarter';
                }
                if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                {
                    $data['deduction_quarter'] = '1st Quarter';
                }
                if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                {
                    $data['deduction_quarter'] = '2nd Quarter';
                }

				$employee_deduction = EmployeeDeduction::where("autoemployeedeductionid",'=',$data['autoemployeedeductionid'])->update(
                    [
                        'financialyear' => $data['deduction_financialyear'],
                        'quarter' => $data['deduction_quarter'],
                        'employeeid' => $data['employeeid'],
                        'deductionheadid' => $data['deduction_head'],
                        'description' => $data['description'],
                        'deduction_amount' => $data['deduction_amount'],
                        'deduction_date' => $data['deduction_date'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

				return redirect('/employee_deductions/'.$data['employeeid'])->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_employee_deduction($autoemployeeid,$autoemployeedeductionid)
    {
        if(Session::get('admin_id'))
        {
            $employee_deduction_delete = EmployeeDeduction::where("autoemployeedeductionid",'=',$autoemployeedeductionid);
            $employee_deduction_delete->delete();

            return redirect('/employee_deductions/'.$autoemployeeid)->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_employee_deductions(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
					EmployeeDeduction::where('autoemployeedeductionid', $deleteid)->delete();
				}
				return redirect('/employee_deductions/'.$row['employeeid'])->with('success',"Deleted successfully");	
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
}
