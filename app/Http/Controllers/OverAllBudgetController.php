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
use App\Models\OverAllBudget;

class OverAllBudgetController extends Controller
{
    function over_all_budgets()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = OverAllBudget::orderby('autobudgetid','asc')->get();
            return view('received_payments.over_all_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function over_all_budget_db(Request $request)
    {
        $rules = [
            'amount'  =>  'required|string|max:10',
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
                $data['financial_year'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['financial_year'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['financial_year'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }

                $query = OverAllBudget::where('financialyear','=',$data['financial_year'])->get();
                if(count($query) == 1)
                {
                    return back()->with('error',"Financial Year Budget already Exist!");
                }
                else
                {
                    $over_all_budget = new OverAllBudget;
                    $over_all_budget->financialyear = $data['financial_year'];
                    $over_all_budget->amount = $data['amount'];
                    $over_all_budget->balance_amount = $data['amount'];
                    $over_all_budget->added_by = Session::get('admin_id');
    
                    $over_all_budget->save();    
                    return redirect('/over_all_budgets')->with('success',"Insert Successfully");
                }

			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_over_all_budget($autobudgetid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = OverAllBudget::orderby('autobudgetid','asc')->get();
            $array1 = OverAllBudget::where("autobudgetid",'=',$autobudgetid)
            ->get();
            // dd($array);        
            return view('received_payments.over_all_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_over_all_budget_db(Request $request)
    {
        $rules = [
            'amount'  =>  'required|string|max:10',
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
                $data['financial_year'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['financial_year'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['financial_year'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
                if($data['amount'] > $data['diff_amount'])
                {
                    $over_all_budget = OverAllBudget::where("autobudgetid",'=',$data['autobudgetid'])->update(
                        [
                            'amount' => $data['amount'],
                            'balance_amount' => $data['amount'] - $data['diff_amount'],
                            'updated_by' => Session::get('admin_id'),
                        ]
                    );
    
                    return redirect('/over_all_budgets')->with('success',"Updated Successfully");                        
                }
                else
                {
                    return back()->with('error',"Invalid Amount try Greater Amount of ".$data['diff_amount']);
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_over_all_budget($autobudgetid)
    {
        if(Session::get('admin_id'))
        {
            $over_all_budget_delete = OverAllBudget::where("autobudgetid",'=',$autobudgetid);
            $over_all_budget_delete->delete();

            return redirect('/over_all_budgets')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_over_all_budgets(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
					OverAllBudget::where('autobudgetid', $deleteid)->delete();
				}
				return redirect('/over_all_budgets')->with('success',"Deleted successfully");	
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
