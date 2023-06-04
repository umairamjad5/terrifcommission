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
use App\Models\ThirdParty;
use App\Models\OverAllBudget;
use App\Models\QuarterlyBudget;
use App\Models\OverAllBankBalance;
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

class ThirdPartyController extends Controller
{
    function validate_heads_third_party()
    {
        if(Session::get('admin_id'))
        {
            return view('third_party.validate_heads_third_party');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function add_third_parties(Request $request)
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

                return redirect('/action_third_parties/'.$query->headcategoryid.'/'.$query->headid.'/'.$query->mainheadid.'/'.$query->autosubheadid);
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function third_parties()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = ThirdParty::join("head_categories","head_categories.autoheadcategoryid",'=',"third_parties.headcategoryid")
            ->join("heads","heads.autoheadid",'=',"third_parties.headid")
            ->join("main_heads","main_heads.automainheadid",'=',"third_parties.mainheadid")
            ->join("sub_heads","sub_heads.autosubheadid",'=',"third_parties.subheadid")
            ->orderby('third_parties.autothirdpartyid','asc')->get();
            return view('third_party.third_parties',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function action_third_parties($head_category,$head,$main_head,$sub_head)
    {
        if(Session::get('admin_id'))
        {
            return view('third_party.action_third_parties',compact('head_category','head','main_head','sub_head'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function third_party_db(Request $request)
    {
        $rules = [
            'bill_no'  =>  'required|string',
            'description'  =>  'required|string',
            'payable_to'  =>  'required|string',
            'gross_amount'  =>  'required|string',
            'gst'  =>  'required|string',
            'gst_ratio'  =>  'required|string',
            'income_tax'  =>  'required|string',
            'net_amount'  =>  'required|string',
            'balance'  =>  'required|string',
            'amount_date'  =>  'required|string',
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
                $data['third_party_financialyear'] = '';
                $data['third_party_quarter'] = '';
                $thisyearmonths = date('02',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['third_party_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['third_party_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                {
                    $data['third_party_quarter'] = '3rd Quarter';
                }
                if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                {
                    $data['third_party_quarter'] = '4th Quarter';
                }
                if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                {
                    $data['third_party_quarter'] = '1st Quarter';
                }
                if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                {
                    $data['third_party_quarter'] = '2nd Quarter';
                }
                
                $query_check = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                ->where('sub_head_financial_year','=',$data['third_party_financialyear'])
                ->where('sub_head_quarter','=',$data['third_party_quarter'])
                ->get();
                if(!empty($query_check))
                {
                    foreach($query_check as $query_check);
                    if($query_check->sub_head_budget_balance > $data['net_amount'])
                    {
                        $third_party = new ThirdParty;
                        $third_party->third_party_financialyear = $data['third_party_financialyear'];
                        $third_party->third_party_quarter = $data['third_party_quarter'];
                        $third_party->headcategoryid = $data['headcategoryid'];
                        $third_party->headid = $data['headid'];
                        $third_party->mainheadid = $data['mainheadid'];
                        $third_party->subheadid = $data['subheadid'];
                        $third_party->bill_no = $data['bill_no'];
                        $third_party->description = $data['description'];
                        $third_party->payable_to = $data['payable_to'];
                        $third_party->gross_amount = $data['gross_amount'];
                        $third_party->gst = $data['gst'];
                        $third_party->gst_ratio = $data['gst_ratio'];
                        $third_party->income_tax = $data['income_tax'];
                        $third_party->net_amount = $data['net_amount'];
                        $third_party->balance = $data['balance'];
                        $third_party->amount_date = $data['amount_date'];
                        $third_party->added_by = Session::get('admin_id');
                        
                        $third_party->save();
                        $last_id = $third_party->id;

                        $debit_ledger = new Ledger;
                        $debit_ledger->thirdpartyid = $last_id;
                        $debit_ledger->ledger_financialyear = $data['third_party_financialyear'];
                        $debit_ledger->ledger_quarter = $data['third_party_quarter'];
                        $debit_ledger->headcategoryid = $data['headcategoryid'];
                        $debit_ledger->headid = $data['headid'];
                        $debit_ledger->mainheadid = $data['mainheadid'];
                        $debit_ledger->subheadid = $data['subheadid'];
                        $debit_ledger->party_employee = $data['payable_to'];
                        $debit_ledger->particular = $data['description'];
                        $debit_ledger->debit_amount = $data['net_amount'];
                        $debit_ledger->incometax_amount = $data['income_tax'];
                        $debit_ledger->gst_amount = $data['gst'];
                        $debit_ledger->gst_ratio = $data['gst_ratio'];
                        $debit_ledger->balance_amount = $data['ledger_balance_amount'] - $data['net_amount'];
                        $debit_ledger->last_balance_amount = $data['ledger_balance_amount'];
                        $debit_ledger->debit_credit = 'Debit';
                        $debit_ledger->ledger_type = 'ThirdParty';
                        $debit_ledger->amount_date = $data['amount_date'];
                        $debit_ledger->added_by = Session::get('admin_id');                        
                        $debit_ledger->save();

                        $credit_ledger = new Ledger;
                        $credit_ledger->thirdpartyid = $last_id;
                        $credit_ledger->ledger_financialyear = $data['third_party_financialyear'];
                        $credit_ledger->ledger_quarter = $data['third_party_quarter'];
                        $credit_ledger->headcategoryid = $data['headcategoryid'];
                        $credit_ledger->headid = $data['headid'];
                        $credit_ledger->mainheadid = $data['mainheadid'];
                        $credit_ledger->subheadid = $data['subheadid'];
                        $credit_ledger->party_employee = $data['payable_to'];
                        $credit_ledger->particular = $data['description'];
                        $credit_ledger->credit_amount = $data['net_amount'];
                        $credit_ledger->incometax_amount = $data['income_tax'];
                        $credit_ledger->gst_amount = $data['gst'];
                        $credit_ledger->gst_ratio = $data['gst_ratio'];
                        $credit_ledger->balance_amount = $data['ledger_balance_amount'] - $data['net_amount'];
                        $credit_ledger->last_balance_amount = $data['ledger_balance_amount'];
                        $credit_ledger->debit_credit = 'Credit';
                        $credit_ledger->ledger_type = 'ThirdParty';
                        $credit_ledger->amount_date = $data['amount_date'];
                        $credit_ledger->added_by = Session::get('admin_id');                        
                        $credit_ledger->save();
        
                        $query1 = OverAllBudget::where('financialyear','=',$data['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = OverAllBudget::where('financialyear','=',$data['third_party_financialyear'])
                            ->update(
                                [
                                    'balance_amount'  =>  $row1->balance_amount - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = QuarterlyBudget::where('financialyear','=',$data['third_party_financialyear'])
                        ->where('quarterly','=',$data['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = QuarterlyBudget::where('financialyear','=',$data['third_party_financialyear'])
                            ->where('quarterly','=',$data['third_party_quarter'])
                            ->update(
                                [
                                    'quarterly_balance_amount'  =>  $row1->quarterly_balance_amount - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = OverAllBankBalance::where('bankfinancialyear','=',$data['third_party_financialyear'])
                        ->where('bank_quarterly','=',$data['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = OverAllBankBalance::where('bankfinancialyear','=',$data['third_party_financialyear'])
                            ->where('bank_quarterly','=',$data['third_party_quarter'])
                            ->update(
                                [
                                    'bank_quarterly_balance_amount'  =>  $row1->bank_quarterly_balance_amount - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = HeadCategoriesBudget::where('headcategoryid','=',$data['headcategoryid'])
                        ->where('head_category_financial_year','=',$data['third_party_financialyear'])
                        ->where('head_category_quarter','=',$data['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadCategoriesBudget::where('headcategoryid','=',$data['headcategoryid'])
                            ->where('head_category_financial_year','=',$data['third_party_financialyear'])
                            ->where('head_category_quarter','=',$data['third_party_quarter'])
                            ->update(
                                [
                                    'head_category_budget_balance'  =>  $row1->head_category_budget_balance - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = HeadsBudget::where('headid','=',$data['headid'])
                        ->where('head_financial_year','=',$data['third_party_financialyear'])
                        ->where('head_quarter','=',$data['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadsBudget::where('headid','=',$data['headid'])
                            ->where('head_financial_year','=',$data['third_party_financialyear'])
                            ->where('head_quarter','=',$data['third_party_quarter'])
                            ->update(
                                [
                                    'head_budget_balance'  =>  $row1->head_budget_balance - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = MainHeadBudget::where('mainheadid','=',$data['mainheadid'])
                        ->where('main_head_financial_year','=',$data['third_party_financialyear'])
                        ->where('main_head_quarter','=',$data['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = MainHeadBudget::where('mainheadid','=',$data['mainheadid'])
                            ->where('main_head_financial_year','=',$data['third_party_financialyear'])
                            ->where('main_head_quarter','=',$data['third_party_quarter'])
                            ->update(
                                [
                                    'main_head_budget_balance'  =>  $row1->main_head_budget_balance - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                        ->where('sub_head_financial_year','=',$data['third_party_financialyear'])
                        ->where('sub_head_quarter','=',$data['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                            ->where('sub_head_financial_year','=',$data['third_party_financialyear'])
                            ->where('sub_head_quarter','=',$data['third_party_quarter'])
                            ->update(
                                [
                                    'sub_head_budget_balance'  =>  $row1->sub_head_budget_balance - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$data['headcategoryid'])
                        ->where('head_category_financial_year','=',$data['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$data['headcategoryid'])
                            ->where('head_category_financial_year','=',$data['third_party_financialyear'])
                            ->update(
                                [
                                    'head_category_balance_amount'  =>  $row1->head_category_balance_amount - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = HeadsBalanace::where('headid','=',$data['headid'])
                        ->where('head_financial_year','=',$data['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadsBalanace::where('headid','=',$data['headid'])
                            ->where('head_financial_year','=',$data['third_party_financialyear'])
                            ->update(
                                [
                                    'head_balance_amount'  =>  $row1->head_balance_amount - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = MainHeadsBalanace::where('mainheadid','=',$data['mainheadid'])
                        ->where('main_head_financial_year','=',$data['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = MainHeadsBalanace::where('mainheadid','=',$data['mainheadid'])
                            ->where('main_head_financial_year','=',$data['third_party_financialyear'])
                            ->update(
                                [
                                    'main_head_balance_amount'  =>  $row1->main_head_balance_amount - $data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = SubHeadsBalanace::where('subheadid','=',$data['subheadid'])
                        ->where('sub_head_financial_year','=',$data['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = SubHeadsBalanace::where('subheadid','=',$data['subheadid'])
                            ->where('sub_head_financial_year','=',$data['third_party_financialyear'])
                            ->update(
                                [
                                    'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount - $data['net_amount'],
                                ]
                            );
                        }

                        // return back()->with('success',"Insert Successfully");
                        return redirect('/action_third_parties/'.$data['headcategoryid'].'/'.$data['headid'].'/'.$data['mainheadid'].'/'.$data['subheadid'])->with('success',"Insert Successfully");
                        return redirect('/third_parties')->with('success',"Insert Successfully");
                    }
                    else
                    {
                        return back()->with('error',"Budget Amount exceed Limit");
                    }
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_third_party($autothirdpartyid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = ThirdParty::orderby('third_parties.autothirdpartyid','asc')->get();
            $array1 = ThirdParty::where("autothirdpartyid",'=',$autothirdpartyid)
            ->get();
            // dd($array);        
            return view('third_party.action_third_parties',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_third_party_db(Request $request)
    {
        $rules = [
            'bill_no'  =>  'required|string',
            'description'  =>  'required|string',
            'payable_to'  =>  'required|string',
            'gross_amount'  =>  'required|string',
            'gst'  =>  'required|string',
            'gst_ratio'  =>  'required|string',
            'income_tax'  =>  'required|string',
            'net_amount'  =>  'required|string',
            'balance'  =>  'required|string',
            'amount_date'  =>  'required|string',
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
                $data['third_party_financialyear'] = '';
                $data['third_party_quarter'] = '';
                $thisyearmonths = date('02',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['third_party_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['third_party_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 03)
                {
                    $data['third_party_quarter'] = '3rd Quarter';
                }
                if($thisyearmonths >= 04 AND $thisyearmonths <= 06)
                {
                    $data['third_party_quarter'] = '4th Quarter';
                }
                if($thisyearmonths >= 07 AND $thisyearmonths < 10)
                {
                    $data['third_party_quarter'] = '1st Quarter';
                }
                if($thisyearmonths >= 10 AND $thisyearmonths <= 12)
                {
                    $data['third_party_quarter'] = '2nd Quarter';
                }
                
                $query_check = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                ->where('sub_head_financial_year','=',$data['third_party_financialyear'])
                ->where('sub_head_quarter','=',$data['third_party_quarter'])
                ->get();
                if(!empty($query_check))
                {
                    foreach($query_check as $query_check);
                    $checkamount = $data['old_amount'] + $data['net_amount'];
                    if($query_check->sub_head_budget_balance > $checkamount)
                    {
                        $third_party = ThirdParty::where("autothirdpartyid",'=',$data['autothirdpartyid'])->update(
                            [
                                'third_party_financialyear' => $data['third_party_financialyear'],
                                'third_party_quarter' => $data['third_party_quarter'],
                                'headcategoryid' => $data['headcategoryid'],
                                'headid' => $data['headid'],
                                'mainheadid' => $data['mainheadid'],
                                'subheadid' => $data['subheadid'],
                                'bill_no' => $data['bill_no'],
                                'description' => $data['description'],
                                'payable_to' => $data['payable_to'],
                                'gross_amount' => $data['gross_amount'],
                                'gst' => $data['gst'],
                                'gst_ratio' => $data['gst_ratio'],
                                'income_tax' => $data['income_tax'],
                                'net_amount' => $data['net_amount'],
                                'balance' => $data['balance'],
                                'amount_date' => $data['amount_date'],
                                'updated_by' => Session::get('admin_id'),
                            ]
                        );

                        $debit_ledger = Ledger::where("autoledgerid",'=',$data['debitautoledgerid'])
                        ->where('debit_credit','=','Debit')
                        ->update(
                            [
                                'party_employee' => $data['payable_to'],
                                'particular' => $data['description'],
                                'debit_amount' => $data['net_amount'],
                                'incometax_amount' => $data['income_tax'],
                                'gst_amount' => $data['gst'],
                                'gst_ratio' => $data['gst_ratio'],
                                'balance_amount' => $data['ledger_balance_amount'] - $data['net_amount'],
                                'last_balance_amount' => $data['ledger_balance_amount'],
                                'amount_date' => $data['amount_date'],
                                'updated_by' => Session::get('admin_id'),
                            ]
                        );

                        $credit_ledger = Ledger::where("autoledgerid",'=',$data['debitautoledgerid'])
                        ->where('debit_credit','=','Credit')
                        ->update(
                            [
                                'party_employee' => $data['payable_to'],
                                'particular' => $data['description'],
                                'credit_amount' => $data['net_amount'],
                                'incometax_amount' => $data['income_tax'],
                                'gst_amount' => $data['gst'],
                                'gst_ratio' => $data['gst_ratio'],
                                'balance_amount' => $data['ledger_balance_amount'] - $data['net_amount'],
                                'last_balance_amount' => $data['ledger_balance_amount'],
                                'amount_date' => $data['amount_date'],
                                'updated_by' => Session::get('admin_id'),
                            ]
                        );
        
                        $query1 = OverAllBudget::where('financialyear','=',$data['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = OverAllBudget::where('financialyear','=',$data['third_party_financialyear'])
                            ->update(
                                [
                                    'balance_amount'  =>  $row1->balance_amount + $data['old_amount'] -$data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = QuarterlyBudget::where('financialyear','=',$data['third_party_financialyear'])
                        ->where('quarterly','=',$data['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = QuarterlyBudget::where('financialyear','=',$data['third_party_financialyear'])
                            ->where('quarterly','=',$data['third_party_quarter'])
                            ->update(
                                [
                                    'quarterly_balance_amount'  =>  $row1->quarterly_balance_amount + $data['old_amount'] -$data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = OverAllBankBalance::where('bankfinancialyear','=',$data['third_party_financialyear'])
                        ->where('bank_quarterly','=',$data['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = OverAllBankBalance::where('bankfinancialyear','=',$data['third_party_financialyear'])
                            ->where('bank_quarterly','=',$data['third_party_quarter'])
                            ->update(
                                [
                                    'bank_quarterly_balance_amount'  =>  $row1->bank_quarterly_balance_amount + $data['old_amount'] -$data['net_amount'],
                                ]
                            );
                        }
        
                        $query1 = HeadCategoriesBudget::where('headcategoryid','=',$data['headcategoryid'])
                        ->where('head_category_financial_year','=',$data['third_party_financialyear'])
                        ->where('head_category_quarter','=',$data['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadCategoriesBudget::where('headcategoryid','=',$data['headcategoryid'])
                            ->where('head_category_financial_year','=',$data['third_party_financialyear'])
                            ->where('head_category_quarter','=',$data['third_party_quarter'])
                            ->update(
                                [
                                    'head_category_budget_balance'  =>  $row1->head_category_budget_balance + $data['old_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
        
                        $query1 = HeadsBudget::where('headid','=',$data['headid'])
                        ->where('head_financial_year','=',$data['third_party_financialyear'])
                        ->where('head_quarter','=',$data['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadsBudget::where('headid','=',$data['headid'])
                            ->where('head_financial_year','=',$data['third_party_financialyear'])
                            ->where('head_quarter','=',$data['third_party_quarter'])
                            ->update(
                                [
                                    'head_budget_balance'  =>  $row1->head_budget_balance + $data['old_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
        
                        $query1 = MainHeadBudget::where('mainheadid','=',$data['mainheadid'])
                        ->where('main_head_financial_year','=',$data['third_party_financialyear'])
                        ->where('main_head_quarter','=',$data['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = MainHeadBudget::where('mainheadid','=',$data['mainheadid'])
                            ->where('main_head_financial_year','=',$data['third_party_financialyear'])
                            ->where('main_head_quarter','=',$data['third_party_quarter'])
                            ->update(
                                [
                                    'main_head_budget_balance'  =>  $row1->main_head_budget_balance + $data['old_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
        
                        $query1 = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                        ->where('sub_head_financial_year','=',$data['third_party_financialyear'])
                        ->where('sub_head_quarter','=',$data['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = SubHeadBudget::where('subheadid','=',$data['subheadid'])
                            ->where('sub_head_financial_year','=',$data['third_party_financialyear'])
                            ->where('sub_head_quarter','=',$data['third_party_quarter'])
                            ->update(
                                [
                                    'sub_head_budget_balance'  =>  $row1->sub_head_budget_balance + $data['old_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }

                        // Manage Balances

                        $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$data['headcategoryid'])
                        ->where('head_category_financial_year','=',$data['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$data['headcategoryid'])
                            ->where('head_category_financial_year','=',$data['third_party_financialyear'])
                            ->update(
                                [
                                    'head_category_balance_amount'  =>  $row1->head_category_balance_amount + $data['old_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
        
                        $query1 = HeadsBalanace::where('headid','=',$data['headid'])
                        ->where('head_financial_year','=',$data['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = HeadsBalanace::where('headid','=',$data['headid'])
                            ->where('head_financial_year','=',$data['third_party_financialyear'])
                            ->update(
                                [
                                    'head_balance_amount'  =>  $row1->head_balance_amount + $data['old_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
        
                        $query1 = MainHeadsBalanace::where('mainheadid','=',$data['mainheadid'])
                        ->where('main_head_financial_year','=',$data['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = MainHeadsBalanace::where('mainheadid','=',$data['mainheadid'])
                            ->where('main_head_financial_year','=',$data['third_party_financialyear'])
                            ->update(
                                [
                                    'main_head_balance_amount'  =>  $row1->main_head_balance_amount + $data['old_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
        
                        $query1 = SubHeadsBalanace::where('subheadid','=',$data['subheadid'])
                        ->where('sub_head_financial_year','=',$data['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);
        
                            $update_query1 = SubHeadsBalanace::where('subheadid','=',$data['subheadid'])
                            ->where('sub_head_financial_year','=',$data['third_party_financialyear'])
                            ->update(
                                [
                                    'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount + $data['old_amount'] -$data['net_amount'],
                                ]
                            );
        
                        }
                    }
                }

				return redirect('/third_parties')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_third_party($autothirdpartyid)
    {
        if(Session::get('admin_id'))
        {
            $query = ThirdParty::where('autothirdpartyid','=',$autothirdpartyid)
            ->get();
            if(!empty($query))
            {
                foreach($query as $row);

                $query1 = OverAllBudget::where('financialyear','=',$row['third_party_financialyear'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = OverAllBudget::where('financialyear','=',$row['third_party_financialyear'])
                    ->update(
                        [
                            'balance_amount'  =>  $row1->balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = QuarterlyBudget::where('financialyear','=',$row['third_party_financialyear'])
                ->where('quarterly','=',$row['third_party_quarter'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = QuarterlyBudget::where('financialyear','=',$row['third_party_financialyear'])
                    ->where('quarterly','=',$row['third_party_quarter'])
                    ->update(
                        [
                            'quarterly_balance_amount'  =>  $row1->quarterly_balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = OverAllBankBalance::where('bankfinancialyear','=',$row['third_party_financialyear'])
                ->where('bank_quarterly','=',$row['third_party_quarter'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = OverAllBankBalance::where('bankfinancialyear','=',$row['third_party_financialyear'])
                    ->where('bank_quarterly','=',$row['third_party_quarter'])
                    ->update(
                        [
                            'bank_quarterly_balance_amount'  =>  $row1->bank_quarterly_balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = HeadCategoriesBudget::where('headcategoryid','=',$row['headcategoryid'])
                ->where('head_category_financial_year','=',$row['third_party_financialyear'])
                ->where('head_category_quarter','=',$row['third_party_quarter'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = HeadCategoriesBudget::where('headcategoryid','=',$row['headcategoryid'])
                    ->where('head_category_financial_year','=',$row['third_party_financialyear'])
                    ->where('head_category_quarter','=',$row['third_party_quarter'])
                    ->update(
                        [
                            'head_category_budget_balance'  =>  $row1->head_category_budget_balance + $row['net_amount'],
                        ]
                    );
                }

                $query1 = HeadsBudget::where('headid','=',$row['headid'])
                ->where('head_financial_year','=',$row['third_party_financialyear'])
                ->where('head_quarter','=',$row['third_party_quarter'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = HeadsBudget::where('headid','=',$row['headid'])
                    ->where('head_financial_year','=',$row['third_party_financialyear'])
                    ->where('head_quarter','=',$row['third_party_quarter'])
                    ->update(
                        [
                            'head_budget_balance'  =>  $row1->head_budget_balance + $row['net_amount'],
                        ]
                    );
                }

                $query1 = MainHeadBudget::where('mainheadid','=',$row['mainheadid'])
                ->where('main_head_financial_year','=',$row['third_party_financialyear'])
                ->where('main_head_quarter','=',$row['third_party_quarter'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = MainHeadBudget::where('mainheadid','=',$row['mainheadid'])
                    ->where('main_head_financial_year','=',$row['third_party_financialyear'])
                    ->where('main_head_quarter','=',$row['third_party_quarter'])
                    ->update(
                        [
                            'main_head_budget_balance'  =>  $row1->main_head_budget_balance + $row['net_amount'],
                        ]
                    );
                }

                $query1 = SubHeadBudget::where('subheadid','=',$row['subheadid'])
                ->where('sub_head_financial_year','=',$row['third_party_financialyear'])
                ->where('sub_head_quarter','=',$row['third_party_quarter'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = SubHeadBudget::where('subheadid','=',$row['subheadid'])
                    ->where('sub_head_financial_year','=',$row['third_party_financialyear'])
                    ->where('sub_head_quarter','=',$row['third_party_quarter'])
                    ->update(
                        [
                            'sub_head_budget_balance'  =>  $row1->sub_head_budget_balance + $row['net_amount'],
                        ]
                    );
                }

                // Manage balance

                $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
                ->where('head_category_financial_year','=',$row['third_party_financialyear'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
                    ->where('head_category_financial_year','=',$row['third_party_financialyear'])
                    ->update(
                        [
                            'head_category_balance_amount'  =>  $row1->head_category_balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = HeadsBalanace::where('headid','=',$row['headid'])
                ->where('head_financial_year','=',$row['third_party_financialyear'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = HeadsBalanace::where('headid','=',$row['headid'])
                    ->where('head_financial_year','=',$row['third_party_financialyear'])
                    ->update(
                        [
                            'head_balance_amount'  =>  $row1->head_balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
                ->where('main_head_financial_year','=',$row['third_party_financialyear'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
                    ->where('main_head_financial_year','=',$row['third_party_financialyear'])
                    ->update(
                        [
                            'main_head_balance_amount'  =>  $row1->main_head_balance_amount + $row['net_amount'],
                        ]
                    );
                }

                $query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
                ->where('sub_head_financial_year','=',$row['third_party_financialyear'])
                ->get();
                if(!empty($query1))
                {
                    foreach($query1 as $row1);

                    $update_query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
                    ->where('sub_head_financial_year','=',$row['third_party_financialyear'])
                    ->update(
                        [
                            'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount + $row['net_amount'],
                        ]
                    );
                }
            }

            $third_party_delete = ThirdParty::where("autothirdpartyid",'=',$autothirdpartyid);
            $third_party_delete->delete();

            $ledger_delete = Ledger::where("thirdpartyid",'=',$autothirdpartyid);
            $ledger_delete->delete();

            return redirect('/third_parties')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_third_parties(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
                    $query = ThirdParty::where('autothirdpartyid','=',$deleteid)
                    ->get();
                    if(!empty($query))
                    {
                        foreach($query as $row);

                        $query1 = OverAllBudget::where('financialyear','=',$row['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = OverAllBudget::where('financialyear','=',$row['third_party_financialyear'])
                            ->update(
                                [
                                    'balance_amount'  =>  $row1->balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = QuarterlyBudget::where('financialyear','=',$row['third_party_financialyear'])
                        ->where('quarterly','=',$row['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = QuarterlyBudget::where('financialyear','=',$row['third_party_financialyear'])
                            ->where('quarterly','=',$row['third_party_quarter'])
                            ->update(
                                [
                                    'quarterly_balance_amount'  =>  $row1->quarterly_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = OverAllBankBalance::where('bankfinancialyear','=',$row['third_party_financialyear'])
                        ->where('bank_quarterly','=',$row['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = OverAllBankBalance::where('bankfinancialyear','=',$row['third_party_financialyear'])
                            ->where('bank_quarterly','=',$row['third_party_quarter'])
                            ->update(
                                [
                                    'bank_quarterly_balance_amount'  =>  $row1->bank_quarterly_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = HeadCategoriesBudget::where('headcategoryid','=',$row['headcategoryid'])
                        ->where('head_category_financial_year','=',$row['third_party_financialyear'])
                        ->where('head_category_quarter','=',$row['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = HeadCategoriesBudget::where('headcategoryid','=',$row['headcategoryid'])
                            ->where('head_category_financial_year','=',$row['third_party_financialyear'])
                            ->where('head_category_quarter','=',$row['third_party_quarter'])
                            ->update(
                                [
                                    'head_category_budget_balance'  =>  $row1->head_category_budget_balance + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = HeadsBudget::where('headid','=',$row['headid'])
                        ->where('head_financial_year','=',$row['third_party_financialyear'])
                        ->where('head_quarter','=',$row['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = HeadsBudget::where('headid','=',$row['headid'])
                            ->where('head_financial_year','=',$row['third_party_financialyear'])
                            ->where('head_quarter','=',$row['third_party_quarter'])
                            ->update(
                                [
                                    'head_budget_balance'  =>  $row1->head_budget_balance + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = MainHeadBudget::where('mainheadid','=',$row['mainheadid'])
                        ->where('main_head_financial_year','=',$row['third_party_financialyear'])
                        ->where('main_head_quarter','=',$row['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = MainHeadBudget::where('mainheadid','=',$row['mainheadid'])
                            ->where('main_head_financial_year','=',$row['third_party_financialyear'])
                            ->where('main_head_quarter','=',$row['third_party_quarter'])
                            ->update(
                                [
                                    'main_head_budget_balance'  =>  $row1->main_head_budget_balance + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = SubHeadBudget::where('subheadid','=',$row['subheadid'])
                        ->where('sub_head_financial_year','=',$row['third_party_financialyear'])
                        ->where('sub_head_quarter','=',$row['third_party_quarter'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = SubHeadBudget::where('subheadid','=',$row['subheadid'])
                            ->where('sub_head_financial_year','=',$row['third_party_financialyear'])
                            ->where('sub_head_quarter','=',$row['third_party_quarter'])
                            ->update(
                                [
                                    'sub_head_budget_balance'  =>  $row1->sub_head_budget_balance + $row['net_amount'],
                                ]
                            );
                        }

// Manage balance

                        $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
                        ->where('head_category_financial_year','=',$row['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
                            ->where('head_category_financial_year','=',$row['third_party_financialyear'])
                            ->update(
                                [
                                    'head_category_balance_amount'  =>  $row1->head_category_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = HeadsBalanace::where('headid','=',$row['headid'])
                        ->where('head_financial_year','=',$row['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = HeadsBalanace::where('headid','=',$row['headid'])
                            ->where('head_financial_year','=',$row['third_party_financialyear'])
                            ->update(
                                [
                                    'head_balance_amount'  =>  $row1->head_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
                        ->where('main_head_financial_year','=',$row['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
                            ->where('main_head_financial_year','=',$row['third_party_financialyear'])
                            ->update(
                                [
                                    'main_head_balance_amount'  =>  $row1->main_head_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
                        ->where('sub_head_financial_year','=',$row['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
                            ->where('sub_head_financial_year','=',$row['third_party_financialyear'])
                            ->update(
                                [
                                    'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount + $row['net_amount'],
                                ]
                            );
                        }
                        
                        // Manage balance

                        $query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
                        ->where('head_category_financial_year','=',$row['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = HeadCategoriesBalanace::where('headcategoryid','=',$row['headcategoryid'])
                            ->where('head_category_financial_year','=',$row['third_party_financialyear'])
                            ->update(
                                [
                                    'head_category_balance_amount'  =>  $row1->head_category_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = HeadsBalanace::where('headid','=',$row['headid'])
                        ->where('head_financial_year','=',$row['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = HeadsBalanace::where('headid','=',$row['headid'])
                            ->where('head_financial_year','=',$row['third_party_financialyear'])
                            ->update(
                                [
                                    'head_balance_amount'  =>  $row1->head_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
                        ->where('main_head_financial_year','=',$row['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = MainHeadsBalanace::where('mainheadid','=',$row['mainheadid'])
                            ->where('main_head_financial_year','=',$row['third_party_financialyear'])
                            ->update(
                                [
                                    'main_head_balance_amount'  =>  $row1->main_head_balance_amount + $row['net_amount'],
                                ]
                            );
                        }

                        $query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
                        ->where('sub_head_financial_year','=',$row['third_party_financialyear'])
                        ->get();
                        if(!empty($query1))
                        {
                            foreach($query1 as $row1);

                            $update_query1 = SubHeadsBalanace::where('subheadid','=',$row['subheadid'])
                            ->where('sub_head_financial_year','=',$row['third_party_financialyear'])
                            ->update(
                                [
                                    'sub_head_balance_amount'  =>  $row1->sub_head_balance_amount + $row['net_amount'],
                                ]
                            );
                        }
                    }
					ThirdParty::where('autothirdpartyid', $deleteid)->delete();
					Ledger::where('thirdpartyid', $deleteid)->delete();
				}
				return redirect('/third_parties')->with('success',"Deleted successfully");	
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

    function invoice_third_party($autothirdpartyid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = ThirdParty::orderby('third_parties.autothirdpartyid','asc')->get();
            $array1 = ThirdParty::where("autothirdpartyid",'=',$autothirdpartyid)
            ->get();
            // dd($array);        
            return view('third_party.invoice_third_party',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }
}
