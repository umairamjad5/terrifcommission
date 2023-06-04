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
use App\Models\EmployeeHBLoan;
use App\Models\EmployeeHBDeduction;
use App\Models\EmployeeHBLoanBalance;

class EmployeeHBLoanController extends Controller
{
    function hb_loans()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeHBLoan::orderby('autoemployeehbloanid','asc')->get();
            return view('loans.hb_loans',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function hb_loan_db(Request $request)
    {
        $rules = [
            'employee'  =>  'required|string',
            'hb_loan_date'  =>  'required|string',
            'hb_loan_amount'  =>  'required|string|max:10',
            'hb_approved_amount'  =>  'required|string|max:10',
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
                
                $hb_loan = new EmployeeHBLoan;
                $hb_loan->employeeid = $data['employee'];
                $hb_loan->hb_loan_date = $data['hb_loan_date'];
                $hb_loan->hb_loan_amount = $data['hb_loan_amount'];
                $hb_loan->hb_approved_amount = $data['hb_approved_amount'];
                $hb_loan->added_by = Session::get('admin_id');

                $hb_loan->save();

                $query = EmployeeHBLoanBalance::where('employeeid','=',$data['employee'])->get();
                if(count($query) == 1)
                {
                    foreach($query as $query);
                    $hb_loan = EmployeeHBLoanBalance::where("employeeid",'=',$data['employee'])->update(
                        [
                            'hb_balance_amount' => $data['hb_approved_amount'] + $query->hb_balance_amount,
                            'added_by' => Session::get('admin_id'),
                        ]
                    );
                }
                else
                {
                    $hb_loan = new EmployeeHBLoanBalance;
                    $hb_loan->employeeid = $data['employee'];
                    $hb_loan->hb_balance_amount = $data['hb_approved_amount'];
                    $hb_loan->added_by = Session::get('admin_id');

                    $hb_loan->save();
                }

                return redirect('/hb_loans')->with('success',"Insert Successfully"); 
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_hb_loan($autoemployeehbloanid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeHBLoan::orderby('autoemployeehbloanid','asc')->get();
            $array1 = EmployeeHBLoan::where("autoemployeehbloanid",'=',$autoemployeehbloanid)
            ->get();
            // dd($array);        
            return view('loans.hb_loans',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_hb_loan_db(Request $request)
    {
        $rules = [
            'employee'  =>  'required|string',
            'hb_loan_date'  =>  'required|string',
            'hb_loan_amount'  =>  'required|string|max:10',
            'hb_approved_amount'  =>  'required|string|max:10',
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
                $hb_loan = EmployeeHBLoan::where("autoemployeehbloanid",'=',$data['autoemployeehbloanid'])->update(
                    [
                        'employeeid' => $data['employee'],
                        'hb_loan_date' => $data['hb_loan_date'],
                        'hb_loan_amount' => $data['hb_loan_amount'],
                        'hb_approved_amount' => $data['hb_approved_amount'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

                $query = EmployeeHBLoanBalance::where('employeeid','=',$data['employee'])->get();
                if(count($query) == 1)
                {
                    foreach($query as $query);
                    $hb_loan = EmployeeHBLoanBalance::where("employeeid",'=',$data['employee'])->update(
                        [
                            'hb_balance_amount' => $query->hb_balance_amount - $data['old_hb_approved_amount'],
                        ]
                    );
                }

                $query = EmployeeHBLoanBalance::where('employeeid','=',$data['employee'])->get();
                if(count($query) == 1)
                {
                    foreach($query as $query);
                    $hb_loan = EmployeeHBLoanBalance::where("employeeid",'=',$data['employee'])->update(
                        [
                            'hb_balance_amount' => $data['hb_approved_amount'] + $query->hb_balance_amount,
                            'updated_by' => Session::get('admin_id'),
                        ]
                    );
                }

                return redirect('/hb_loans')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_hb_loan($autoemployeehbloanid)
    {
        if(Session::get('admin_id'))
        {
            $query = EmployeeHBLoan::where('autoemployeehbloanid','=',$autoemployeehbloanid)
            ->get();
            if(count($query) > 0)
            {
                foreach($query as $query);
                $query1 = EmployeeHBLoanBalance::where('employeeid','=',$query->employeeid)
                ->get();
                if(count($query1) > 0)
                {
                    foreach($query1 as $query1);
                    $hb_loan = EmployeeHBLoanBalance::where("employeeid",'=',$query->employeeid)->update(
                        [
                            'hb_balance_amount' => $query1->hb_balance_amount - $query->hb_approved_amount,
                        ]
                    );
                }    
            }
            $hb_loan_delete = EmployeeHBLoan::where("autoemployeehbloanid",'=',$autoemployeehbloanid);
            $hb_loan_delete->delete();

            return redirect('/hb_loans')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_hb_loans(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
                    $query = EmployeeHBLoan::where('autoemployeehbloanid','=',$deleteid)
                    ->get();
                    if(count($query) > 0)
                    {
                        foreach($query as $query);
                        $query1 = EmployeeHBLoanBalance::where('employeeid','=',$query->employeeid)
                        ->get();
                        if(count($query1) > 0)
                        {
                            foreach($query1 as $query1);
                            $hb_loan = EmployeeHBLoanBalance::where("employeeid",'=',$query->employeeid)->update(
                                [
                                    'hb_balance_amount' => $query1->hb_balance_amount - $query->hb_approved_amount,
                                ]
                            );
                        }    
                    }

                    EmployeeHBLoan::where('autoemployeehbloanid', $deleteid)->delete();

				}
				return redirect('/hb_loans')->with('success',"Deleted successfully");	
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
