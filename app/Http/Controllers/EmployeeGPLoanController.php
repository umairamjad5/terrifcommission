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
use App\Models\EmployeeGPLoan;
use App\Models\EmployeeGPFundSubscription;
use App\Models\EmployeeGPLoanBalance;
use App\Models\GPFundSubscriptionStatment;

class EmployeeGPLoanController extends Controller
{
    function gp_loans()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeGPLoan::orderby('autoemployeegploanid','asc')->get();
            return view('loans.gp_loans',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    // function gp_loan_db_old(Request $request)
    // {
    //     $rules = [
    //         'employee'  =>  'required|string',
    //         'gp_loan_type'  =>  'required|string',
    //         'gp_loan_date'  =>  'required|string',
    //         'gp_loan_amount'  =>  'required|string|max:10',
    //         'gp_loan_installment'  =>  'required|string|max:10',
	// 	];

	// 	$validator = Validator::make($request->all(),$rules);
	// 	if ($validator->fails()) {
	// 		return back()
	// 		->withInput()
	// 		->withErrors($validator);
	// 	}
	// 	else{
    //         $data = $request->input();
	// 		try{
	// 			// dd($data);
                
    //             $query = EmployeeGPFundSubscription::where('employeeid','=',$data['employee'])->get();
    //             if(count($query) > 0)
    //             {
    //                 foreach($query as $query);

    //                 $sumamount = EmployeeGPFundSubscription::where('employeeid','=',$data['employee'])->sum('gp_fund_amount');

    //                 if($sumamount > $data['gp_loan_amount'])
    //                 {
    //                     $gp_loan = new EmployeeGPLoan;
    //                     $gp_loan->employeeid = $data['employee'];
    //                     $gp_loan->gp_loan_type = $data['gp_loan_type'];
    //                     $gp_loan->gp_loan_date = $data['gp_loan_date'];
    //                     $gp_loan->gp_loan_amount = $data['gp_loan_amount'];
    //                     $gp_loan->gp_loan_installment = $data['gp_loan_installment'];
    //                     $gp_loan->added_by = Session::get('admin_id');
        
    //                     $gp_loan->save();

    //                     $query = EmployeeGPLoanBalance::where('employeeid','=',$data['employee'])->get();
    //                     if(count($query) == 1)
    //                     {
    //                         foreach($query as $query);
    //                         $gp_loan = EmployeeGPLoanBalance::where("employeeid",'=',$data['employee'])->update(
    //                             [
    //                                 'gp_balance_amount' => $data['gp_loan_amount'] + $query->gp_balance_amount,
    //                                 'added_by' => Session::get('admin_id'),
    //                             ]
    //                         );
    //                     }
    //                     else
    //                     {
    //                         $gp_loan = new EmployeeGPLoanBalance;
    //                         $gp_loan->employeeid = $data['employee'];
    //                         $gp_loan->gp_balance_amount = $data['gp_loan_amount'];
    //                         $gp_loan->added_by = Session::get('admin_id');

    //                         $gp_loan->save();
    //                     }
    
    //                     return redirect('/gp_loans')->with('success',"Insert Successfully");    
    //                 }
    //                 else
    //                 {
    //                     return back()->with('error',"Loan amount is lower than fund amount.");  
    //                 }
    //             }
    //             else
    //             {
    //                 return back()->with('error',"Can not find Employee data.");                    
    //             }
	// 		}
	// 		catch(Exception $e){
	// 			return back()->with('error',"Error Occured");
	// 		}
	// 	}
    // }

    function gp_loan_db(Request $request)
    {
        $rules = [
            'employee'  =>  'required|string',
            'gp_loan_type'  =>  'required|string',
            'gp_loan_date'  =>  'required|string',
            'gp_loan_amount'  =>  'required|string|max:10',
            'gp_loan_installment'  =>  'required|string|max:10',
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
                
                $gp_loan = new EmployeeGPLoan;
                $gp_loan->employeeid = $data['employee'];
                $gp_loan->gp_loan_type = $data['gp_loan_type'];
                $gp_loan->gp_loan_date = $data['gp_loan_date'];
                $gp_loan->gp_loan_amount = $data['gp_loan_amount'];
                $gp_loan->gp_loan_installment = $data['gp_loan_installment'];
                $gp_loan->added_by = Session::get('admin_id');

                $gp_loan->save();

                $query = EmployeeGPLoanBalance::where('employeeid','=',$data['employee'])->get();
                if(count($query) == 1)
                {
                    foreach($query as $query);
                    $gp_loan = EmployeeGPLoanBalance::where("employeeid",'=',$data['employee'])->update(
                        [
                            'gp_balance_amount' => $data['gp_loan_amount'] + $query->gp_balance_amount,
                            'added_by' => Session::get('admin_id'),
                        ]
                    );
                }
                else
                {
                    $gp_loan = new EmployeeGPLoanBalance;
                    $gp_loan->employeeid = $data['employee'];
                    $gp_loan->gp_balance_amount = $data['gp_loan_amount'];
                    $gp_loan->added_by = Session::get('admin_id');

                    $gp_loan->save();
                }

                return redirect('/gp_loans')->with('success',"Insert Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_gp_loan($autoemployeegploanid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = EmployeeGPLoan::orderby('autoemployeegploanid','asc')->get();
            $array1 = EmployeeGPLoan::where("autoemployeegploanid",'=',$autoemployeegploanid)
            ->get();
            // dd($array);        
            return view('loans.gp_loans',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    // function update_gp_loan_db_old(Request $request)
    // {
    //     $rules = [
    //         'employee'  =>  'required|string',
    //         'gp_loan_type'  =>  'required|string',
    //         'gp_loan_date'  =>  'required|string',
    //         'gp_loan_amount'  =>  'required|string|max:10',
    //         'gp_loan_installment'  =>  'required|string|max:10',
	// 	];

	// 	$validator = Validator::make($request->all(),$rules);
	// 	if ($validator->fails()) {
	// 		return back()
	// 		->withInput()
	// 		->withErrors($validator);
	// 	}
	// 	else{
    //         $data = $request->input();
	// 		try{
	// 			// dd($data);
    //             $query = EmployeeGPFundSubscription::where('employeeid','=',$data['employee'])->get();
    //             if(count($query) > 0)
    //             {
    //                 foreach($query as $query);

    //                 $sumamount = EmployeeGPFundSubscription::where('employeeid','=',$data['employee'])->sum('gp_fund_amount');

    //                 if($sumamount > $data['gp_loan_amount'])
    //                 {
    //                     $gp_loan = EmployeeGPLoan::where("autoemployeegploanid",'=',$data['autoemployeegploanid'])->update(
    //                         [
    //                             'employeeid' => $data['employee'],
    //                             'gp_loan_type' => $data['gp_loan_type'],
    //                             'gp_loan_date' => $data['gp_loan_date'],
    //                             'gp_loan_amount' => $data['gp_loan_amount'],
    //                             'gp_loan_installment' => $data['gp_loan_installment'],
    //                             'updated_by' => Session::get('admin_id'),
    //                         ]
    //                     );
                        
    //                     $query = EmployeeGPLoanBalance::where('employeeid','=',$data['employee'])->get();
    //                     if(count($query) == 1)
    //                     {
    //                         foreach($query as $query);
    //                         $gp_loan = EmployeeGPLoanBalance::where("employeeid",'=',$data['employee'])->update(
    //                             [
    //                                 'gp_balance_amount' => $query->gp_balance_amount - $data['old_gp_loan_amount'],
    //                             ]
    //                         );
    //                     }

    //                     $query = EmployeeGPLoanBalance::where('employeeid','=',$data['employee'])->get();
    //                     if(count($query) == 1)
    //                     {
    //                         foreach($query as $query);
    //                         $gp_loan = EmployeeGPLoanBalance::where("employeeid",'=',$data['employee'])->update(
    //                             [
    //                                 'gp_balance_amount' => $data['gp_loan_amount'] + $query->gp_balance_amount,
    //                                 'updated_by' => Session::get('admin_id'),
    //                             ]
    //                         );
    //                     }
                        
    //                     return redirect('/gp_loans')->with('success',"Updated Successfully");
    //                 }
    //                 else
    //                 {
    //                     return back()->with('error',"Loan amount is lower than fund amount.");  
    //                 }
    //             }
    //             else
    //             {
    //                 return back()->with('error',"Can not find Employee data.");                    
    //             }
	// 		}
	// 		catch(Exception $e){
	// 			return back()->with('error',"Error Occured");
	// 		}
	// 	}
    // }

    function update_gp_loan_db(Request $request)
    {
        $rules = [
            'employee'  =>  'required|string',
            'gp_loan_type'  =>  'required|string',
            'gp_loan_date'  =>  'required|string',
            'gp_loan_amount'  =>  'required|string|max:10',
            'gp_loan_installment'  =>  'required|string|max:10',
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
                $gp_loan = EmployeeGPLoan::where("autoemployeegploanid",'=',$data['autoemployeegploanid'])->update(
                    [
                        'employeeid' => $data['employee'],
                        'gp_loan_type' => $data['gp_loan_type'],
                        'gp_loan_date' => $data['gp_loan_date'],
                        'gp_loan_amount' => $data['gp_loan_amount'],
                        'gp_loan_installment' => $data['gp_loan_installment'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );
                
                $query = EmployeeGPLoanBalance::where('employeeid','=',$data['employee'])->get();
                if(count($query) == 1)
                {
                    foreach($query as $query);
                    $gp_loan = EmployeeGPLoanBalance::where("employeeid",'=',$data['employee'])->update(
                        [
                            'gp_balance_amount' => $query->gp_balance_amount - $data['old_gp_loan_amount'],
                        ]
                    );
                }

                $query = EmployeeGPLoanBalance::where('employeeid','=',$data['employee'])->get();
                if(count($query) == 1)
                {
                    foreach($query as $query);
                    $gp_loan = EmployeeGPLoanBalance::where("employeeid",'=',$data['employee'])->update(
                        [
                            'gp_balance_amount' => $data['gp_loan_amount'] + $query->gp_balance_amount,
                            'updated_by' => Session::get('admin_id'),
                        ]
                    );
                }
                
                return redirect('/gp_loans')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_gp_loan($autoemployeegploanid)
    {
        if(Session::get('admin_id'))
        {
            $query = EmployeeGPLoan::where('autoemployeegploanid','=',$autoemployeegploanid)
            ->get();
            if(count($query) > 0)
            {
                foreach($query as $query);
                $query1 = EmployeeGPLoanBalance::where('employeeid','=',$query->employeeid)
                ->get();
                if(count($query1) > 0)
                {
                    foreach($query1 as $query1);
                    $gp_loan = EmployeeGPLoanBalance::where("employeeid",'=',$query->employeeid)->update(
                        [
                            'gp_balance_amount' => $query1->gp_balance_amount - $query->gp_loan_amount,
                        ]
                    );
                }    
            }
            $gp_loan_delete = EmployeeGPLoan::where("autoemployeegploanid",'=',$autoemployeegploanid);
            $gp_loan_delete->delete();

            return redirect('/gp_loans')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_gp_loans(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
                    $query = EmployeeGPLoan::where('autoemployeegploanid','=',$deleteid)
                    ->get();
                    if(count($query) > 0)
                    {
                        foreach($query as $query);
                        $query1 = EmployeeGPLoanBalance::where('employeeid','=',$query->employeeid)
                        ->get();
                        if(count($query1) > 0)
                        {
                            foreach($query1 as $query1);
                            $gp_loan = EmployeeGPLoanBalance::where("employeeid",'=',$query->employeeid)->update(
                                [
                                    'gp_balance_amount' => $query1->gp_balance_amount - $query->gp_loan_amount,
                                ]
                            );
                        }    
                    }
					EmployeeGPLoan::where('autoemployeegploanid', $deleteid)->delete();
				}
				return redirect('/gp_loans')->with('success',"Deleted successfully");	
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

    function chechurl($id)
    {
        $data['salary_month'] = '2022-09';
        $q5_3 = GPFundSubscriptionStatment::where('employeeid','=',$id)
        ->where('gp_fund_statment_month','=',$data['salary_month'])
        ->orderby('autoemployeegploanstatmentid','desc')
        ->get();
        if(count($q5_3) > 0)
        {
            foreach($q5_3 as $q5_3);
            $progressivebalance = $q5_3->progressive_balance;
        }
        else
        {
            $progressivebalance = '0';
        }
        echo $progressivebalance;
    }
}
