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
use App\Models\OverAllBankBalance;
use App\Models\SumBankBalance;
use App\Models\Ledger;

class OverAllBankBalanceController extends Controller
{
    function bank_budgets()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = OverAllBankBalance::orderby('autobankquarterlybudgetid','asc')->get();
            return view('received_payments.bank_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function bank_budget_db(Request $request)
    {
        $rules = [
            'particular'  =>  'required|string',
            'amount_date'  =>  'required|string',
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
                $data['financial_year'] = '2022 - 2023';
                $data['quarter'] = '4th Quarter';
                $thisyearmonths = date('m',time());
                
                // if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                // {
                //     $data['financial_year'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                // }
                // else
                // {
                //     $data['financial_year'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                // }
                
                // if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                // {
                //     $data['quarter'] = '3rd Quarter';
                // }
                // if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                // {
                //     $data['quarter'] = '4th Quarter';
                // }
                // if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                // {
                //     $data['quarter'] = '1st Quarter';
                // }
                // if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                // {
                //     $data['quarter'] = '2nd Quarter';
                // }
                $query = OverAllBankBalance::where('bankfinancialyear','=',$data['financial_year'])
                ->where('bank_quarterly','=',$data['quarter'])->get();
                if(count($query) == 1)
                {
                    return back()->with('error',$data['quarter']." Budget of this Financial Year is already Exist!");
                }
                else
                {
                    $bank_budget = new OverAllBankBalance;
                    $bank_budget->bankfinancialyear = $data['financial_year'];
                    $bank_budget->bank_quarterly = $data['quarter'];
                    $bank_budget->bank_name = 'National Bank';
                    $bank_budget->bank_account_title = 'National Tariff Commission';
                    $bank_budget->bank_account_no = '2267-6/1163626634';
                    $bank_budget->bank_address = 'NBP, Main Branch Melody, Islamabad.';
                    $bank_budget->bank_branch = '0341';
                    $bank_budget->bank_quarterly_amount = $data['quarterly_amount'];
                    $bank_budget->bank_quarterly_balance_amount = $data['quarterly_amount'];
                    $bank_budget->amount_date = $data['amount_date'];
                    $bank_budget->added_by = Session::get('admin_id');
    
                    $bank_budget->save();
                    $last_budget_id = $bank_budget->id;

                    $qu_ledger = Ledger::where('ledger_financialyear','=',$data['financial_year'])->get();
                    if(count($qu_ledger) > 0)
                    {
                        $qu_ledger1 = Ledger::where('ledger_financialyear','=',$data['financial_year'])
                        ->orderBy('autoledgerid','desc')
                        ->offset(0)->limit(1)
                        ->get();
                        if(count($qu_ledger1) == 1)
                        {
                            foreach($qu_ledger1 as $qu_ledger1);

                            $bank_ledger = new Ledger;
                            $bank_ledger->bankquarterlybudgetid = $last_budget_id;
                            $bank_ledger->ledger_financialyear = $data['financial_year'];
                            $bank_ledger->ledger_quarter = $data['quarter'];
                            $bank_ledger->description = $data['particular'];
                            $bank_ledger->debit_amount = $data['quarterly_amount'];
                            $bank_ledger->balance_amount = $data['quarterly_amount'] + $qu_ledger1->balance_amount;
                            $bank_ledger->last_balance_amount = $qu_ledger1->balance_amount;
                            $bank_ledger->amount_date = $data['amount_date'];
                            $bank_ledger->added_by = Session::get('admin_id');
            
                            $bank_ledger->save();
                        }
                    }
                    else
                    {
                        $bank_ledger = new Ledger;
                        $bank_ledger->bankquarterlybudgetid = $last_budget_id;
                        $bank_ledger->ledger_financialyear = $data['financial_year'];
                        $bank_ledger->ledger_quarter = $data['quarter'];
                        $bank_ledger->description = $data['particular'];
                        $bank_ledger->debit_amount = $data['quarterly_amount'];
                        $bank_ledger->balance_amount = $data['quarterly_amount'];
                        $bank_ledger->amount_date = $data['amount_date'];
                        $bank_ledger->added_by = Session::get('admin_id');
        
                        $bank_ledger->save();
                    }
                    
                    $query = SumBankBalance::where('bankfinancialyear','=',$data['financial_year'])->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);
                        // echo '1';exit;
                        $query1 = SumBankBalance::where('bankfinancialyear','=',$data['financial_year'])
                        ->update(
                            [
                                'bank_sum_amount' => $query->bank_sum_amount + $data['quarterly_amount'],
                                'bank_sum_balance_amount' => $query->bank_sum_balance_amount + $data['quarterly_amount'],
                            ]
                        );
                    }
                    else
                    {
                        // echo '2';exit;
                        $bank_balance = new SumBankBalance;
                        $bank_balance->bankfinancialyear = $data['financial_year'];
                        $bank_balance->bank_sum_amount = $data['quarterly_amount'];
                        $bank_balance->bank_sum_balance_amount = $data['quarterly_amount'];
                        $bank_balance->added_by = Session::get('admin_id');

                        $bank_balance->save();
                    }

                    return redirect('/bank_budgets')->with('success',"Insert Successfully");
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_bank_budget($autobankquarterlybudgetid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = OverAllBankBalance::orderby('autobankquarterlybudgetid','asc')->get();
            $array1 = OverAllBankBalance::where("autobankquarterlybudgetid",'=',$autobankquarterlybudgetid)
            ->get();
            // dd($array);        
            return view('received_payments.bank_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_bank_budget_db(Request $request)
    {
        $rules = [
            'particular'  =>  'required|string',
            'amount_date'  =>  'required|string',
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
                $data['financial_year'] = '2022 - 2023';
                $data['quarter'] = '4th Quarter';
                $thisyearmonths = date('m',time());
                
                // if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                // {
                //     $data['financial_year'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                // }
                // else
                // {
                //     $data['financial_year'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                // }
                
                // if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                // {
                //     $data['quarter'] = '3rd Quarter';
                // }
                // if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                // {
                //     $data['quarter'] = '4th Quarter';
                // }
                // if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                // {
                //     $data['quarter'] = '1st Quarter';
                // }
                // if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                // {
                //     $data['quarter'] = '2nd Quarter';
                // }
                if($data['quarterly_amount'] > $data['diff_amount'])
                {
                    $bank_budget = OverAllBankBalance::where("autobankquarterlybudgetid",'=',$data['autobankquarterlybudgetid'])->update(
                        [
                            'bank_quarterly_amount' => $data['quarterly_amount'],
                            'bank_quarterly_balance_amount' => $data['quarterly_amount'] - $data['diff_amount'],
                            'amount_date' => $data['amount_date'],
                            'updated_by' => Session::get('admin_id'),
                        ]
                    );

                    $ledger = Ledger::where("bankquarterlybudgetid",'=',$data['autobankquarterlybudgetid'])->update(
                        [
                            'debit_amount' => $data['quarterly_amount'],
                            'balance_amount' => $data['quarterly_amount'] + $data['last_balance_amount'],
                            'amount_date' => $data['amount_date'],
                            'updated_by' => Session::get('admin_id'),
                        ]
                    );

                    $query = SumBankBalance::where('bankfinancialyear','=',$data['financial_year'])->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);

                        $query1 = SumBankBalance::where('bankfinancialyear','=',$data['financial_year'])
                        ->update(
                            [
                                'bank_sum_amount' => $query->bank_sum_amount - $data['old_quarterly_amount'],
                                'bank_sum_balance_amount' => $query->bank_sum_balance_amount -$data['old_balance_quarterly_amount'],
                            ]
                        );
                    }   
                    $query = SumBankBalance::where('bankfinancialyear','=',$data['financial_year'])->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);

                        $new_bal = $data['quarterly_amount'] - $data['diff_amount'];
                        $query1 = SumBankBalance::where('bankfinancialyear','=',$data['financial_year'])
                        ->update(
                            [
                                'bank_sum_amount' => $query->bank_sum_amount + $data['quarterly_amount'],
                                'bank_sum_balance_amount' => $query->bank_sum_balance_amount + $new_bal,
                            ]
                        );
                    }                    
                    return redirect('/bank_budgets')->with('success',"Updated Successfully");
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

    function delete_bank_budget($autobankquarterlybudgetid)
    {
        if(Session::get('admin_id'))
        {
            $query = OverAllBankBalance::where('autobankquarterlybudgetid','=',$autobankquarterlybudgetid)
            ->get();
            if(count($query) > 0)
            {
                foreach($query as $query);
                if($query->bank_quarterly_amount == $query->bank_quarterly_balance_amount)
                {
                    
                    $query1 = SumBankBalance::where('bankfinancialyear','=',$query->bankfinancialyear)
                    ->get();
                    if(count($query1) > 0)
                    {
                        foreach($query1 as $query1);
                        $bank_loan = SumBankBalance::where('bankfinancialyear','=',$query->bankfinancialyear)->update(
                            [
                                'bank_sum_amount' => $query1->bank_sum_amount - $query->bank_quarterly_balance_amount,
                                'bank_sum_balance_amount' => $query1->bank_sum_balance_amount - $query->bank_quarterly_balance_amount,
                            ]
                        );
                    }        

                    $bank_budget_delete = OverAllBankBalance::where("autobankquarterlybudgetid",'=',$autobankquarterlybudgetid);
                    $bank_budget_delete->delete();

                    $ledger_entry_delete = Ledger::where("bankquarterlybudgetid",'=',$autobankquarterlybudgetid);
                    $ledger_entry_delete->delete();

                    return redirect('/bank_budgets')->with('success',"Deleted Successfully");
                }
                else
                {
                    return back()->with('success',"Cannot Delete Budget because Balance Amount is not qual to Actual Amount");
                }
            }   
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_bank_budgets(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
                    $query = OverAllBankBalance::where('autobankquarterlybudgetid','=',$deleteid)
                    ->get();
                    if(count($query) > 0)
                    {
                        foreach($query as $query);
                        if($query->bank_quarterly_amount == $quer->bank_quarterly_balance_amount)
                        {
                            $query1 = SumBankBalance::where('bankfinancialyear','=',$query->bankfinancialyear)
                            ->get();
                            if(count($query1) > 0)
                            {
                                foreach($query1 as $query1);
                                $bank_loan = SumBankBalance::where('bankfinancialyear','=',$query->bankfinancialyear)->update(
                                    [
                                        'bank_sum_amount' => $query1->bank_sum_amount - $query->bank_quarterly_balance_amount,
                                        'bank_sum_balance_amount' => $query1->bank_sum_balance_amount - $query->bank_quarterly_balance_amount,
                                    ]
                                );
                            }        
                            OverAllBankBalance::where('autobankquarterlybudgetid', $deleteid)->delete();

                            $ledger_entry_delete = Ledger::where("bankquarterlybudgetid",'=',$deleteid);
                            $ledger_entry_delete->delete();
        
                            return redirect('/bank_budgets')->with('success',"Deleted Successfully");
                        }
                        else
                        {
                            return back()->with('success',"Cannot Delete Budget because Balance Amount is not qual to Actual Amount");
                        }
                    }   
				}
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
