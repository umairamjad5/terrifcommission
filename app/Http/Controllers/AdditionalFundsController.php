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
use App\Models\AdditionalFunds;
use App\Models\SubHead;
use App\Models\HeadCategoriesBalanace;
use App\Models\HeadsBalanace;
use App\Models\MainHeadsBalanace;
use App\Models\SubHeadsBalanace;

class AdditionalFundsController extends Controller
{
    public function additional_funds()
    {
        if(Session::get('admin_id'))
        {
            $array = AdditionalFunds::get();
            return view('additional_funds.additional_funds',compact('array'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    public function action_additional_funds()
    {
        if(Session::get('admin_id'))
        {
            return view('additional_funds.action_additional_funds');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function additional_fund_db(Request $request)
    {
        $rules = [
            'head'  =>  'required|string',
            'quarter'  =>  'required|string',
            'date'  =>  'required|string',
            'amount'  =>  'required|string',
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
				
                $data['additionalfund_financialyear'] = '2022 - 2023';
                // $thisyearmonths = date('m',time());
                
                // if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                // {
                //     $data['head_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                // }
                // else
                // {
                //     $data['head_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                // }
                $q = SubHead::where('autosubheadid',$data['head'])->first();
                if(!empty($q))
                {
                    $q1 = HeadCategoriesBalanace::where('headcategoryid',$q->headcategoryid)->first();
                    $q2 = HeadsBalanace::where('headid',$q->headid)->first();
                    $q3 = MainHeadsBalanace::where('mainheadid',$q->mainheadid)->first();
                    $q4 = SubHeadsBalanace::where('subheadid',$q->autosubheadid)->first();

                    HeadCategoriesBalanace::where('headcategoryid',$q->headcategoryid)
                    ->update(
                        [
                            'head_category_balance_amount'  =>  $q1->head_category_balance_amount + $data['amount']
                        ]
                    );
                    HeadsBalanace::where('headid',$q->headid)
                    ->update(
                        [
                            'head_balance_amount'  =>  $q2->head_balance_amount + $data['amount']
                        ]
                    ); 
                    MainHeadsBalanace::where('mainheadid',$q->mainheadid)
                    ->update(
                        [
                            'main_head_balance_amount'  =>  $q3->main_head_balance_amount + $data['amount']
                        ]
                    ); 
                    SubHeadsBalanace::where('subheadid',$q->autosubheadid)
                    ->update(
                        [
                            'sub_head_balance_amount'  =>  $q4->sub_head_balance_amount + $data['amount']
                        ]
                    ); 

                    
                    $array = new AdditionalFunds;
                    $array->additionalfund_financialyear = $data['additionalfund_financialyear'];
                    $array->additionalfund_quarter = $data['quarter'];
                    $array->headcategoryid = $q->headcategoryid;
                    $array->headid = $q->headid;
                    $array->mainheadid = $q->mainheadid;
                    $array->subheadid = $q->autosubheadid;
                    $array->date = $data['date'];
                    $array->amount = $data['amount'];
                    $array->added_by = Session::get('admin_id');
    
                    $array->save();
                    
                    return redirect('/action_additional_funds')->with('success',"Insert Successfully");  
                }
                else
                {
                    return back()->with('error',"Error in Head");
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    public function update_additional_fund($id)
    {
        if(Session::get('admin_id'))
        {
            $array1 = AdditionalFunds::where('autoadditionalfundid',$id)->get();
            return view('additional_funds.action_additional_funds',compact('array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_additional_fund_db(Request $request)
    {
        $rules = [
            'head'  =>  'required|string',
            'quarter'  =>  'required|string',
            'date'  =>  'required|string',
            'amount'  =>  'required|string',
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
				
                $data['additionalfund_financialyear'] = '2022 - 2023';
                // $thisyearmonths = date('m',time());
                
                // if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                // {
                //     $data['head_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                // }
                // else
                // {
                //     $data['head_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                // }
                $q = SubHead::where('autosubheadid',$data['head'])->first();
                if(!empty($q))
                {
                    $q1 = HeadCategoriesBalanace::where('headcategoryid',$q->headcategoryid)->first();
                    $q2 = HeadsBalanace::where('headid',$q->headid)->first();
                    $q3 = MainHeadsBalanace::where('mainheadid',$q->mainheadid)->first();
                    $q4 = SubHeadsBalanace::where('subheadid',$q->autosubheadid)->first();

                    $val = $q4->sub_head_balance_amount + $data['old_amount'];

                    if($val > $data['amount'])
                    {
                        HeadCategoriesBalanace::where('headcategoryid',$q->headcategoryid)
                        ->update(
                            [
                                'head_category_balance_amount'  =>  $q1->head_category_balance_amount - $data['old_amount']
                            ]
                        );
                        HeadsBalanace::where('headid',$q->headid)
                        ->update(
                            [
                                'head_balance_amount'  =>  $q2->head_balance_amount - $data['old_amount']
                            ]
                        ); 
                        MainHeadsBalanace::where('mainheadid',$q->mainheadid)
                        ->update(
                            [
                                'main_head_balance_amount'  =>  $q3->main_head_balance_amount - $data['old_amount']
                            ]
                        ); 
                        SubHeadsBalanace::where('subheadid',$q->autosubheadid)
                        ->update(
                            [
                                'sub_head_balance_amount'  =>  $q4->sub_head_balance_amount - $data['old_amount']
                            ]
                        );

                        $q31 = SubHead::where('autosubheadid',$data['head'])->first();

                        $q21 = HeadCategoriesBalanace::where('headcategoryid',$q31->headcategoryid)->first();
                        $q22 = HeadsBalanace::where('headid',$q31->headid)->first();
                        $q23 = MainHeadsBalanace::where('mainheadid',$q31->mainheadid)->first();
                        $q24 = SubHeadsBalanace::where('subheadid',$q31->autosubheadid)->first();  

                        HeadCategoriesBalanace::where('headcategoryid',$q31->headcategoryid)
                        ->update(
                            [
                                'head_category_balance_amount'  =>  $q21->head_category_balance_amount + $data['amount']
                            ]
                        );
                        HeadsBalanace::where('headid',$q31->headid)
                        ->update(
                            [
                                'head_balance_amount'  =>  $q22->head_balance_amount + $data['amount']
                            ]
                        ); 
                        MainHeadsBalanace::where('mainheadid',$q31->mainheadid)
                        ->update(
                            [
                                'main_head_balance_amount'  =>  $q23->main_head_balance_amount + $data['amount']
                            ]
                        ); 
                        SubHeadsBalanace::where('subheadid',$q31->autosubheadid)
                        ->update(
                            [
                                'sub_head_balance_amount'  =>  $q24->sub_head_balance_amount + $data['amount']
                            ]
                        ); 
                        
                        $array = AdditionalFunds::where('autoadditionalfundid',$data['autoadditionalfundid'])
                        ->update([
                            'additionalfund_quarter'    =>  $data['quarter'],
                            'date'    =>  $data['date'],
                            'amount'    =>  $data['amount'],
                        ]);
                        
                        return redirect('/additional_funds')->with('success',"Updated Successfully");            
                    }
                    else
                    {
                        return back()->with('error',"Error in Budget Head");
                    }
                }
                else
                {
                    return back()->with('error',"Error in Head");
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }
}
