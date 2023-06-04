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
use App\Models\QuarterlyBudget;

class QuarterlyBudgetController extends Controller
{
    function quarterly_budgets()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = QuarterlyBudget::orderby('autoquarterlybudgetid','asc')->get();
            return view('received_payments.quarterly_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function quarterly_budget_db(Request $request)
    {
        $rules = [
            'quarterly_amount'  =>  'required|string|max:10',
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
                $data['quarter'] = '';
                $thisyearmonths = date('02',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['financial_year'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['financial_year'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                {
                    $data['quarter'] = '3rd Quarter';
                }
                if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                {
                    $data['quarter'] = '4th Quarter';
                }
                if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                {
                    $data['quarter'] = '1st Quarter';
                }
                if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                {
                    $data['quarter'] = '2nd Quarter';
                }
                $query = QuarterlyBudget::where('financialyear','=',$data['financial_year'])
                ->where('quarterly','=',$data['quarter'])->get();
                if(count($query) == 1)
                {
                    return back()->with('error',$data['quarter']." Budget of this Financial Year is already Exist!");
                }
                else
                {
                    $quarterly_budget = new QuarterlyBudget;
                    $quarterly_budget->financialyear = $data['financial_year'];
                    $quarterly_budget->quarterly = $data['quarter'];
                    $quarterly_budget->quarterly_amount = $data['quarterly_amount'];
                    $quarterly_budget->quarterly_balance_amount = $data['quarterly_amount'];
                    $quarterly_budget->added_by = Session::get('admin_id');
    
                    $quarterly_budget->save();

                    return redirect('/quarterly_budgets')->with('success',"Insert Successfully");
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_quarterly_budget($autoquarterlybudgetid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = QuarterlyBudget::orderby('autoquarterlybudgetid','asc')->get();
            $array1 = QuarterlyBudget::where("autoquarterlybudgetid",'=',$autoquarterlybudgetid)
            ->get();
            // dd($array);        
            return view('received_payments.quarterly_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_quarterly_budget_db(Request $request)
    {
        $rules = [
            'quarterly_amount'  =>  'required|string|max:10',
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
                $data['quarter'] = '';
                $thisyearmonths = date('02',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['financial_year'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['financial_year'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                {
                    $data['quarter'] = '3rd Quarter';
                }
                if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                {
                    $data['quarter'] = '4th Quarter';
                }
                if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                {
                    $data['quarter'] = '1st Quarter';
                }
                if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                {
                    $data['quarter'] = '2nd Quarter';
                }
                if($data['quarterly_amount'] > $data['diff_amount'])
                {
                    $quarterly_budget = QuarterlyBudget::where("autoquarterlybudgetid",'=',$data['autoquarterlybudgetid'])->update(
                        [
                            'quarterly_amount' => $data['quarterly_amount'],
                            'quarterly_balance_amount' => $data['quarterly_amount'] - $data['diff_amount'],
                            'updated_by' => Session::get('admin_id'),
                        ]
                    );
                    
                    return redirect('/quarterly_budgets')->with('success',"Updated Successfully");
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

    function delete_quarterly_budget($autoquarterlybudgetid)
    {
        if(Session::get('admin_id'))
        {
            $quarterly_budget_delete = QuarterlyBudget::where("autoquarterlybudgetid",'=',$autoquarterlybudgetid);
            $quarterly_budget_delete->delete();

            return redirect('/quarterly_budgets')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_quarterly_budgets(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
					QuarterlyBudget::where('autoquarterlybudgetid', $deleteid)->delete();
				}
				return redirect('/quarterly_budgets')->with('success',"Deleted successfully");	
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
