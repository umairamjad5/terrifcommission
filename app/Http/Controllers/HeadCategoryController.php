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
use App\Models\HeadCategory;

class HeadCategoryController extends Controller
{
    function head_categories()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = HeadCategory::orderby('head_categories.autoheadcategoryid','asc')->get();
            return view('heads.head_categories',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function head_category_db(Request $request)
    {
        $rules = [
            'head_type'  =>  'required|string',
            'head_category_code'  =>  'required|string',
            'head_category_name'  =>  'required|string',
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
                $data['headcategory_financialyear'] = '';
                $thisyearmonths = date('02',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['headcategory_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['headcategory_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
				$head_category = new HeadCategory;
                $head_category->headcategory_financialyear = $data['headcategory_financialyear'];
                $head_category->head_type = $data['head_type'];
                $head_category->head_category_code = $data['head_category_code'];
                $head_category->head_category_name = $data['head_category_name'];
                $head_category->added_by = Session::get('admin_id');

				$head_category->save();

				return redirect('/head_categories')->with('success',"Insert Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_head_category($autoheadcategoryid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = HeadCategory::orderby('head_categories.autoheadcategoryid','asc')->get();
            $array1 = HeadCategory::where("autoheadcategoryid",'=',$autoheadcategoryid)
            ->get();
            // dd($array);        
            return view('heads.head_categories',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_head_category_db(Request $request)
    {
        $rules = [
            'head_type'  =>  'required|string',
            'head_category_code'  =>  'required|string',
            'head_category_name'  =>  'required|string',
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
                $data['headcategory_financialyear'] = '';
                $thisyearmonths = date('02',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['headcategory_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['headcategory_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
				$head_category = HeadCategory::where("autoheadcategoryid",'=',$data['autoheadcategoryid'])->update(
                    [
                        'head_type' => $data['head_type'],
                        'head_category_code' => $data['head_category_code'],
                        'head_category_name' => $data['head_category_name'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

				return redirect('/head_categories')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_head_category($autoheadcategoryid)
    {
        if(Session::get('admin_id'))
        {
            $head_category_delete = HeadCategory::where("autoheadcategoryid",'=',$autoheadcategoryid);
            $head_category_delete->delete();

            return redirect('/head_categories')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_head_categories(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
					HeadCategory::where('autoheadcategoryid', $deleteid)->delete();
				}
				return redirect('/head_categories')->with('success',"Deleted successfully");	
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
