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
use App\Models\EmployeeMotorCycleLoan;
use App\Models\EmployeeMotorCycleDeduction;
use App\Models\EmployeeMotorCycleLoanBalance;

class EmployeeMotorCycleLoanController extends Controller
{
    function motorcycle_loans()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeMotorCycleLoan::orderby('autoemployeemotorcycleloanid','asc')->get();
            return view('loans.motorcycle_loans',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function motorcycle_loan_db(Request $request)
    {
        $rules = [
            'employee'  =>  'required|string',
            'motorcycle_loan_date'  =>  'required|string',
            'motorcycle_loan_amount'  =>  'required|string|max:10',
            'motorcycle_approved_amount'  =>  'required|string|max:10',
            'motorcycle_installment_amount'  =>  'required|string|max:10',
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
                
                $motorcycle_loan = new EmployeeMotorCycleLoan;
                $motorcycle_loan->employeeid = $data['employee'];
                $motorcycle_loan->motorcycle_loan_date = $data['motorcycle_loan_date'];
                $motorcycle_loan->motorcycle_loan_amount = $data['motorcycle_loan_amount'];
                $motorcycle_loan->motorcycle_approved_amount = $data['motorcycle_approved_amount'];
                $motorcycle_loan->motorcycle_installment_amount = $data['motorcycle_installment_amount'];
                $motorcycle_loan->added_by = Session::get('admin_id');

                $motorcycle_loan->save();

                $query = EmployeeMotorCycleLoanBalance::where('employeeid','=',$data['employee'])->get();
                if(count($query) == 1)
                {
                    foreach($query as $query);
                    $motorcycle_loan = EmployeeMotorCycleLoanBalance::where("employeeid",'=',$data['employee'])->update(
                        [
                            'motorcycle_balance_amount' => $data['motorcycle_approved_amount'] + $query->motorcycle_balance_amount,
                            'added_by' => Session::get('admin_id'),
                        ]
                    );
                }
                else
                {
                    $motorcycle_loan = new EmployeeMotorCycleLoanBalance;
                    $motorcycle_loan->employeeid = $data['employee'];
                    $motorcycle_loan->motorcycle_balance_amount = $data['motorcycle_approved_amount'];
                    $motorcycle_loan->added_by = Session::get('admin_id');

                    $motorcycle_loan->save();
                }

                return redirect('/motorcycle_loans')->with('success',"Insert Successfully"); 
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_motorcycle_loan($autoemployeemotorcycleloanid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeMotorCycleLoan::orderby('autoemployeemotorcycleloanid','asc')->get();
            $array1 = EmployeeMotorCycleLoan::where("autoemployeemotorcycleloanid",'=',$autoemployeemotorcycleloanid)
            ->get();
            // dd($array);        
            return view('loans.motorcycle_loans',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_motorcycle_loan_db(Request $request)
    {
        $rules = [
            'employee'  =>  'required|string',
            'motorcycle_loan_date'  =>  'required|string',
            'motorcycle_loan_amount'  =>  'required|string|max:10',
            'motorcycle_approved_amount'  =>  'required|string|max:10',
            'motorcycle_installment_amount'  =>  'required|string|max:10',
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
                $motorcycle_loan = EmployeeMotorCycleLoan::where("autoemployeemotorcycleloanid",'=',$data['autoemployeemotorcycleloanid'])->update(
                    [
                        'employeeid' => $data['employee'],
                        'motorcycle_loan_date' => $data['motorcycle_loan_date'],
                        'motorcycle_loan_amount' => $data['motorcycle_loan_amount'],
                        'motorcycle_approved_amount' => $data['motorcycle_approved_amount'],
                        'motorcycle_installment_amount' => $data['motorcycle_installment_amount'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

                $query = EmployeeMotorCycleLoanBalance::where('employeeid','=',$data['employee'])->get();
                if(count($query) == 1)
                {
                    foreach($query as $query);
                    $motorcycle_loan = EmployeeMotorCycleLoanBalance::where("employeeid",'=',$data['employee'])->update(
                        [
                            'motorcycle_balance_amount' => $query->motorcycle_balance_amount - $data['old_motorcycle_approved_amount'],
                        ]
                    );
                }

                $query = EmployeeMotorCycleLoanBalance::where('employeeid','=',$data['employee'])->get();
                if(count($query) == 1)
                {
                    foreach($query as $query);
                    $motorcycle_loan = EmployeeMotorCycleLoanBalance::where("employeeid",'=',$data['employee'])->update(
                        [
                            'motorcycle_balance_amount' => $data['motorcycle_approved_amount'] + $query->motorcycle_balance_amount,
                            'updated_by' => Session::get('admin_id'),
                        ]
                    );
                }

                return redirect('/motorcycle_loans')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_motorcycle_loan($autoemployeemotorcycleloanid)
    {
        if(Session::get('admin_id'))
        {
            $query = EmployeeMotorCycleLoan::where('autoemployeemotorcycleloanid','=',$autoemployeemotorcycleloanid)
            ->get();
            if(count($query) > 0)
            {
                foreach($query as $query);
                $query1 = EmployeeMotorCycleLoanBalance::where('employeeid','=',$query->employeeid)
                ->get();
                if(count($query1) > 0)
                {
                    foreach($query1 as $query1);
                    $hb_loan = EmployeeMotorCycleLoanBalance::where("employeeid",'=',$query->employeeid)->update(
                        [
                            'motorcycle_balance_amount' => $query1->motorcycle_balance_amount - $query->motorcycle_approved_amount,
                        ]
                    );
                }    
            }
            $motorcycle_loan_delete = EmployeeMotorCycleLoan::where("autoemployeemotorcycleloanid",'=',$autoemployeemotorcycleloanid);
            $motorcycle_loan_delete->delete();

            return redirect('/motorcycle_loans')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_motorcycle_loans(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
                    $query = EmployeeMotorCycleLoan::where('autoemployeemotorcycleloanid','=',$deleteid)
                    ->get();
                    if(count($query) > 0)
                    {
                        foreach($query as $query);
                        $query1 = EmployeeMotorCycleLoanBalance::where('employeeid','=',$query->employeeid)
                        ->get();
                        if(count($query1) > 0)
                        {
                            foreach($query1 as $query1);
                            $hb_loan = EmployeeMotorCycleLoanBalance::where("employeeid",'=',$query->employeeid)->update(
                                [
                                    'motorcycle_balance_amount' => $query1->motorcycle_balance_amount - $query->motorcycle_approved_amount,
                                ]
                            );
                        }    
                    }
					EmployeeMotorCycleLoan::where('autoemployeemotorcycleloanid', $deleteid)->delete();
				}
				return redirect('/motorcycle_loans')->with('success',"Deleted successfully");	
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
