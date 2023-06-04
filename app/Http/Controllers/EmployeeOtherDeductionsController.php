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
use App\Models\EmployeeOtherDeductions;

class EmployeeOtherDeductionsController extends Controller
{
    function employee_other_deductions($employee)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeOtherDeductions::orderby('autoemployeeotherdeductionid','asc')->where('employeeid','=',$employee)->get();
            return view('employees.employee_other_deductions',compact('array','array1','employee'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function employee_other_deduction_db(Request $request)
    {
        $rules = [
            'description'  =>  'required|string',
            'otherdeduction_amount'  =>  'required|string',
            'otherdeduction_installment'  =>  'required|string',
            'otherdeduction_balance'  =>  'required|string',
            'otherdeduction_date'  =>  'required|string',
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
                
                $employee_other_deduction = new EmployeeOtherDeductions;
                $employee_other_deduction->employeeid = $data['employee'];
                $employee_other_deduction->description = $data['description'];
                $employee_other_deduction->otherdeduction_amount = $data['otherdeduction_amount'];
                $employee_other_deduction->otherdeduction_installment = $data['otherdeduction_installment'];
                $employee_other_deduction->otherdeduction_balance = $data['otherdeduction_balance'];
                $employee_other_deduction->otherdeduction_date = $data['otherdeduction_date'];
                $employee_other_deduction->added_by = Session::get('admin_id');

                $employee_other_deduction->save();

                return redirect('/employee_other_deductions/'.$data['employee'])->with('success',"Insert Successfully"); 
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_employee_other_deduction($autoemployeeotherdeductionid)
    {
        if(Session::get('admin_id'))
        {
            $employee = '';
            $array = array();
            $array1 = array();
            $array = EmployeeOtherDeductions::orderby('autoemployeeotherdeductionid','asc')->where('employeeid','=',$employee)->get();
            $array1 = EmployeeOtherDeductions::where("autoemployeeotherdeductionid",'=',$autoemployeeotherdeductionid)
            ->get();
            // dd($array1);        
            return view('employees.employee_other_deductions',compact('array','array1','employee'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_employee_other_deduction_db(Request $request)
    {
        $rules = [
            'description'  =>  'required|string',
            'otherdeduction_amount'  =>  'required|string',
            'otherdeduction_installment'  =>  'required|string',
            'otherdeduction_balance'  =>  'required|string',
            'otherdeduction_date'  =>  'required|string',
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
                $employee_other_deduction = EmployeeOtherDeductions::where("autoemployeeotherdeductionid",'=',$data['autoemployeeotherdeductionid'])->update(
                    [
                        'employeeid' => $data['employee'],
                        'description' => $data['description'],
                        'otherdeduction_amount' => $data['otherdeduction_amount'],
                        'otherdeduction_installment' => $data['otherdeduction_installment'],
                        'otherdeduction_balance' => $data['otherdeduction_balance'],
                        'otherdeduction_date' => $data['otherdeduction_date'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

                return redirect('/employee_other_deductions/'.$data['employee'])->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_employee_other_deduction($autoemployeeotherdeductionid)
    {
        if(Session::get('admin_id'))
        {
            $employee_other_deduction_delete = EmployeeOtherDeductions::where("autoemployeeotherdeductionid",'=',$autoemployeeotherdeductionid);
            $employee_other_deduction_delete->delete();

            return back()->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_employee_other_deductions(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
                    EmployeeOtherDeductions::where('autoemployeeotherdeductionid', $deleteid)->delete();
				}
				return back()->with('success',"Deleted successfully");	
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
