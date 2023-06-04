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
use App\Models\HeadCategoriesBudget;
use App\Models\HeadsBudget;
use App\Models\MainHeadBudget;
use App\Models\SubHeadBudget;
use App\Models\HeadCategoriesBalanace;
use App\Models\HeadsBalanace;
use App\Models\MainHeadsBalanace;
use App\Models\SubHeadsBalanace;

class HeadWiseBudgetController extends Controller
{
    function head_categories_budgets()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = HeadCategoriesBudget::join("head_categories","head_categories.autoheadcategoryid",'=',"head_categories_budgets.headcategoryid")
            ->orderby('head_categories_budgets.autoheadcategorybudgetid','asc')->get();
            return view('head_wise_budget.head_categories_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function head_category_db_budget(Request $request)
    {
        $rules = [
            'head_category'  =>  'required|string',
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
                
                $query = HeadCategoriesBudget::where('headcategoryid','=',$data['head_category'])
                ->where('head_category_financial_year','=',$data['financial_year'])
                ->where('head_category_quarter','=',$data['quarter'])
                ->get();
                // dd($query);
                if(count($query) == 1)
                {
                    return back()->with('error',"Selected Head Category in ".$data['quarter']." of this Financial Year is already Exist!");
                }
                else
                {
                    $head_category = new HeadCategoriesBudget;
                    $head_category->headcategoryid = $data['head_category'];
                    $head_category->head_category_financial_year = $data['financial_year'];
                    $head_category->head_category_quarter = $data['quarter'];
                    $head_category->head_category_budget_total_amount = $data['total_amount'];
                    $head_category->head_category_budget_amount = $data['amount'];
                    $head_category->head_category_budget_balance = $data['amount'];
                    $head_category->added_by = Session::get('admin_id');
    
                    $head_category->save();
    
                    $query = HeadCategoriesBalanace::where('head_category_financial_year','=',$data['financial_year'])
                    ->where('headcategoryid','=',$data['head_category'])
                    ->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);
                        // echo '1';exit;
                        $query1 = HeadCategoriesBalanace::where('head_category_financial_year','=',$data['financial_year'])
                        ->where('headcategoryid','=',$data['head_category'])
                        ->update(
                            [
                                'head_category_budget_total_amount' => $data['total_amount'],
                                'head_category_total_amount' => $query->head_category_total_amount + $data['amount'],
                                'head_category_balance_amount' => $query->head_category_balance_amount + $data['amount'],
                            ]
                        );
                    }
                    else
                    {
                        // echo '2';exit;
                        $bank_balance = new HeadCategoriesBalanace;
                        $bank_balance->headcategoryid = $data['head_category'];
                        $bank_balance->head_category_financial_year = $data['financial_year'];
                        $bank_balance->head_category_budget_total_amount = $data['total_amount'];
                        $bank_balance->head_category_total_amount = $data['amount'];
                        $bank_balance->head_category_balance_amount = $data['amount'];
                        $bank_balance->added_by = Session::get('admin_id');

                        $bank_balance->save();
                    }

                    return redirect('/head_categories_budgets')->with('success',"Insert Successfully");
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_head_category_budget($autoheadcategorybudgetid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = HeadCategoriesBudget::join("head_categories","head_categories.autoheadcategoryid",'=',"head_categories_budgets.headcategoryid")
            ->orderby('head_categories_budgets.autoheadcategorybudgetid','asc')->get();
            $array1 = HeadCategoriesBudget::where("autoheadcategorybudgetid",'=',$autoheadcategorybudgetid)
            ->get();
            // dd($array);        
            return view('head_wise_budget.head_categories_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_head_category_db_budget(Request $request)
    {
        $rules = [
            'head_category'  =>  'required|string',
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
                if($data['amount'] > $data['diff_amount'])
                {
                    $head_category = HeadCategoriesBudget::where("autoheadcategorybudgetid",'=',$data['autoheadcategorybudgetid'])->update(
                        [
                            'headcategoryid' => $data['head_category'],
                            'head_category_budget_total_amount' => $data['total_amount'],
                            'head_category_budget_amount' => $data['amount'],
                            'head_category_budget_balance' => $data['amount'] - $data['diff_amount'],
                            'updated_by' => Session::get('admin_id'),
                        ]
                    );
                    $query = HeadCategoriesBalanace::where('head_category_financial_year','=',$data['financial_year'])
                    ->where('headcategoryid','=',$data['head_category'])
                    ->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);

                        $query1 = HeadCategoriesBalanace::where('head_category_financial_year','=',$data['financial_year'])
                        ->where('headcategoryid','=',$data['head_category'])
                        ->update(
                            [
                                'head_category_budget_total_amount' => $data['total_amount'],
                                'head_category_total_amount' => $query->head_category_total_amount - $data['old_amount'],
                                'head_category_balance_amount' => $query->head_category_balance_amount - $data['old_balance_amount'],
                            ]
                        );
                    }
                    $query = HeadCategoriesBalanace::where('head_category_financial_year','=',$data['financial_year'])
                    ->where('headcategoryid','=',$data['head_category'])
                    ->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);
                        $new_bal = $data['amount'] - $data['diff_amount'];
                        $query1 = HeadCategoriesBalanace::where('head_category_financial_year','=',$data['financial_year'])
                        ->where('headcategoryid','=',$data['head_category'])
                        ->update(
                            [
                                'head_category_budget_total_amount' => $data['total_amount'],
                                'head_category_total_amount' => $query->head_category_total_amount + $data['amount'],
                                'head_category_balance_amount' => $query->head_category_balance_amount + $new_bal,
                            ]
                        );
                    }
                    return redirect('/head_categories_budgets')->with('success',"Updated Successfully");    
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

    function delete_head_category_budget($autoheadcategorybudgetid)
    {
        if(Session::get('admin_id'))
        {
            $query = HeadCategoriesBudget::where('autoheadcategorybudgetid','=',$autoheadcategorybudgetid)
            ->get();
            if(count($query) > 0)
            {
                foreach($query as $query);
                if($query->head_category_budget_amount == $query->head_category_budget_balance)
                {
                    
                    $query1 = HeadCategoriesBalanace::where('head_category_financial_year','=',$query->head_category_financial_year)
                    ->where('headcategoryid','=',$query->headcategoryid)
                    ->get();
                    if(count($query1) > 0)
                    {
                        foreach($query1 as $query1);
                        $bank_loan = HeadCategoriesBalanace::where('head_category_financial_year','=',$query->head_category_financial_year)
                        ->where('headcategoryid','=',$query->headcategoryid)
                        ->update(
                            [
                                'head_category_budget_total_amount' => $query1->head_category_budget_total_amount - $query->head_category_budget_total_amount,
                                'head_category_total_amount' => $query1->head_category_total_amount - $query->head_category_budget_balance,
                                'head_category_balance_amount' => $query1->head_category_balance_amount - $query->head_category_budget_balance,
                            ]
                        );
                    }        

                    $head_category_delete = HeadCategoriesBudget::where("autoheadcategorybudgetid",'=',$autoheadcategorybudgetid);
                    $head_category_delete->delete();
        
                    return redirect('/head_categories_budgets')->with('success',"Deleted Successfully");
                }
                else
                {
                    return back()->with('error',"Cannot Delete Budget because Balance Amount is not equal to Actual Amount");
                }
            }
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_head_categories_budgets(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
                    $query = HeadCategoriesBudget::where('autoheadcategorybudgetid','=',$deleteid)
                    ->get();
                    if(count($query) > 0)
                    {
                        foreach($query as $query);
                        if($query->head_category_budget_amount == $query->head_category_budget_balance)
                        {
                            
                            $query1 = HeadCategoriesBalanace::where('head_category_financial_year','=',$query->head_category_financial_year)
                            ->where('headcategoryid','=',$query->headcategoryid)
                            ->get();
                            if(count($query1) > 0)
                            {
                                foreach($query1 as $query1);
                                $bank_loan = HeadCategoriesBalanace::where('head_category_financial_year','=',$query->head_category_financial_year)
                                ->where('headcategoryid','=',$query->headcategoryid)
                                ->update(
                                    [
                                        'head_category_budget_total_amount' => $query1->head_category_budget_total_amount - $query->head_category_budget_total_amount,
                                        'head_category_total_amount' => $query1->head_category_total_amount - $query->head_category_budget_balance,
                                        'head_category_balance_amount' => $query1->head_category_balance_amount - $query->head_category_budget_balance,
                                    ]
                                );
                            }        

                            HeadCategoriesBudget::where('autoheadcategorybudgetid', $deleteid)->delete();
                        }
                        else
                        {
                            return back()->with('error',"Cannot Delete Budget because Balance Amount is not equal to Actual Amount");
                        }
                    }
				}
				return redirect('/head_categories_budgets')->with('success',"Deleted successfully");	
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

    function heads_budgets()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = HeadsBudget::join("heads","heads.autoheadid",'=',"heads_budgets.headid")
            ->orderby('heads_budgets.autoheadbudgetid','asc')->get();
            return view('head_wise_budget.heads_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function head_db_budget(Request $request)
    {
        $rules = [
            'head'  =>  'required|string',
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
                
                $query = HeadsBudget::where('headid','=',$data['head'])
                ->where('head_financial_year','=',$data['financial_year'])
                ->where('head_quarter','=',$data['quarter'])
                ->get();
                // dd($query);
                if(count($query) == 1)
                {
                    return back()->with('error',"Selected Head in ".$data['quarter']." of this Financial Year is already Exist!");
                }
                else
                {
                    $head = new HeadsBudget;
                    $head->headid = $data['head'];
                    $head->head_financial_year = $data['financial_year'];
                    $head->head_quarter = $data['quarter'];
                    $head->head_budget_total_amount = $data['total_amount'];
                    $head->head_budget_amount = $data['amount'];
                    $head->head_budget_balance = $data['amount'];
                    $head->added_by = Session::get('admin_id');
    
                    $head->save();

                    $query = HeadsBalanace::where('head_financial_year','=',$data['financial_year'])
                    ->where('headid','=',$data['head'])
                    ->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);
                        // echo '1';exit;
                        $query1 = HeadsBalanace::where('head_financial_year','=',$data['financial_year'])
                        ->where('headid','=',$data['head'])
                        ->update(
                            [
                                'head_budget_total_amount' => $data['total_amount'],
                                'head_total_amount' => $query->head_total_amount + $data['amount'],
                                'head_balance_amount' => $query->head_balance_amount + $data['amount'],
                            ]
                        );
                    }
                    else
                    {
                        // echo '2';exit;
                        $bank_balance = new HeadsBalanace;
                        $bank_balance->headid = $data['head'];
                        $bank_balance->head_financial_year = $data['financial_year'];
                        $bank_balance->head_budget_total_amount = $data['total_amount'];
                        $bank_balance->head_total_amount = $data['amount'];
                        $bank_balance->head_balance_amount = $data['amount'];
                        $bank_balance->added_by = Session::get('admin_id');

                        $bank_balance->save();
                    }
    
                    return redirect('/heads_budgets')->with('success',"Insert Successfully");
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_head_budget($autoheadbudgetid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = HeadsBudget::join("heads","heads.autoheadid",'=',"heads_budgets.headid")
            ->orderby('heads_budgets.autoheadbudgetid','asc')->get();
            $array1 = HeadsBudget::where("autoheadbudgetid",'=',$autoheadbudgetid)
            ->get();
            // dd($array);        
            return view('head_wise_budget.heads_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_head_db_budget(Request $request)
    {
        $rules = [
            'head'  =>  'required|string',
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
                
                if($data['amount'] > $data['diff_amount'])
                {
                    $head = HeadsBudget::where("autoheadbudgetid",'=',$data['autoheadbudgetid'])->update(
                        [
                            'headid' => $data['head'],
                            'head_budget_total_amount' => $data['total_amount'],
                            'head_budget_amount' => $data['amount'],
                            'head_budget_balance' => $data['amount'] - $data['diff_amount'],
                            'updated_by' => Session::get('admin_id'),
                        ]
                    );

                    $query = HeadsBalanace::where('head_financial_year','=',$data['financial_year'])
                    ->where('headid','=',$data['head'])
                    ->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);
                        $query1 = HeadsBalanace::where('head_financial_year','=',$data['financial_year'])
                        ->where('headid','=',$data['head'])
                        ->update(
                            [
                                'head_budget_total_amount' => $data['total_amount'],
                                'head_total_amount' => $query->head_total_amount - $data['old_amount'],
                                'head_balance_amount' => $query->head_balance_amount - $data['old_balance_amount'],
                            ]
                        );
                    }

                    $query = HeadsBalanace::where('head_financial_year','=',$data['financial_year'])
                    ->where('headid','=',$data['head'])
                    ->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);
                        $new_bal = $data['amount'] - $data['diff_amount'];
                        $query1 = HeadsBalanace::where('head_financial_year','=',$data['financial_year'])
                        ->where('headid','=',$data['head'])
                        ->update(
                            [
                                'head_budget_total_amount' => $data['total_amount'],
                                'head_total_amount' => $query->head_total_amount + $data['amount'],
                                'head_balance_amount' => $query->head_balance_amount + $new_bal,
                            ]
                        );
                    }
    
                    return redirect('/heads_budgets')->with('success',"Updated Successfully");    
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

    function delete_head_budget($autoheadbudgetid)
    {
        if(Session::get('admin_id'))
        {
            $query = HeadsBudget::where('autoheadbudgetid','=',$autoheadbudgetid)
            ->get();
            if(count($query) > 0)
            {
                foreach($query as $query);
                if($query->head_budget_amount == $query->head_budget_balance)
                {
                    
                    $query1 = HeadsBalanace::where('head_financial_year','=',$query->head_financial_year)
                    ->where('headid','=',$query->headid)
                    ->get();
                    if(count($query1) > 0)
                    {
                        foreach($query1 as $query1);
                        $bank_loan = HeadsBalanace::where('head_financial_year','=',$query->head_financial_year)
                        ->where('headid','=',$query->headid)
                        ->update(
                            [
                                'head_budget_total_amount' => $query1->head_budget_total_amount - $query->head_budget_total_amount,
                                'head_total_amount' => $query1->head_total_amount - $query->head_budget_balance,
                                'head_balance_amount' => $query1->head_balance_amount - $query->head_budget_balance,
                            ]
                        );
                    }        

                    $head_delete = HeadsBudget::where("autoheadbudgetid",'=',$autoheadbudgetid);
                    $head_delete->delete();
        
                    return redirect('/heads_budgets')->with('success',"Deleted Successfully");
                }
                else
                {
                    return back()->with('error',"Cannot Delete Budget because Balance Amount is not equal to Actual Amount");
                }
            }
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_heads_budgets(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
                    $query = HeadsBudget::where('autoheadbudgetid','=',$deleteid)
                    ->get();
                    if(count($query) > 0)
                    {
                        foreach($query as $query);
                        if($query->head_budget_amount == $query->head_budget_balance)
                        {
                            
                            $query1 = HeadsBalanace::where('head_financial_year','=',$query->head_financial_year)
                            ->where('headid','=',$query->headid)
                            ->get();
                            if(count($query1) > 0)
                            {
                                foreach($query1 as $query1);
                                $bank_loan = HeadsBalanace::where('head_financial_year','=',$query->head_financial_year)
                                ->where('headid','=',$query->headid)
                                ->update(
                                    [
                                        'head_budget_total_amount' => $query1->head_budget_total_amount - $query->head_budget_total_amount,
                                        'head_total_amount' => $query1->head_total_amount - $query->head_budget_balance,
                                        'head_balance_amount' => $query1->head_balance_amount - $query->head_budget_balance,
                                    ]
                                );
                            }        

                            HeadsBudget::where('autoheadbudgetid', $deleteid)->delete();
                        }
                        else
                        {
                            return back()->with('error',"Cannot Delete Budget because Balance Amount is not equal to Actual Amount");
                        }
                    }
				}
				return redirect('/heads_budgets')->with('success',"Deleted successfully");	
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

    function main_head_budgets()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = MainHeadBudget::join("main_heads","main_heads.automainheadid",'=',"main_head_budgets.mainheadid")
            ->orderby('main_head_budgets.automainheadbudgetid','asc')->get();
            return view('head_wise_budget.main_head_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function main_head_db_budget(Request $request)
    {
        $rules = [
            'main_head'  =>  'required|string',
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
                
                $query = MainHeadBudget::where('mainheadid','=',$data['main_head'])
                ->where('main_head_financial_year','=',$data['financial_year'])
                ->where('main_head_quarter','=',$data['quarter'])
                ->get();
                // dd($query);
                if(count($query) == 1)
                {
                    return back()->with('error',"Selected Main Head in ".$data['quarter']." of this Financial Year is already Exist!");
                }
                else
                {
                    $main_head = new MainHeadBudget;
                    $main_head->mainheadid = $data['main_head'];
                    $main_head->main_head_financial_year = $data['financial_year'];
                    $main_head->main_head_quarter = $data['quarter'];
                    $main_head->main_head_budget_total_amount = $data['total_amount'];
                    $main_head->main_head_budget_amount = $data['amount'];
                    $main_head->main_head_budget_balance = $data['amount'];
                    $main_head->added_by = Session::get('admin_id');
    
                    $main_head->save();

                    $query = MainHeadsBalanace::where('main_head_financial_year','=',$data['financial_year'])
                    ->where('mainheadid','=',$data['main_head'])
                    ->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);
                        // echo '1';exit;
                        $query1 = MainHeadsBalanace::where('main_head_financial_year','=',$data['financial_year'])
                        ->where('mainheadid','=',$data['main_head'])
                        ->update(
                            [
                                'main_head_budget_total_amount' => $data['total_amount'],
                                'main_head_total_amount' => $query->main_head_total_amount + $data['amount'],
                                'main_head_balance_amount' => $query->main_head_balance_amount + $data['amount'],
                            ]
                        );
                    }
                    else
                    {
                        // echo '2';exit;
                        $bank_balance = new MainHeadsBalanace;
                        $bank_balance->mainheadid = $data['main_head'];
                        $bank_balance->main_head_financial_year = $data['financial_year'];
                        $bank_balance->main_head_budget_total_amount = $data['total_amount'];
                        $bank_balance->main_head_total_amount = $data['amount'];
                        $bank_balance->main_head_balance_amount = $data['amount'];
                        $bank_balance->added_by = Session::get('admin_id');

                        $bank_balance->save();
                    }
    
                    return redirect('/main_head_budgets')->with('success',"Insert Successfully");
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_main_head_budget($automainheadbudgetid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = MainHeadBudget::join("main_heads","main_heads.automainheadid",'=',"main_head_budgets.mainheadid")
            ->orderby('main_head_budgets.automainheadbudgetid','asc')->get();
            $array1 = MainHeadBudget::where("automainheadbudgetid",'=',$automainheadbudgetid)
            ->get();
            // dd($array);        
            return view('head_wise_budget.main_head_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_main_head_db_budget(Request $request)
    {
        $rules = [
            'main_head'  =>  'required|string',
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
                
                if($data['amount'] > $data['diff_amount'])
                {
                    $main_head = MainHeadBudget::where("automainheadbudgetid",'=',$data['automainheadbudgetid'])->update(
                        [
                            'mainheadid' => $data['main_head'],
                            'main_head_budget_total_amount' => $data['total_amount'],
                            'main_head_budget_amount' => $data['amount'],
                            'main_head_budget_balance' => $data['amount']-$data['diff_amount'],
                            'updated_by' => Session::get('admin_id'),
                        ]
                    );
    
                    $query = MainHeadsBalanace::where('main_head_financial_year','=',$data['financial_year'])
                    ->where('mainheadid','=',$data['main_head'])
                    ->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);
                        $query1 = MainHeadsBalanace::where('main_head_financial_year','=',$data['financial_year'])
                        ->where('mainheadid','=',$data['main_head'])
                        ->update(
                            [
                                'main_head_budget_total_amount' => $data['total_amount'],
                                'main_head_total_amount' => $query->main_head_total_amount - $data['old_amount'],
                                'main_head_balance_amount' => $query->main_head_balance_amount - $data['old_balance_amount'],
                            ]
                        );
                    }

                    $query = MainHeadsBalanace::where('main_head_financial_year','=',$data['financial_year'])
                    ->where('mainheadid','=',$data['main_head'])
                    ->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);
                        $new_bal = $data['amount'] - $data['diff_amount'];
                        $query1 = MainHeadsBalanace::where('main_head_financial_year','=',$data['financial_year'])
                        ->where('mainheadid','=',$data['main_head'])
                        ->update(
                            [
                                'main_head_budget_total_amount' => $data['total_amount'],
                                'main_head_total_amount' => $query->main_head_total_amount + $data['amount'],
                                'main_head_balance_amount' => $query->main_head_balance_amount + $new_bal,
                            ]
                        );
                    }

                    return redirect('/main_head_budgets')->with('success',"Updated Successfully");
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

    function delete_main_head_budget($automainheadbudgetid)
    {
        if(Session::get('admin_id'))
        {
            $query = MainHeadBudget::where('automainheadbudgetid','=',$automainheadbudgetid)
            ->get();
            if(count($query) > 0)
            {
                foreach($query as $query);
                if($query->main_head_budget_amount == $query->main_head_budget_balance)
                {
                    
                    $query1 = MainHeadsBalanace::where('main_head_financial_year','=',$query->main_head_financial_year)
                    ->where('mainheadid','=',$query->mainheadid)
                    ->get();
                    if(count($query1) > 0)
                    {
                        foreach($query1 as $query1);
                        $bank_loan = MainHeadsBalanace::where('main_head_financial_year','=',$query->main_head_financial_year)
                        ->where('mainheadid','=',$query->mainheadid)
                        ->update(
                            [
                                'main_head_budget_total_amount' => $query1->main_head_budget_total_amount - $query->main_head_budget_total_amount,
                                'main_head_total_amount' => $query1->main_head_total_amount - $query->main_head_budget_balance,
                                'main_head_balance_amount' => $query1->main_head_balance_amount - $query->main_head_budget_balance,
                            ]
                        );
                    }        

                    $main_head_delete = MainHeadBudget::where("automainheadbudgetid",'=',$automainheadbudgetid);
                    $main_head_delete->delete();
        
                    return redirect('/main_head_budgets')->with('success',"Deleted Successfully");        
                }
                else
                {
                    return back()->with('error',"Cannot Delete Budget because Balance Amount is not equal to Actual Amount");
                }
            }
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_main_head_budgets(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
                    $query = MainHeadBudget::where('automainheadbudgetid','=',$deleteid)
                    ->get();
                    if(count($query) > 0)
                    {
                        foreach($query as $query);
                        if($query->main_head_budget_amount == $query->main_head_budget_balance)
                        {
                            
                            $query1 = MainHeadsBalanace::where('main_head_financial_year','=',$query->main_head_financial_year)
                            ->where('mainheadid','=',$query->mainheadid)
                            ->get();
                            if(count($query1) > 0)
                            {
                                foreach($query1 as $query1);
                                $bank_loan = MainHeadsBalanace::where('main_head_financial_year','=',$query->main_head_financial_year)
                                ->where('mainheadid','=',$query->mainheadid)
                                ->update(
                                    [
                                        'main_head_budget_total_amount' => $query1->main_head_budget_total_amount - $query->main_head_budget_total_amount,
                                        'main_head_total_amount' => $query1->main_head_total_amount - $query->main_head_budget_balance,
                                        'main_head_balance_amount' => $query1->main_head_balance_amount - $query->main_head_budget_balance,
                                    ]
                                );
                            }        

                            MainHeadBudget::where('automainheadbudgetid', $deleteid)->delete();
                        }
                        else
                        {
                            return back()->with('error',"Cannot Delete Budget because Balance Amount is not equal to Actual Amount");
                        }
                    }
				}
				return redirect('/main_head_budgets')->with('success',"Deleted successfully");	
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

    function sub_head_budgets()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = SubHeadBudget::join("sub_heads","sub_heads.autosubheadid",'=',"sub_head_budgets.subheadid")
            ->orderby('sub_head_budgets.autosubheadbudgetid','asc')->get();
            return view('head_wise_budget.sub_head_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function sub_head_db_budget(Request $request)
    {
        $rules = [
            'sub_head'  =>  'required|string',
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
                
                $query = SubHeadBudget::where('subheadid','=',$data['sub_head'])
                ->where('sub_head_financial_year','=',$data['financial_year'])
                ->where('sub_head_quarter','=',$data['quarter'])
                ->get();
                // dd($query);
                if(count($query) == 1)
                {
                    return back()->with('error',"Selected Sub Head in ".$data['quarter']." of this Financial Year is already Exist!");
                }
                else
                {
                    $sub_head = new SubHeadBudget;
                    $sub_head->subheadid = $data['sub_head'];
                    $sub_head->sub_head_financial_year = $data['financial_year'];
                    $sub_head->sub_head_quarter = $data['quarter'];
                    $sub_head->sub_head_budget_total_amount = $data['total_amount'];
                    $sub_head->sub_head_budget_amount = $data['amount'];
                    $sub_head->sub_head_budget_balance = $data['amount'];
                    $sub_head->added_by = Session::get('admin_id');
    
                    $sub_head->save();

                    $query = SubHeadsBalanace::where('sub_head_financial_year','=',$data['financial_year'])
                    ->where('subheadid','=',$data['sub_head'])
                    ->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);
                        // echo '1';exit;
                        $query1 = SubHeadsBalanace::where('sub_head_financial_year','=',$data['financial_year'])
                        ->where('subheadid','=',$data['sub_head'])
                        ->update(
                            [
                                'sub_head_budget_total_amount' => $data['total_amount'],
                                'sub_head_total_amount' => $query->sub_head_total_amount + $data['amount'],
                                'sub_head_balance_amount' => $query->sub_head_balance_amount + $data['amount'],
                            ]
                        );
                    }
                    else
                    {
                        // echo '2';exit;
                        $bank_balance = new SubHeadsBalanace;
                        $bank_balance->subheadid = $data['sub_head'];
                        $bank_balance->sub_head_financial_year = $data['financial_year'];
                        $bank_balance->sub_head_budget_total_amount = $data['total_amount'];
                        $bank_balance->sub_head_total_amount = $data['amount'];
                        $bank_balance->sub_head_balance_amount = $data['amount'];
                        $bank_balance->added_by = Session::get('admin_id');

                        $bank_balance->save();
                    }
    
                    return redirect('/sub_head_budgets')->with('success',"Insert Successfully");
                }
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_sub_head_budget($autosubheadbudgetid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = SubHeadBudget::join("sub_heads","sub_heads.autosubheadid",'=',"sub_head_budgets.subheadid")
            ->orderby('sub_head_budgets.autosubheadbudgetid','asc')->get();
            $array1 = SubHeadBudget::where("autosubheadbudgetid",'=',$autosubheadbudgetid)
            ->get();
            // dd($array);        
            return view('head_wise_budget.sub_head_budgets',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_sub_head_db_budget(Request $request)
    {
        $rules = [
            'sub_head'  =>  'required|string',
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
                
                if($data['amount'] > $data['diff_amount'])
                {
                    $sub_head = SubHeadBudget::where("autosubheadbudgetid",'=',$data['autosubheadbudgetid'])->update(
                        [
                            'subheadid' => $data['sub_head'],
                            'sub_head_budget_total_amount' => $data['total_amount'],
                            'sub_head_budget_amount' => $data['amount'],
                            'sub_head_budget_balance' => $data['amount']-$data['diff_amount'],
                            'updated_by' => Session::get('admin_id'),
                        ]
                    );
    
                    $query = SubHeadsBalanace::where('sub_head_financial_year','=',$data['financial_year'])
                    ->where('subheadid','=',$data['sub_head'])
                    ->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);
                        $query1 = SubHeadsBalanace::where('sub_head_financial_year','=',$data['financial_year'])
                        ->where('subheadid','=',$data['sub_head'])
                        ->update(
                            [
                                'sub_head_budget_total_amount' => $data['total_amount'],
                                'sub_head_total_amount' => $query->sub_head_total_amount - $data['old_amount'],
                                'sub_head_balance_amount' => $query->sub_head_balance_amount - $data['old_balance_amount'],
                            ]
                        );
                    }

                    $query = SubHeadsBalanace::where('sub_head_financial_year','=',$data['financial_year'])
                    ->where('subheadid','=',$data['sub_head'])
                    ->get();
                    if(count($query) == 1)
                    {
                        foreach($query as $query);
                        $new_bal = $data['amount'] - $data['diff_amount'];
                        $query1 = SubHeadsBalanace::where('sub_head_financial_year','=',$data['financial_year'])
                        ->where('subheadid','=',$data['sub_head'])
                        ->update(
                            [
                                'sub_head_budget_total_amount' => $data['total_amount'],
                                'sub_head_total_amount' => $query->sub_head_total_amount + $data['amount'],
                                'sub_head_balance_amount' => $query->sub_head_balance_amount + $new_bal,
                            ]
                        );
                    }
                    return redirect('/sub_head_budgets')->with('success',"Updated Successfully");
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

    function delete_sub_head_budget($autosubheadbudgetid)
    {
        if(Session::get('admin_id'))
        {
            $query = SubHeadBudget::where('autosubheadbudgetid','=',$autosubheadbudgetid)
            ->get();
            if(count($query) > 0)
            {
                foreach($query as $query);
                if($query->sub_head_budget_amount == $query->sub_head_budget_balance)
                {
                    
                    $query1 = SubHeadsBalanace::where('sub_head_financial_year','=',$query->sub_head_financial_year)
                    ->where('subheadid','=',$query->subheadid)
                    ->get();
                    if(count($query1) > 0)
                    {
                        foreach($query1 as $query1);
                        $bank_loan = SubHeadsBalanace::where('sub_head_financial_year','=',$query->sub_head_financial_year)
                        ->where('subheadid','=',$query->subheadid)
                        ->update(
                            [
                                'sub_head_budget_total_amount' => $query1->sub_head_budget_total_amount - $query->sub_head_budget_total_amount,
                                'sub_head_total_amount' => $query1->sub_head_total_amount - $query->sub_head_budget_balance,
                                'sub_head_balance_amount' => $query1->sub_head_balance_amount - $query->sub_head_budget_balance,
                            ]
                        );
                    }        

                    $sub_head_delete = SubHeadBudget::where("autosubheadbudgetid",'=',$autosubheadbudgetid);
                    $sub_head_delete->delete();
        
                    return redirect('/sub_head_budgets')->with('success',"Deleted Successfully");
                }
                else
                {
                    return back()->with('error',"Cannot Delete Budget because Balance Amount is not equal to Actual Amount");
                }
            }
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_sub_head_budgets(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
                    $query = SubHeadBudget::where('autosubheadbudgetid','=',$deleteid)
                    ->get();
                    if(count($query) > 0)
                    {
                        foreach($query as $query);
                        if($query->sub_head_budget_amount == $query->sub_head_budget_balance)
                        {
                            
                            $query1 = SubHeadsBalanace::where('sub_head_financial_year','=',$query->sub_head_financial_year)
                            ->where('subheadid','=',$query->subheadid)
                            ->get();
                            if(count($query1) > 0)
                            {
                                foreach($query1 as $query1);
                                $bank_loan = SubHeadsBalanace::where('sub_head_financial_year','=',$query->sub_head_financial_year)
                                ->where('subheadid','=',$query->subheadid)
                                ->update(
                                    [
                                        'sub_head_budget_total_amount' => $query1->sub_head_budget_total_amount - $query->sub_head_budget_total_amount,
                                        'sub_head_total_amount' => $query1->sub_head_total_amount - $query->sub_head_budget_balance,
                                        'sub_head_balance_amount' => $query1->sub_head_balance_amount - $query->sub_head_budget_balance,
                                    ]
                                );
                            }        

                            SubHeadBudget::where('autosubheadbudgetid', $deleteid)->delete();
                        }
                        else
                        {
                            return back()->with('error',"Cannot Delete Budget because Balance Amount is not equal to Actual Amount");
                        }
                    }
				}
				return redirect('/sub_head_budgets')->with('success',"Deleted successfully");	
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
