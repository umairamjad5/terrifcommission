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
use App\Models\Reappropiations;
use App\Models\SubHead;
use App\Models\HeadCategoriesBalanace;
use App\Models\HeadsBalanace;
use App\Models\MainHeadsBalanace;
use App\Models\SubHeadsBalanace;

class ReappropiationsController extends Controller
{
    public function reappropiations()
    {
        if(Session::get('admin_id'))
        {
            $array = Reappropiations::get();
            return view('reappropiation.reappropiation',compact('array'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    public function action_reappropiations()
    {
        if(Session::get('admin_id'))
        {
            return view('reappropiation.action_reappropiations');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function reappropiation_db(Request $request)
    {
        $rules = [
            'from_head'  =>  'required|string',
            'to_head'  =>  'required|string',
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
				
                $data['reappropiation_financialyear'] = '2022 - 2023';
                // $thisyearmonths = date('m',time());
                
                // if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                // {
                //     $data['head_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                // }
                // else
                // {
                //     $data['head_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                // }
                $q = SubHead::where('autosubheadid',$data['from_head'])->first();
                $q21 = SubHead::where('autosubheadid',$data['to_head'])->first();
                if(!empty($q) AND !empty($q21))
                {
                    $q1 = HeadCategoriesBalanace::where('headcategoryid',$q->headcategoryid)->first();
                    $q2 = HeadsBalanace::where('headid',$q->headid)->first();
                    $q3 = MainHeadsBalanace::where('mainheadid',$q->mainheadid)->first();
                    $q4 = SubHeadsBalanace::where('subheadid',$q->autosubheadid)->first();

                    if($q4->sub_head_balance_amount > $data['amount'])
                    {
                        HeadCategoriesBalanace::where('headcategoryid',$q->headcategoryid)
                        ->update(
                            [
                                'head_category_balance_amount'  =>  $q1->head_category_balance_amount - $data['amount']
                            ]
                        );
                        HeadsBalanace::where('headid',$q->headid)
                        ->update(
                            [
                                'head_balance_amount'  =>  $q2->head_balance_amount - $data['amount']
                            ]
                        ); 
                        MainHeadsBalanace::where('mainheadid',$q->mainheadid)
                        ->update(
                            [
                                'main_head_balance_amount'  =>  $q3->main_head_balance_amount - $data['amount']
                            ]
                        ); 
                        SubHeadsBalanace::where('subheadid',$q->autosubheadid)
                        ->update(
                            [
                                'sub_head_balance_amount'  =>  $q4->sub_head_balance_amount - $data['amount']
                            ]
                        ); 

                        $q21 = SubHead::where('autosubheadid',$data['to_head'])->first();

                        $q11 = HeadCategoriesBalanace::where('headcategoryid',$q21->headcategoryid)->first();
                        $q12 = HeadsBalanace::where('headid',$q21->headid)->first();
                        $q13 = MainHeadsBalanace::where('mainheadid',$q21->mainheadid)->first();
                        $q14 = SubHeadsBalanace::where('subheadid',$q21->autosubheadid)->first();  
                        
                        HeadCategoriesBalanace::where('headcategoryid',$q21->headcategoryid)
                        ->update(
                            [
                                'head_category_balance_amount'  =>  $q11->head_category_balance_amount + $data['amount']
                            ]
                        ); 
                        HeadsBalanace::where('headid',$q21->headid)
                        ->update(
                            [
                                'head_balance_amount'  =>  $q12->head_balance_amount + $data['amount']
                            ]
                        ); 
                        MainHeadsBalanace::where('mainheadid',$q21->mainheadid)
                        ->update(
                            [
                                'main_head_balance_amount'  =>  $q13->main_head_balance_amount + $data['amount']
                            ]
                        ); 
                        SubHeadsBalanace::where('subheadid',$q21->autosubheadid)
                        ->update(
                            [
                                'sub_head_balance_amount'  =>  $q14->sub_head_balance_amount + $data['amount']
                            ]
                        ); 
                        
                        $array = new Reappropiations;
                        $array->reappropiation_financialyear = $data['reappropiation_financialyear'];
                        $array->reappropiation_quarter = $data['quarter'];
                        $array->fromheadcategoryid = $q->headcategoryid;
                        $array->fromheadid = $q->headid;
                        $array->frommainheadid = $q->mainheadid;
                        $array->fromsubheadid = $q->autosubheadid;
                        $array->toheadcategoryid = $q21->headcategoryid;
                        $array->toheadid = $q21->headid;
                        $array->tomainheadid = $q21->mainheadid;
                        $array->tosubheadid = $q21->autosubheadid;
                        $array->date = $data['date'];
                        $array->amount = $data['amount'];
                        $array->added_by = Session::get('admin_id');
        
                        $array->save();
                        
                        return redirect('/action_reappropiations')->with('success',"Insert Successfully");            
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

    public function update_reappropiation($id)
    {
        if(Session::get('admin_id'))
        {
            $array1 = Reappropiations::where('autoreappropiationid',$id)->get();
            return view('reappropiation.action_reappropiations',compact('array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_reappropiation_db(Request $request)
    {
        $rules = [
            'from_head'  =>  'required|string',
            'to_head'  =>  'required|string',
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
				
                $data['reappropiation_financialyear'] = '2022 - 2023';
                // $thisyearmonths = date('m',time());
                
                // if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                // {
                //     $data['head_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                // }
                // else
                // {
                //     $data['head_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                // }
                $q = SubHead::where('autosubheadid',$data['from_head'])->first();
                $q21 = SubHead::where('autosubheadid',$data['to_head'])->first();
                if(!empty($q) AND !empty($q21))
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
                                'head_category_balance_amount'  =>  $q1->head_category_balance_amount + $data['old_amount']
                            ]
                        );
                        HeadsBalanace::where('headid',$q->headid)
                        ->update(
                            [
                                'head_balance_amount'  =>  $q2->head_balance_amount + $data['old_amount']
                            ]
                        ); 
                        MainHeadsBalanace::where('mainheadid',$q->mainheadid)
                        ->update(
                            [
                                'main_head_balance_amount'  =>  $q3->main_head_balance_amount + $data['old_amount']
                            ]
                        ); 
                        SubHeadsBalanace::where('subheadid',$q->autosubheadid)
                        ->update(
                            [
                                'sub_head_balance_amount'  =>  $q4->sub_head_balance_amount + $data['old_amount']
                            ]
                        ); 

                        $q21 = SubHead::where('autosubheadid',$data['to_head'])->first();

                        $q11 = HeadCategoriesBalanace::where('headcategoryid',$q21->headcategoryid)->first();
                        $q12 = HeadsBalanace::where('headid',$q21->headid)->first();
                        $q13 = MainHeadsBalanace::where('mainheadid',$q21->mainheadid)->first();
                        $q14 = SubHeadsBalanace::where('subheadid',$q21->autosubheadid)->first();  
                        
                        HeadCategoriesBalanace::where('headcategoryid',$q21->headcategoryid)
                        ->update(
                            [
                                'head_category_balance_amount'  =>  $q11->head_category_balance_amount - $data['old_amount']
                            ]
                        ); 
                        HeadsBalanace::where('headid',$q21->headid)
                        ->update(
                            [
                                'head_balance_amount'  =>  $q12->head_balance_amount - $data['old_amount']
                            ]
                        ); 
                        MainHeadsBalanace::where('mainheadid',$q21->mainheadid)
                        ->update(
                            [
                                'main_head_balance_amount'  =>  $q13->main_head_balance_amount - $data['old_amount']
                            ]
                        ); 
                        SubHeadsBalanace::where('subheadid',$q21->autosubheadid)
                        ->update(
                            [
                                'sub_head_balance_amount'  =>  $q14->sub_head_balance_amount - $data['old_amount']
                            ]
                        ); 

                        $q31 = SubHead::where('autosubheadid',$data['from_head'])->first();

                        $q21 = HeadCategoriesBalanace::where('headcategoryid',$q31->headcategoryid)->first();
                        $q22 = HeadsBalanace::where('headid',$q31->headid)->first();
                        $q23 = MainHeadsBalanace::where('mainheadid',$q31->mainheadid)->first();
                        $q24 = SubHeadsBalanace::where('subheadid',$q31->autosubheadid)->first();  

                        HeadCategoriesBalanace::where('headcategoryid',$q31->headcategoryid)
                        ->update(
                            [
                                'head_category_balance_amount'  =>  $q21->head_category_balance_amount - $data['amount']
                            ]
                        );
                        HeadsBalanace::where('headid',$q31->headid)
                        ->update(
                            [
                                'head_balance_amount'  =>  $q22->head_balance_amount - $data['amount']
                            ]
                        ); 
                        MainHeadsBalanace::where('mainheadid',$q31->mainheadid)
                        ->update(
                            [
                                'main_head_balance_amount'  =>  $q23->main_head_balance_amount - $data['amount']
                            ]
                        ); 
                        SubHeadsBalanace::where('subheadid',$q31->autosubheadid)
                        ->update(
                            [
                                'sub_head_balance_amount'  =>  $q24->sub_head_balance_amount - $data['amount']
                            ]
                        ); 

                        $q41 = SubHead::where('autosubheadid',$data['to_head'])->first();

                        $q31 = HeadCategoriesBalanace::where('headcategoryid',$q41->headcategoryid)->first();
                        $q32 = HeadsBalanace::where('headid',$q41->headid)->first();
                        $q33 = MainHeadsBalanace::where('mainheadid',$q41->mainheadid)->first();
                        $q34 = SubHeadsBalanace::where('subheadid',$q41->autosubheadid)->first();  
                        
                        HeadCategoriesBalanace::where('headcategoryid',$q41->headcategoryid)
                        ->update(
                            [
                                'head_category_balance_amount'  =>  $q31->head_category_balance_amount + $data['amount']
                            ]
                        ); 
                        HeadsBalanace::where('headid',$q41->headid)
                        ->update(
                            [
                                'head_balance_amount'  =>  $q32->head_balance_amount + $data['amount']
                            ]
                        ); 
                        MainHeadsBalanace::where('mainheadid',$q41->mainheadid)
                        ->update(
                            [
                                'main_head_balance_amount'  =>  $q33->main_head_balance_amount + $data['amount']
                            ]
                        ); 
                        SubHeadsBalanace::where('subheadid',$q41->autosubheadid)
                        ->update(
                            [
                                'sub_head_balance_amount'  =>  $q34->sub_head_balance_amount + $data['amount']
                            ]
                        ); 
                        
                        $array = Reappropiations::where('autoreappropiationid',$data['autoreappropiationid'])
                        ->update([
                            'reappropiation_quarter'    =>  $data['quarter'],
                            'date'    =>  $data['date'],
                            'amount'    =>  $data['amount'],
                        ]);
                        
                        return redirect('/reappropiations')->with('success',"Updated Successfully");            
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

    public function delete_reappropiation($id)
    {
        if(Session::get('admin_id'))
        {
            $array = Reappropiations::where('autoreappropiationid',$id)->get();
            if(!empty($array))
            {
                foreach($array as $array);

                $q1 = SubHead::where('autosubheadid',$array->to_head)->first();

                $q11 = HeadCategoriesBalanace::where('headcategoryid',$q1->headcategoryid)->first();
                $q12 = HeadsBalanace::where('headid',$q1->headid)->first();
                $q13 = MainHeadsBalanace::where('mainheadid',$q1->mainheadid)->first();
                $q14 = SubHeadsBalanace::where('subheadid',$q1->autosubheadid)->first();  


                HeadCategoriesBalanace::where('headcategoryid',$q1->headcategoryid)
                ->update(
                    [
                        'head_category_balance_amount'  =>  $q11->head_category_balance_amount + $array->amount
                    ]
                );
                HeadsBalanace::where('headid',$q1->headid)
                ->update(
                    [
                        'head_balance_amount'  =>  $q12->head_balance_amount + $array->amount
                    ]
                ); 
                MainHeadsBalanace::where('mainheadid',$q1->mainheadid)
                ->update(
                    [
                        'main_head_balance_amount'  =>  $q13->main_head_balance_amount + $array->amount
                    ]
                ); 
                SubHeadsBalanace::where('subheadid',$q1->autosubheadid)
                ->update(
                    [
                        'sub_head_balance_amount'  =>  $q14->sub_head_balance_amount + $array->amount
                    ]
                ); 

                $q2 = SubHead::where('autosubheadid',$array->from_head)->first();

                $q21 = HeadCategoriesBalanace::where('headcategoryid',$q2->headcategoryid)->first();
                $q22 = HeadsBalanace::where('headid',$q2->headid)->first();
                $q23 = MainHeadsBalanace::where('mainheadid',$q2->mainheadid)->first();
                $q24 = SubHeadsBalanace::where('subheadid',$q2->autosubheadid)->first();  
                
                HeadCategoriesBalanace::where('headcategoryid',$q2->headcategoryid)
                ->update(
                    [
                        'head_category_balance_amount'  =>  $q21->head_category_balance_amount - $array->amount
                    ]
                ); 
                HeadsBalanace::where('headid',$q2->headid)
                ->update(
                    [
                        'head_balance_amount'  =>  $q22->head_balance_amount - $array->amount
                    ]
                ); 
                MainHeadsBalanace::where('mainheadid',$q2->mainheadid)
                ->update(
                    [
                        'main_head_balance_amount'  =>  $q23->main_head_balance_amount - $array->amount
                    ]
                ); 
                SubHeadsBalanace::where('subheadid',$q2->autosubheadid)
                ->update(
                    [
                        'sub_head_balance_amount'  =>  $q24->sub_head_balance_amount - $array->amount
                    ]
                ); 
            }
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }
}
