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
use App\Models\EmployeeMotorCarLoan;
use App\Models\EmployeeMotorCarDeduction;
use App\Models\EmployeeMotorCarLoanBalance;

class EmployeeMotorCarLoanController extends Controller
{
    function motorcar_loans()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeMotorCarLoan::orderby('autoemployeemotorcarloanid','asc')->get();
            return view('loans.motorcar_loans',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function motorcar_loan_db(Request $request)
    {
        $rules = [
            'employee'  =>  'required|string',
            'motorcar_loan_date'  =>  'required|string',
            'motorcar_loan_amount'  =>  'required|string|max:10',
            'motorcar_approved_amount'  =>  'required|string|max:10',
            'motorcar_installment_amount'  =>  'required|string|max:10',
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
                
                $motorcar_loan = new EmployeeMotorCarLoan;
                $motorcar_loan->employeeid = $data['employee'];
                $motorcar_loan->motorcar_loan_date = $data['motorcar_loan_date'];
                $motorcar_loan->motorcar_loan_amount = $data['motorcar_loan_amount'];
                $motorcar_loan->motorcar_approved_amount = $data['motorcar_approved_amount'];
                $motorcar_loan->motorcar_installment_amount = $data['motorcar_installment_amount'];
                $motorcar_loan->added_by = Session::get('admin_id');

                $motorcar_loan->save();

                $query = EmployeeMotorCarLoanBalance::where('employeeid','=',$data['employee'])->get();
                if(count($query) == 1)
                {
                    foreach($query as $query);
                    $motorcar_loan = EmployeeMotorCarLoanBalance::where("employeeid",'=',$data['employee'])->update(
                        [
                            'motorcar_balance_amount' => $data['motorcar_approved_amount'] + $query->motorcar_balance_amount,
                            'added_by' => Session::get('admin_id'),
                        ]
                    );
                }
                else
                {
                    $motorcar_loan = new EmployeeMotorCarLoanBalance;
                    $motorcar_loan->employeeid = $data['employee'];
                    $motorcar_loan->motorcar_balance_amount = $data['motorcar_approved_amount'];
                    $motorcar_loan->added_by = Session::get('admin_id');

                    $motorcar_loan->save();
                }

                return redirect('/motorcar_loans')->with('success',"Insert Successfully"); 
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_motorcar_loan($autoemployeemotorcarloanid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeMotorCarLoan::orderby('autoemployeemotorcarloanid','asc')->get();
            $array1 = EmployeeMotorCarLoan::where("autoemployeemotorcarloanid",'=',$autoemployeemotorcarloanid)
            ->get();
            // dd($array);        
            return view('loans.motorcar_loans',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_motorcar_loan_db(Request $request)
    {
        $rules = [
            'employee'  =>  'required|string',
            'motorcar_loan_date'  =>  'required|string',
            'motorcar_loan_amount'  =>  'required|string|max:10',
            'motorcar_approved_amount'  =>  'required|string|max:10',
            'motorcar_installment_amount'  =>  'required|string|max:10',
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
                $motorcar_loan = EmployeeMotorCarLoan::where("autoemployeemotorcarloanid",'=',$data['autoemployeemotorcarloanid'])->update(
                    [
                        'employeeid' => $data['employee'],
                        'motorcar_loan_date' => $data['motorcar_loan_date'],
                        'motorcar_loan_amount' => $data['motorcar_loan_amount'],
                        'motorcar_approved_amount' => $data['motorcar_approved_amount'],
                        'motorcar_installment_amount' => $data['motorcar_installment_amount'],    
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

                $query = EmployeeMotorCarLoanBalance::where('employeeid','=',$data['employee'])->get();
                if(count($query) == 1)
                {
                    foreach($query as $query);
                    $motorcar_loan = EmployeeMotorCarLoanBalance::where("employeeid",'=',$data['employee'])->update(
                        [
                            'motorcar_balance_amount' => $query->motorcar_balance_amount - $data['old_motorcar_approved_amount'],
                        ]
                    );
                }

                $query = EmployeeMotorCarLoanBalance::where('employeeid','=',$data['employee'])->get();
                if(count($query) == 1)
                {
                    foreach($query as $query);
                    $motorcar_loan = EmployeeMotorCarLoanBalance::where("employeeid",'=',$data['employee'])->update(
                        [
                            'motorcar_balance_amount' => $data['motorcar_approved_amount'] + $query->motorcar_balance_amount,
                            'updated_by' => Session::get('admin_id'),
                        ]
                    );
                }

                return redirect('/motorcar_loans')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_motorcar_loan($autoemployeemotorcarloanid)
    {
        if(Session::get('admin_id'))
        {
            $query = EmployeeMotorCarLoan::where('autoemployeemotorcarloanid','=',$autoemployeemotorcarloanid)
            ->get();
            if(count($query) > 0)
            {
                foreach($query as $query);
                $query1 = EmployeeMotorCarLoanBalance::where('employeeid','=',$query->employeeid)
                ->get();
                if(count($query1) > 0)
                {
                    foreach($query1 as $query1);
                    $hb_loan = EmployeeMotorCarLoanBalance::where("employeeid",'=',$query->employeeid)->update(
                        [
                            'motorcar_balance_amount' => $query1->motorcar_balance_amount - $query->motorcar_approved_amount,
                        ]
                    );
                }    
            }
            $motorcar_loan_delete = EmployeeMotorCarLoan::where("autoemployeemotorcarloanid",'=',$autoemployeemotorcarloanid);
            $motorcar_loan_delete->delete();

            return redirect('/motorcar_loans')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_motorcar_loans(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
                    $query = EmployeeMotorCarLoan::where('autoemployeemotorcarloanid','=',$deleteid)
                    ->get();
                    if(count($query) > 0)
                    {
                        foreach($query as $query);
                        $query1 = EmployeeMotorCarLoanBalance::where('employeeid','=',$query->employeeid)
                        ->get();
                        if(count($query1) > 0)
                        {
                            foreach($query1 as $query1);
                            $hb_loan = EmployeeMotorCarLoanBalance::where("employeeid",'=',$query->employeeid)->update(
                                [
                                    'motorcar_balance_amount' => $query1->motorcar_balance_amount - $query->motorcar_approved_amount,
                                ]
                            );
                        }    
                    }
					EmployeeMotorCarLoan::where('autoemployeemotorcarloanid', $deleteid)->delete();
				}
				return redirect('/motorcar_loans')->with('success',"Deleted successfully");	
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
