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
use App\Models\EmployeeDeductionHeads;

class EmployeeDeductionHeadsController extends Controller
{
    function employee_deduction_heads()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeDeductionHeads::orderby('employee_deduction_heads.autoemployeedeductionheadid','asc')->get();
            return view('heads.employee_deduction_heads',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function employee_deduction_head_db(Request $request)
    {
        $rules = [
            'deduction_head_code'  =>  'required|string',
            'deduction_head_name'  =>  'required|string',
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
                $data['deductionhead_financialyear'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['deductionhead_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['deductionhead_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
				$deduction_head = new EmployeeDeductionHeads;
                $deduction_head->deductionhead_financialyear = $data['deductionhead_financialyear'];
                $deduction_head->deduction_head_code = $data['deduction_head_code'];
                $deduction_head->deduction_head_name = $data['deduction_head_name'];
                $deduction_head->added_by = Session::get('admin_id');

				$deduction_head->save();

				return redirect('/employee_deduction_heads')->with('success',"Insert Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_employee_deduction_head($autoemployeedeductionheadid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeDeductionHeads::orderby('employee_deduction_heads.autoemployeedeductionheadid','asc')->get();
            $array1 = EmployeeDeductionHeads::where("autoemployeedeductionheadid",'=',$autoemployeedeductionheadid)
            ->get();
            // dd($array);        
            return view('heads.employee_deduction_heads',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_employee_deduction_head_db(Request $request)
    {
        $rules = [
            'deduction_head_code'  =>  'required|string',
            'deduction_head_name'  =>  'required|string',
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
                $data['deductionhead_financialyear'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['deductionhead_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['deductionhead_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
				$deduction_head = EmployeeDeductionHeads::where("autoemployeedeductionheadid",'=',$data['autoemployeedeductionheadid'])->update(
                    [
                        'deductionhead_financialyear' => $data['deductionhead_financialyear'],
                        'deduction_head_code' => $data['deduction_head_code'],
                        'deduction_head_name' => $data['deduction_head_name'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

				return redirect('/employee_deduction_heads')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_employee_deduction_head($autoemployeedeductionheadid)
    {
        if(Session::get('admin_id'))
        {
            $deduction_head_delete = EmployeeDeductionHeads::where("autoemployeedeductionheadid",'=',$autoemployeedeductionheadid);
            $deduction_head_delete->delete();

            return redirect('/employee_deduction_heads')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_employee_deduction_heads(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
					EmployeeDeductionHeads::where('autoemployeedeductionheadid', $deleteid)->delete();
				}
				return redirect('/employee_deduction_heads')->with('success',"Deleted successfully");	
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
