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
use App\Models\EmployeeDesignation;

class EmployeeDesignationController extends Controller
{
    function employee_designations()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeDesignation::orderby('employee_designations.autoemployeedesignationid','asc')->get();
            return view('employees.employee_designations',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function employee_designation_db(Request $request)
    {
        $rules = [
            'bps_name'  =>  'required|string',
            'designation_name'  =>  'required|string',
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
				$employee_designation = new EmployeeDesignation;
                $employee_designation->bps_name = $data['bps_name'];
                $employee_designation->designation_name = $data['designation_name'];
                $employee_designation->added_by = Session::get('admin_id');

				$employee_designation->save();

				return redirect('/employee_designations')->with('success',"Insert Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_employee_designation($autoemployeedesignationid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeDesignation::orderby('employee_designations.autoemployeedesignationid','asc')->get();
            $array1 = EmployeeDesignation::where("autoemployeedesignationid",'=',$autoemployeedesignationid)
            ->get();
            // dd($array);        
            return view('employees.employee_designations',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_employee_designation_db(Request $request)
    {
        $rules = [
            'bps_name'  =>  'required|string',
            'designation_name'  =>  'required|string',
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
				$employee_designation = EmployeeDesignation::where("autoemployeedesignationid",'=',$data['autoemployeedesignationid'])->update(
                    [
                        'bps_name' => $data['bps_name'],
                        'designation_name' => $data['designation_name'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

				return redirect('/employee_designations')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_employee_designation($autoemployeedesignationid)
    {
        if(Session::get('admin_id'))
        {
            $employee_designation_delete = EmployeeDesignation::where("autoemployeedesignationid",'=',$autoemployeedesignationid);
            $employee_designation_delete->delete();

            return redirect('/employee_designations')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_employee_designations(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
					EmployeeDesignation::where('autoemployeedesignationid', $deleteid)->delete();
				}
				return redirect('/employee_designations')->with('success',"Deleted successfully");	
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
