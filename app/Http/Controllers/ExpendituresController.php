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
use App\Models\Expenditures;
use App\Models\OverAllBudget;
use App\Models\QuarterlyBudget;
use App\Models\OverAllBankBalance;
use App\Models\SumBankBalance;
use App\Models\HeadCategoriesBudget;
use App\Models\HeadsBudget;
use App\Models\MainHeadBudget;
use App\Models\SubHeadBudget;
use App\Models\Head;
use App\Models\MainHead;
use App\Models\SubHead;
use App\Models\Ledger;
use App\Models\HeadCategoriesBalanace;
use App\Models\HeadsBalanace;
use App\Models\MainHeadsBalanace;
use App\Models\SubHeadsBalanace;

class ExpendituresController extends Controller
{
    function validate_heads_expenditure()
    {
        if(Session::get('admin_id'))
        {
            return view('expenditures.validate_heads_expenditure');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function add_expenditures(Request $request)
    {
        $rules = [
            'sub_head'  =>  'required|string',
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
                $sub_head = $data['sub_head'];
                $query = SubHead::where('autosubheadid','=',$sub_head)->get();
                foreach($query as $query);

                return redirect('/action_expenditures/'.$query->headcategoryid.'/'.$query->headid.'/'.$query->mainheadid.'/'.$query->autosubheadid);
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function expenditures()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = Expenditures::join("head_categories","head_categories.autoheadcategoryid",'=',"expenditures.headcategoryid")
            ->join("heads","heads.autoheadid",'=',"expenditures.headid")
            ->join("main_heads","main_heads.automainheadid",'=',"expenditures.mainheadid")
            ->join("sub_heads","sub_heads.autosubheadid",'=',"expenditures.subheadid")
            ->orderby('expenditures.autoexpenditureid','asc')->get();
            return view('expenditures.expenditures',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function action_expenditures($head_category,$head,$main_head,$sub_head)
    {
        if(Session::get('admin_id'))
        {
            return view('expenditures.action_expenditures',compact('head_category','head','main_head','sub_head'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function action_new_expenditures()
    {
        if(Session::get('admin_id'))
        {
            return view('expenditures.action_new_expenditures');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function expenditure_db(Request $request)
    {
        $rules = [
            'debit_head'    =>  'required|string',
            'bill_type' =>  'required|string',
            'bill_no'   =>  'required|string',
            'payable_to'    =>  'required|string',
            'description'   =>  'required|string',
            'gross_amount'  =>  'required|string',
            'gst'   =>  'required|string',
            'gst_ratio' =>  'required|string',
            'income_tax'    =>  'required|string',
            'net_amount'    =>  'required|string',
            'amount_date'   =>  'required|string',
            'credit_head'    =>  'required|string',
            'payable_from'  =>  'required|string',
            'description_from'  =>  'required|string',
            'credit_amount' =>  'required|string',
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
                $data['subheadid'] = $data['debit_head'];

                $data['expenditure_financialyear'] = '';
                $data['expenditure_quarter'] = '';
                $thisyearmonths = date('02',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['expenditure_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['expenditure_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                {
                    $data['expenditure_quarter'] = '3rd Quarter';
                }
                if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                {
                    $data['expenditure_quarter'] = '4th Quarter';
                }
                if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                {
                    $data['expenditure_quarter'] = '1st Quarter';
                }
                if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                {
                    $data['expenditure_quarter'] = '2nd Quarter';
                }
                
                $query_check = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                ->where('sub_head_financial_year','=',$data['expenditure_financialyear'])
                ->where('sub_head_quarter','=',$data['expenditure_quarter'])
                ->get();
                if(count($query_check) > 0)
                {
                    foreach($query_check as $query_check);
                    if($query_check->sub_head_budget_balance > $data['net_amount'])
                    {
                        $subheadids = SubHead::where('autosubheadid','=',$data['subheadid'])->get();
                        foreach($subheadids as $subheadids);

                        $data['headcategoryid'] = $subheadids->headcategoryid;
                        $data['headid'] = $subheadids->headid;
                        $data['mainheadid'] = $subheadids->mainheadid;

                        $expenditure = new Expenditures;
                        $expenditure->expenditure_financialyear = $data['expenditure_financialyear'];
                        $expenditure->expenditure_quarter = $data['expenditure_quarter'];
                        $expenditure->headcategoryid = $data['headcategoryid'];
                        $expenditure->headid = $data['headid'];
                        $expenditure->mainheadid = $data['mainheadid'];
                        $expenditure->subheadid = $data['subheadid'];

                        $expenditure->bill_type = $data['bill_type'];
                        $expenditure->bill_no = $data['bill_no'];
                        $expenditure->payable_to = $data['payable_to'];
                        $expenditure->description = $data['description'];
                        $expenditure->gross_amount = $data['gross_amount'];
                        $expenditure->gst = $data['gst'];
                        $expenditure->gst_ratio = $data['gst_ratio'];
                        $expenditure->income_tax = $data['income_tax'];
                        $expenditure->net_amount = $data['net_amount'];
                        $expenditure->payable_from = $data['payable_from'];
                        $expenditure->description_from = $data['description_from'];
                        $expenditure->credit_amount = $data['credit_amount'];
                        $expenditure->amount_date = $data['amount_date'];
                        $expenditure->added_by = Session::get('admin_id');
                                                
                        $expenditure->save();
                        $exp_last_id = $expenditure->id;

                        $debit_ledger = new Ledger;
                        $debit_ledger->expenditureid = $exp_last_id;
                        $debit_ledger->ledger_financialyear = $data['expenditure_financialyear'];
                        $debit_ledger->ledger_quarter = $data['expenditure_quarter'];
                        $debit_ledger->headcategoryid = $data['headcategoryid'];
                        $debit_ledger->headid = $data['headid'];
                        $debit_ledger->mainheadid = $data['mainheadid'];
                        $debit_ledger->subheadid = $data['subheadid'];

                        $debit_ledger->payable_to = $data['payable_to'];
                        $debit_ledger->description = $data['description'];
                        $debit_ledger->incometax_amount = $data['income_tax'];
                        $debit_ledger->gross_amount = $data['gross_amount'];
                        $debit_ledger->gst = $data['gst'];
                        $debit_ledger->gst_ratio = $data['gst_ratio'];
                        $debit_ledger->debit_amount = $data['net_amount'];
                        $debit_ledger->payable_from = $data['payable_from'];
                        $debit_ledger->description_from = $data['description_from'];
                        $debit_ledger->credit_amount = $data['credit_amount'];
                        $debit_ledger->balance_amount = $data['ledger_balance_amount'] - $data['net_amount'];
                        $debit_ledger->last_balance_amount = $data['ledger_balance_amount'];
                        $debit_ledger->amount_date = $data['amount_date'];

                        $debit_ledger->added_by = Session::get('admin_id');                        
                        $debit_ledger->save();
        
                        $query1 = OverAllBudget::where('financialyear','=',$data['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = OverAllBudget::where('financialyear','=',$data['expenditure_financialyear'])
                            ->update(
                                [
                                    'balance_amount'  =>  $row1->balance_amount - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = QuarterlyBudget::where('financialyear','=',$data['expenditure_financialyear'])
                        ->where('quarterly','=',$data['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = QuarterlyBudget::where('financialyear','=',$data['expenditure_financialyear'])
                            ->where('quarterly','=',$data['expenditure_quarter'])
                            ->update(
                                [
                                    'quarterly_balance_amount'  =>  $row1->quarterly_balance_amount - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = OverAllBankBalance::where('bankfinancialyear','=',$data['expenditure_financialyear'])
                        ->where('bank_quarterly','=',$data['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = OverAllBankBalance::where('bankfinancialyear','=',$data['expenditure_financialyear'])
                            ->where('bank_quarterly','=',$data['expenditure_quarter'])
                            ->update(
                                [
                                    'bank_quarterly_balance_amount'  =>  $row1->bank_quarterly_balance_amount - $data['net_amount'],
                                ]
                            );
                        }

                        $query1 = SumBankBalance::where('bankfinancialyear','=',$data['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = SumBankBalance::where('bankfinancialyear','=',$data['expenditure_financialyear'])
                            ->update(
                                [
                                    'bank_sum_balance_amount'  =>  $row1->bank_sum_balance_amount - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = HeadCategoriesBudget::where('headcategoryid','=',$data['headcategoryid'])
                        ->where('head_category_financial_year','=',$data['expenditure_financialyear'])
                        ->where('head_category_quarter','=',$data['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadCategoriesBudget::where('headcategoryid','=',$data['headcategoryid'])
                            ->where('head_category_financial_year','=',$data['expenditure_financialyear'])
                            ->where('head_category_quarter','=',$data['expenditure_quarter'])
                            ->update(
                                [
                                    'head_category_budget_balance'  =>  $row1->head_category_budget_balance - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = HeadsBudget::where('headid','=',$data['headid'])
                        ->where('head_financial_year','=',$data['expenditure_financialyear'])
                        ->where('head_quarter','=',$data['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadsBudget::where('headid','=',$data['headid'])
                            ->where('head_financial_year','=',$data['expenditure_financialyear'])
                            ->where('head_quarter','=',$data['expenditure_quarter'])
                            ->update(
                                [
                                    'head_budget_balance'  =>  $row1->head_budget_balance - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = MainHeadBudget::where('mainheadid','=',$data['mainheadid'])
                        ->where('main_head_financial_year','=',$data['expenditure_financialyear'])
                        ->where('main_head_quarter','=',$data['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = MainHeadBudget::where('mainheadid','=',$data['mainheadid'])
                            ->where('main_head_financial_year','=',$data['expenditure_financialyear'])
                            ->where('main_head_quarter','=',$data['expenditure_quarter'])
                            ->update(
                                [
                                    'main_head_budget_balance'  =>  $row1->main_head_budget_balance - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                        ->where('sub_head_financial_year','=',$data['expenditure_financialyear'])
                        ->where('sub_head_quarter','=',$data['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                            ->where('sub_head_financial_year','=',$data['expenditure_financialyear'])
                            ->where('sub_head_quarter','=',$data['expenditure_quarter'])
                            ->update(
                                [
                                    'sub_head_budget_balance'  =>  $row1->sub_head_budget_balance - $data['net_amount'],
                                ]
                            );
                        }

                        //  Manage Balance

                        $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$data['headcategoryid'])
                        ->where('head_category_financial_year','=',$data['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$data['headcategoryid'])
                            ->where('head_category_financial_year','=',$data['expenditure_financialyear'])
                            ->update(
                                [
                                    'head_category_balance_amount'  =>  $row1->head_category_balance_amount - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = HeadsBalanace::where('headid','=',$data['headid'])
                        ->where('head_financial_year','=',$data['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadsBalanace::where('headid','=',$data['headid'])
                            ->where('head_financial_year','=',$data['expenditure_financialyear'])
                            ->update(
                                [
                                    'head_balance_amount'  =>  $row1->head_balance_amount - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = MainHeadsBalanace::where('mainheadid','=',$data['mainheadid'])
                        ->where('main_head_financial_year','=',$data['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = MainHeadsBalanace::where('mainheadid','=',$data['mainheadid'])
                            ->where('main_head_financial_year','=',$data['expenditure_financialyear'])
                            ->update(
                                [
                                    'main_head_balance_amount'  =>  $row1->main_head_balance_amount - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = SubHeadsBalanace::where('subheadid','=',$data['subheadid'])
                        ->where('sub_head_financial_year','=',$data['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = SubHeadsBalanace::where('subheadid','=',$data['subheadid'])
                            ->where('sub_head_financial_year','=',$data['expenditure_financialyear'])
                            ->update(
                                [
                                    'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount - $data['net_amount'],
                                ]
                            );
                        }
                        
        
                        // return back()->with('success',"Insert Successfully");
                        // return redirect('/action_expenditures/'.$data['headcategoryid'].'/'.$data['headid'].'/'.$data['mainheadid'].'/'.$data['subheadid'])->with('success',"Insert Successfully");
                        return redirect('/action_new_expenditures')->with('success',"Insert Successfully");
                    }
                    else
                    {
                        return back()->with('error',"Budget Amount exceed Limit");
                    }
                }
                else
                {
                    return back()->with('error',"Head Budget Error Occured");
                }
            }
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_expenditure($autoexpenditureid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = Expenditures::orderby('expenditures.autoexpenditureid','asc')->get();
            $array1 = Expenditures::where("autoexpenditureid",'=',$autoexpenditureid)
            ->get();
            // dd($array);        
            return view('expenditures.action_new_expenditures',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_expenditure_db(Request $request)
    {
        $rules = [
            'debit_head'    =>  'required|string',
            'bill_type' =>  'required|string',
            'bill_no'   =>  'required|string',
            'payable_to'    =>  'required|string',
            'description'   =>  'required|string',
            'gross_amount'  =>  'required|string',
            'gst'   =>  'required|string',
            'gst_ratio' =>  'required|string',
            'income_tax'    =>  'required|string',
            'net_amount'    =>  'required|string',
            'amount_date'   =>  'required|string',
            'credit_head'    =>  'required|string',
            'payable_from'  =>  'required|string',
            'description_from'  =>  'required|string',
            'credit_amount' =>  'required|string',
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
                $data['subheadid'] = $data['debit_head'];

                $data['expenditure_financialyear'] = '';
                $data['expenditure_quarter'] = '';
                $thisyearmonths = date('02',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['expenditure_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['expenditure_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                {
                    $data['expenditure_quarter'] = '3rd Quarter';
                }
                if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                {
                    $data['expenditure_quarter'] = '4th Quarter';
                }
                if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                {
                    $data['expenditure_quarter'] = '1st Quarter';
                }
                if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                {
                    $data['expenditure_quarter'] = '2nd Quarter';
                }
                
                $query_check = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                ->where('sub_head_financial_year','=',$data['expenditure_financialyear'])
                ->where('sub_head_quarter','=',$data['expenditure_quarter'])
                ->get();
                if(count($query_check) > 0)
                {
                    foreach($query_check as $query_check);
                    $checkamount = $query_check->sub_head_budget_balance + $data['old_net_amount'];
                    if($checkamount > $data['net_amount'])
                    {
                        $subheadids = SubHead::where('autosubheadid','=',$data['subheadid'])->get();
                        foreach($subheadids as $subheadids);

                        $data['headcategoryid'] = $subheadids->headcategoryid;
                        $data['headid'] = $subheadids->headid;
                        $data['mainheadid'] = $subheadids->mainheadid;

                        $expenditure = Expenditures::where("autoexpenditureid",'=',$data['autoexpenditureid'])->update(
                            [
                                'expenditure_financialyear' => $data['expenditure_financialyear'],
                                'expenditure_quarter' => $data['expenditure_quarter'],
                                'headcategoryid' => $data['headcategoryid'],
                                'headid' => $data['headid'],
                                'mainheadid' => $data['mainheadid'],
                                'subheadid' => $data['subheadid'],

                                'bill_type' => $data['bill_type'],
                                'bill_no' => $data['bill_no'],
                                'payable_to' => $data['payable_to'],
                                'description' => $data['description'],
                                'gross_amount' => $data['gross_amount'],
                                'gst' => $data['gst'],
                                'gst_ratio' => $data['gst_ratio'],
                                'income_tax' => $data['income_tax'],
                                'net_amount' => $data['net_amount'],
                                'payable_from' => $data['payable_from'],
                                'description_from' => $data['description_from'],
                                'credit_amount' => $data['credit_amount'],
                                'amount_date' => $data['amount_date'],

                                'updated_by' => Session::get('admin_id'),
                            ]
                        );

                        $debit_ledger = Ledger::where("autoledgerid",'=',$data['debitautoledgerid'])->update(
                            [
                                'ledger_financialyear' => $data['expenditure_financialyear'],
                                'ledger_quarter' => $data['expenditure_quarter'],
                                'headcategoryid' => $data['headcategoryid'],
                                'headid' => $data['headid'],
                                'mainheadid' => $data['mainheadid'],
                                'subheadid' => $data['subheadid'],

                                'payable_to' => $data['payable_to'],
                                'description' => $data['description'],
                                'incometax_amount' => $data['income_tax'],
                                'gross_amount' => $data['gross_amount'],
                                'gst' => $data['gst'],
                                'gst_ratio' => $data['gst_ratio'],
                                'debit_amount' => $data['net_amount'],
                                'payable_from' => $data['payable_from'],
                                'description_from' => $data['description_from'],
                                'credit_amount' => $data['credit_amount'],
                                'balance_amount' => $data['ledger_balance_amount'] - $data['net_amount'],
                                'last_balance_amount' => $data['ledger_balance_amount'],
                                'amount_date' => $data['amount_date'],

                                'updated_by' => Session::get('admin_id'),
                            ]
                        );
        
                        $query1 = OverAllBudget::where('financialyear','=',$data['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = OverAllBudget::where('financialyear','=',$data['expenditure_financialyear'])
                            ->update(
                                [
                                    'balance_amount'  =>  $row1->balance_amount + $data['old_net_amount'] -$data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = QuarterlyBudget::where('financialyear','=',$data['expenditure_financialyear'])
                        ->where('quarterly','=',$data['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = QuarterlyBudget::where('financialyear','=',$data['expenditure_financialyear'])
                            ->where('quarterly','=',$data['expenditure_quarter'])
                            ->update(
                                [
                                    'quarterly_balance_amount'  =>  $row1->quarterly_balance_amount + $data['old_net_amount'] -$data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = OverAllBankBalance::where('bankfinancialyear','=',$data['expenditure_financialyear'])
                        ->where('bank_quarterly','=',$data['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = OverAllBankBalance::where('bankfinancialyear','=',$data['expenditure_financialyear'])
                            ->where('bank_quarterly','=',$data['expenditure_quarter'])
                            ->update(
                                [
                                    'bank_quarterly_balance_amount'  =>  $row1->bank_quarterly_balance_amount + $data['old_net_amount'] -$data['net_amount'],
                                ]
                            );
                        }

                        $query1 = SumBankBalance::where('bankfinancialyear','=',$data['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = SumBankBalance::where('bankfinancialyear','=',$data['expenditure_financialyear'])

                            ->update(
                                [
                                    'bank_sum_balance_amount'  =>  $row1->bank_sum_balance_amount + $data['old_net_amount'] -$data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = HeadCategoriesBudget::where('headcategoryid','=',$data['headcategoryid'])
                        ->where('head_category_financial_year','=',$data['expenditure_financialyear'])
                        ->where('head_category_quarter','=',$data['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadCategoriesBudget::where('headcategoryid','=',$data['headcategoryid'])
                            ->where('head_category_financial_year','=',$data['expenditure_financialyear'])
                            ->where('head_category_quarter','=',$data['expenditure_quarter'])
                            ->update(
                                [
                                    'head_category_budget_balance'  =>  $row1->head_category_budget_balance + $data['old_net_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
        
                        $query1 = HeadsBudget::where('headid','=',$data['headid'])
                        ->where('head_financial_year','=',$data['expenditure_financialyear'])
                        ->where('head_quarter','=',$data['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadsBudget::where('headid','=',$data['headid'])
                            ->where('head_financial_year','=',$data['expenditure_financialyear'])
                            ->where('head_quarter','=',$data['expenditure_quarter'])
                            ->update(
                                [
                                    'head_budget_balance'  =>  $row1->head_budget_balance + $data['old_net_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
        
                        $query1 = MainHeadBudget::where('mainheadid','=',$data['mainheadid'])
                        ->where('main_head_financial_year','=',$data['expenditure_financialyear'])
                        ->where('main_head_quarter','=',$data['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = MainHeadBudget::where('mainheadid','=',$data['mainheadid'])
                            ->where('main_head_financial_year','=',$data['expenditure_financialyear'])
                            ->where('main_head_quarter','=',$data['expenditure_quarter'])
                            ->update(
                                [
                                    'main_head_budget_balance'  =>  $row1->main_head_budget_balance + $data['old_net_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
        
                        $query1 = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                        ->where('sub_head_financial_year','=',$data['expenditure_financialyear'])
                        ->where('sub_head_quarter','=',$data['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                            ->where('sub_head_financial_year','=',$data['expenditure_financialyear'])
                            ->where('sub_head_quarter','=',$data['expenditure_quarter'])
                            ->update(
                                [
                                    'sub_head_budget_balance'  =>  $row1->sub_head_budget_balance + $data['old_net_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }

                        // Manage Balances

                        $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$data['headcategoryid'])
                        ->where('head_category_financial_year','=',$data['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$data['headcategoryid'])
                            ->where('head_category_financial_year','=',$data['expenditure_financialyear'])
                            ->update(
                                [
                                    'head_category_balance_amount'  =>  $row1->head_category_balance_amount + $data['old_net_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
        
                        $query1 = HeadsBalanace::where('headid','=',$data['headid'])
                        ->where('head_financial_year','=',$data['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadsBalanace::where('headid','=',$data['headid'])
                            ->where('head_financial_year','=',$data['expenditure_financialyear'])
                            ->update(
                                [
                                    'head_balance_amount'  =>  $row1->head_balance_amount + $data['old_net_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
        
                        $query1 = MainHeadsBalanace::where('mainheadid','=',$data['mainheadid'])
                        ->where('main_head_financial_year','=',$data['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = MainHeadsBalanace::where('mainheadid','=',$data['mainheadid'])
                            ->where('main_head_financial_year','=',$data['expenditure_financialyear'])
                            ->update(
                                [
                                    'main_head_balance_amount'  =>  $row1->main_head_balance_amount + $data['old_net_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
        
                        $query1 = SubHeadsBalanace::where('subheadid','=',$data['subheadid'])
                        ->where('sub_head_financial_year','=',$data['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = SubHeadsBalanace::where('subheadid','=',$data['subheadid'])
                            ->where('sub_head_financial_year','=',$data['expenditure_financialyear'])
                            ->update(
                                [
                                    'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount + $data['old_net_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
                        return redirect('/expenditures')->with('success',"Updated Successfully");
                    }
                    else
                    {
                        return back()->with('error',"Budget Amount exceed Limit");
                    }    
                }
                else
                {
                    return back()->with('error',"Head Budget Error Occured");
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_expenditure($autoexpenditureid)
    {
        if(Session::get('admin_id'))
        {
            $query = Expenditures::where('autoexpenditureid','=',$autoexpenditureid)
            ->get();
            if(!empty($query))
            {
                foreach($query as $row);

                $query1 = OverAllBudget::where('financialyear','=',$row['expenditure_financialyear'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = OverAllBudget::where('financialyear','=',$row['expenditure_financialyear'])
                    ->update(
                        [
                            'balance_amount'  =>  $row1->balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = QuarterlyBudget::where('financialyear','=',$row['expenditure_financialyear'])
                ->where('quarterly','=',$row['expenditure_quarter'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = QuarterlyBudget::where('financialyear','=',$row['expenditure_financialyear'])
                    ->where('quarterly','=',$row['expenditure_quarter'])
                    ->update(
                        [
                            'quarterly_balance_amount'  =>  $row1->quarterly_balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = OverAllBankBalance::where('bankfinancialyear','=',$row['expenditure_financialyear'])
                ->where('bank_quarterly','=',$row['expenditure_quarter'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = OverAllBankBalance::where('bankfinancialyear','=',$row['expenditure_financialyear'])
                    ->where('bank_quarterly','=',$row['expenditure_quarter'])
                    ->update(
                        [
                            'bank_quarterly_balance_amount'  =>  $row1->bank_quarterly_balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = SumBankBalance::where('bankfinancialyear','=',$row['expenditure_financialyear'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = SumBankBalance::where('bankfinancialyear','=',$row['expenditure_financialyear'])

                    ->update(
                        [
                            'bank_sum_balance_amount'  =>  $row1->bank_sum_balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = HeadCategoriesBudget::where('headcategoryid','=',$row['headcategoryid'])
                ->where('head_category_financial_year','=',$row['expenditure_financialyear'])
                ->where('head_category_quarter','=',$row['expenditure_quarter'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = HeadCategoriesBudget::where('headcategoryid','=',$row['headcategoryid'])
                    ->where('head_category_financial_year','=',$row['expenditure_financialyear'])
                    ->where('head_category_quarter','=',$row['expenditure_quarter'])
                    ->update(
                        [
                            'head_category_budget_balance'  =>  $row1->head_category_budget_balance + $row['net_amount'],
                        ]
                    );
                }

                $query1 = HeadsBudget::where('headid','=',$row['headid'])
                ->where('head_financial_year','=',$row['expenditure_financialyear'])
                ->where('head_quarter','=',$row['expenditure_quarter'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = HeadsBudget::where('headid','=',$row['headid'])
                    ->where('head_financial_year','=',$row['expenditure_financialyear'])
                    ->where('head_quarter','=',$row['expenditure_quarter'])
                    ->update(
                        [
                            'head_budget_balance'  =>  $row1->head_budget_balance + $row['net_amount'],
                        ]
                    );
                }

                $query1 = MainHeadBudget::where('mainheadid','=',$row['mainheadid'])
                ->where('main_head_financial_year','=',$row['expenditure_financialyear'])
                ->where('main_head_quarter','=',$row['expenditure_quarter'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = MainHeadBudget::where('mainheadid','=',$row['mainheadid'])
                    ->where('main_head_financial_year','=',$row['expenditure_financialyear'])
                    ->where('main_head_quarter','=',$row['expenditure_quarter'])
                    ->update(
                        [
                            'main_head_budget_balance'  =>  $row1->main_head_budget_balance + $row['net_amount'],
                        ]
                    );
                }

                $query1 = SubHeadBudget::where('subheadid','=',$row['subheadid'])
                ->where('sub_head_financial_year','=',$row['expenditure_financialyear'])
                ->where('sub_head_quarter','=',$row['expenditure_quarter'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = SubHeadBudget::where('subheadid','=',$row['subheadid'])
                    ->where('sub_head_financial_year','=',$row['expenditure_financialyear'])
                    ->where('sub_head_quarter','=',$row['expenditure_quarter'])
                    ->update(
                        [
                            'sub_head_budget_balance'  =>  $row1->sub_head_budget_balance + $row['net_amount'],
                        ]
                    );
                }

                // Manage balance

                $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
                ->where('head_category_financial_year','=',$row['expenditure_financialyear'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
                    ->where('head_category_financial_year','=',$row['expenditure_financialyear'])
                    ->update(
                        [
                            'head_category_balance_amount'  =>  $row1->head_category_balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = HeadsBalanace::where('headid','=',$row['headid'])
                ->where('head_financial_year','=',$row['expenditure_financialyear'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = HeadsBalanace::where('headid','=',$row['headid'])
                    ->where('head_financial_year','=',$row['expenditure_financialyear'])
                    ->update(
                        [
                            'head_balance_amount'  =>  $row1->head_balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
                ->where('main_head_financial_year','=',$row['expenditure_financialyear'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
                    ->where('main_head_financial_year','=',$row['expenditure_financialyear'])
                    ->update(
                        [
                            'main_head_balance_amount'  =>  $row1->main_head_balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
                ->where('sub_head_financial_year','=',$row['expenditure_financialyear'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
                    ->where('sub_head_financial_year','=',$row['expenditure_financialyear'])
                    ->update(
                        [
                            'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount + $row['net_amount'],
                        ]
                    );
                }
            }

            $expenditure_delete = Expenditures::where("autoexpenditureid",'=',$autoexpenditureid);
            $expenditure_delete->delete();

            $ledger_delete = Ledger::where("expenditureid",'=',$autoexpenditureid);
            $ledger_delete->delete();

            return redirect('/expenditures')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_expenditures(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
                    $query = Expenditures::where('autoexpenditureid','=',$deleteid)
                    ->get();
                    if(!empty($query))
                    {
                        foreach($query as $row);

                        $query1 = OverAllBudget::where('financialyear','=',$row['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = OverAllBudget::where('financialyear','=',$row['expenditure_financialyear'])
                            ->update(
                                [
                                    'balance_amount'  =>  $row1->balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = QuarterlyBudget::where('financialyear','=',$row['expenditure_financialyear'])
                        ->where('quarterly','=',$row['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = QuarterlyBudget::where('financialyear','=',$row['expenditure_financialyear'])
                            ->where('quarterly','=',$row['expenditure_quarter'])
                            ->update(
                                [
                                    'quarterly_balance_amount'  =>  $row1->quarterly_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = OverAllBankBalance::where('bankfinancialyear','=',$row['expenditure_financialyear'])
                        ->where('bank_quarterly','=',$row['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = OverAllBankBalance::where('bankfinancialyear','=',$row['expenditure_financialyear'])
                            ->where('bank_quarterly','=',$row['expenditure_quarter'])
                            ->update(
                                [
                                    'bank_quarterly_balance_amount'  =>  $row1->bank_quarterly_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = SumBankBalance::where('bankfinancialyear','=',$row['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = SumBankBalance::where('bankfinancialyear','=',$row['expenditure_financialyear'])

                            ->update(
                                [
                                    'bank_sum_balance_amount'  =>  $row1->bank_sum_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = HeadCategoriesBudget::where('headcategoryid','=',$row['headcategoryid'])
                        ->where('head_category_financial_year','=',$row['expenditure_financialyear'])
                        ->where('head_category_quarter','=',$row['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = HeadCategoriesBudget::where('headcategoryid','=',$row['headcategoryid'])
                            ->where('head_category_financial_year','=',$row['expenditure_financialyear'])
                            ->where('head_category_quarter','=',$row['expenditure_quarter'])
                            ->update(
                                [
                                    'head_category_budget_balance'  =>  $row1->head_category_budget_balance + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = HeadsBudget::where('headid','=',$row['headid'])
                        ->where('head_financial_year','=',$row['expenditure_financialyear'])
                        ->where('head_quarter','=',$row['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = HeadsBudget::where('headid','=',$row['headid'])
                            ->where('head_financial_year','=',$row['expenditure_financialyear'])
                            ->where('head_quarter','=',$row['expenditure_quarter'])
                            ->update(
                                [
                                    'head_budget_balance'  =>  $row1->head_budget_balance + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = MainHeadBudget::where('mainheadid','=',$row['mainheadid'])
                        ->where('main_head_financial_year','=',$row['expenditure_financialyear'])
                        ->where('main_head_quarter','=',$row['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = MainHeadBudget::where('mainheadid','=',$row['mainheadid'])
                            ->where('main_head_financial_year','=',$row['expenditure_financialyear'])
                            ->where('main_head_quarter','=',$row['expenditure_quarter'])
                            ->update(
                                [
                                    'main_head_budget_balance'  =>  $row1->main_head_budget_balance + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = SubHeadBudget::where('subheadid','=',$row['subheadid'])
                        ->where('sub_head_financial_year','=',$row['expenditure_financialyear'])
                        ->where('sub_head_quarter','=',$row['expenditure_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = SubHeadBudget::where('subheadid','=',$row['subheadid'])
                            ->where('sub_head_financial_year','=',$row['expenditure_financialyear'])
                            ->where('sub_head_quarter','=',$row['expenditure_quarter'])
                            ->update(
                                [
                                    'sub_head_budget_balance'  =>  $row1->sub_head_budget_balance + $row['net_amount'],
                                ]
                            );
                        }

                        // Manage balance

                        $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
                        ->where('head_category_financial_year','=',$row['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
                            ->where('head_category_financial_year','=',$row['expenditure_financialyear'])
                            ->update(
                                [
                                    'head_category_balance_amount'  =>  $row1->head_category_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = HeadsBalanace::where('headid','=',$row['headid'])
                        ->where('head_financial_year','=',$row['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = HeadsBalanace::where('headid','=',$row['headid'])
                            ->where('head_financial_year','=',$row['expenditure_financialyear'])
                            ->update(
                                [
                                    'head_balance_amount'  =>  $row1->head_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
                        ->where('main_head_financial_year','=',$row['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
                            ->where('main_head_financial_year','=',$row['expenditure_financialyear'])
                            ->update(
                                [
                                    'main_head_balance_amount'  =>  $row1->main_head_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
                        ->where('sub_head_financial_year','=',$row['expenditure_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
                            ->where('sub_head_financial_year','=',$row['expenditure_financialyear'])
                            ->update(
                                [
                                    'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount + $row['net_amount'],
                                ]
                            );
                        }
                    }
					Expenditures::where('autoexpenditureid', $deleteid)->delete();
                    
                    $ledger_delete = Ledger::where("expenditureid",'=',$deleteid);
                    $ledger_delete->delete();
				}
				return redirect('/expenditures')->with('success',"Deleted successfully");	
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
