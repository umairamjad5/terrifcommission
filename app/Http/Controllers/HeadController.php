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
use App\Models\Head;

class HeadController extends Controller
{
    function heads()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = Head::join("head_categories","heads.headcategoryid",'=',"head_categories.autoheadcategoryid")
            ->orderby('autoheadid','asc')->get();
            return view('heads.heads',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function head_db(Request $request)
    {
        $rules = [
            'head_category'  =>  'required|string',
            'head_code'  =>  'required|string',
            'head_name'  =>  'required|string',
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
                $data['head_financialyear'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['head_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['head_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
				$head = new Head;
                $head->head_financialyear = $data['head_financialyear'];
                $head->headcategoryid = $data['head_category'];
                $head->head_code = $data['head_code'];
                $head->head_name = $data['head_name'];
                $head->added_by = Session::get('admin_id');

				$head->save();

				return redirect('/heads')->with('success',"Insert Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_head($autoheadid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = Head::join("head_categories","heads.headcategoryid",'=',"head_categories.autoheadcategoryid")
            ->orderby('autoheadid','asc')->get();
            $array1 = Head::where("autoheadid",'=',$autoheadid)
            ->get();
            // dd($array);        
            return view('heads.heads',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_head_db(Request $request)
    {
        $rules = [
            'head_category'  =>  'required|string',
            'head_code'  =>  'required|string',
            'head_name'  =>  'required|string',
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
                $data['head_financialyear'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['head_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['head_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
				$head = Head::where("autoheadid",'=',$data['autoheadid'])->update(
                    [
                        'headcategoryid' => $data['head_category'],
                        'head_code' => $data['head_code'],
                        'head_name' => $data['head_name'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

				return redirect('/heads')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_head($autoheadid)
    {
        if(Session::get('admin_id'))
        {
            $head_delete = Head::where("autoheadid",'=',$autoheadid);
            $head_delete->delete();

            return redirect('/heads')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_heads(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
					Head::where('autoheadid', $deleteid)->delete();
				}
				return redirect('/heads')->with('success',"Deleted successfully");	
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
