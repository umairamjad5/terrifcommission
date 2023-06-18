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
use App\Models\MainHead;
use App\Models\SubHead;

class SubHeadController extends Controller
{
    function sub_heads()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = SubHead::join("head_categories","head_categories.autoheadcategoryid",'=','sub_heads.headcategoryid')
            ->join("heads","heads.autoheadid",'=','sub_heads.headid')
            ->join("main_heads","main_heads.automainheadid",'=','sub_heads.mainheadid')
            ->orderby('sub_heads.autosubheadid','asc')->get();
            return view('heads.sub_heads',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function sub_head_db(Request $request)
    {
        $rules = [
            'head_category'  =>  'required|string',
            'head'  =>  'required|string',
            'main_head'  =>  'required|string',
            'sub_head_code'  =>  'required|string',
            'sub_head_name'  =>  'required|string',
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
                $data['subhead_financialyear'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['subhead_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['subhead_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
				$sub_head = new SubHead;
                $sub_head->subhead_financialyear = $data['subhead_financialyear'];
                $sub_head->headcategoryid = $data['head_category'];
                $sub_head->headid = $data['head'];
                $sub_head->mainheadid = $data['main_head'];
                $sub_head->sub_head_code = $data['sub_head_code'];
                $sub_head->sub_head_name = $data['sub_head_name'];
                $sub_head->added_by = Session::get('admin_id');

				$sub_head->save();

				return redirect('/sub_heads')->with('success',"Insert Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_sub_head($autosubheadid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = SubHead::join("head_categories","head_categories.autoheadcategoryid",'=','sub_heads.headcategoryid')
            ->join("heads","heads.autoheadid",'=','sub_heads.headid')
            ->join("main_heads","main_heads.automainheadid",'=','sub_heads.mainheadid')
            ->orderby('sub_heads.autosubheadid','asc')->get();
            $array1 = SubHead::where("autosubheadid",'=',$autosubheadid)
            ->get();
            // dd($array);        
            return view('heads.sub_heads',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_sub_head_db(Request $request)
    {
        $rules = [
            'head_category'  =>  'required|string',
            'head'  =>  'required|string',
            'main_head'  =>  'required|string',
            'sub_head_code'  =>  'required|string',
            'sub_head_name'  =>  'required|string',
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
                $data['subhead_financialyear'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['subhead_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['subhead_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
				$sub_head = SubHead::where("autosubheadid",'=',$data['autosubheadid'])->update(
                    [
                        'headcategoryid' => $data['head_category'],
                        'headid' => $data['head'],
                        'mainheadid' => $data['main_head'],
                        'sub_head_code' => $data['sub_head_code'],
                        'sub_head_name' => $data['sub_head_name'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

				return redirect('/sub_heads')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_sub_head($autosubheadid)
    {
        if(Session::get('admin_id'))
        {
            $sub_head_delete = SubHead::where("autosubheadid",'=',$autosubheadid);
            $sub_head_delete->delete();

            return redirect('/sub_heads')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_sub_heads(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
					SubHead::where('autosubheadid', $deleteid)->delete();
				}
				return redirect('/sub_heads')->with('success',"Deleted successfully");	
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
