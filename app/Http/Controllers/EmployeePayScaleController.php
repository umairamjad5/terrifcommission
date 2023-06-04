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
use App\Models\EmployeePayScale;

class EmployeePayScaleController extends Controller
{
    
    function employee_pay_scales()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeePayScale::orderby('employee_pay_scales.autoemployeepayscaleid','asc')->get();
            return view('employees.employee_pay_scales',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function employee_pay_scale_db(Request $request)
    {
        $rules = [
            'bps_scale'  =>  'required|string',
            'minimum_pay'  =>  'required|string',
            'increment_amount'  =>  'required|string',
            'maximum_pay'  =>  'required|string',
            'scale_stages'  =>  'required|string',
            'gp_fund_advance'  =>  'required|string',
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
				$employee_pay_scale = new EmployeePayScale;
                $employee_pay_scale->payscale_year = '2022';
                $employee_pay_scale->bps_scale = $data['bps_scale'];
                $employee_pay_scale->minimum_pay = $data['minimum_pay'];
                $employee_pay_scale->increment_amount = $data['increment_amount'];
                $employee_pay_scale->maximum_pay = $data['maximum_pay'];
                $employee_pay_scale->scale_stages = $data['scale_stages'];
                $employee_pay_scale->gp_fund_advance = $data['gp_fund_advance'];
                $employee_pay_scale->added_by = Session::get('admin_id');

				$employee_pay_scale->save();

				return redirect('/employee_pay_scales')->with('success',"Insert Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_employee_pay_scale($autoemployeepayscaleid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeePayScale::orderby('employee_pay_scales.autoemployeepayscaleid','asc')->get();
            $array1 = EmployeePayScale::where("autoemployeepayscaleid",'=',$autoemployeepayscaleid)
            ->get();
            // dd($array);        
            return view('employees.employee_pay_scales',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_employee_pay_scale_db(Request $request)
    {
        $rules = [
            'bps_scale'  =>  'required|string',
            'minimum_pay'  =>  'required|string',
            'increment_amount'  =>  'required|string',
            'maximum_pay'  =>  'required|string',
            'scale_stages'  =>  'required|string',
            'gp_fund_advance'  =>  'required|string',
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
				$employee_pay_scale = EmployeePayScale::where("autoemployeepayscaleid",'=',$data['autoemployeepayscaleid'])->update(
                    [
                        'bps_scale' => $data['bps_scale'],
                        'minimum_pay' => $data['minimum_pay'],
                        'increment_amount' => $data['increment_amount'],
                        'maximum_pay' => $data['maximum_pay'],
                        'scale_stages' => $data['scale_stages'],
                        'gp_fund_advance' => $data['gp_fund_advance'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

				return redirect('/employee_pay_scales')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_employee_pay_scale($autoemployeepayscaleid)
    {
        if(Session::get('admin_id'))
        {
            $employee_pay_scale_delete = EmployeePayScale::where("autoemployeepayscaleid",'=',$autoemployeepayscaleid);
            $employee_pay_scale_delete->delete();

            return redirect('/employee_pay_scales')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_employee_pay_scales(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
					EmployeePayScale::where('autoemployeepayscaleid', $deleteid)->delete();
				}
				return redirect('/employee_pay_scales')->with('success',"Deleted successfully");	
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
