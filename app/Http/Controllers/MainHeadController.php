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

class MainHeadController extends Controller
{
    function main_heads()
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = MainHead::join("head_categories","head_categories.autoheadcategoryid",'=','main_heads.headcategoryid')
            ->join("heads","heads.autoheadid",'=','main_heads.headid')
            ->orderby('main_heads.automainheadid','asc')->get();
            return view('heads.main_heads',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function main_head_db(Request $request)
    {
        $rules = [
            'head_category'  =>  'required|string',
            'head'  =>  'required|string',
            'main_head_code'  =>  'required|string',
            'main_head_name'  =>  'required|string',
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
                $data['mainhead_financialyear'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['mainhead_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['mainhead_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
				$main_head = new MainHead;
                $main_head->mainhead_financialyear = $data['mainhead_financialyear'];
                $main_head->headcategoryid = $data['head_category'];
                $main_head->headid = $data['head'];
                $main_head->main_head_code = $data['main_head_code'];
                $main_head->main_head_name = $data['main_head_name'];
                $main_head->added_by = Session::get('admin_id');

				$main_head->save();

				return redirect('/main_heads')->with('success',"Insert Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function update_main_head($automainheadid)
    {
        if(Session::get('admin_id'))
        {
            $array = array();
            $array1 = array();
            $array = MainHead::join("head_categories","head_categories.autoheadcategoryid",'=','main_heads.headcategoryid')
            ->join("heads","heads.autoheadid",'=','main_heads.headid')
            ->orderby('main_heads.automainheadid','asc')->get();
            $array1 = MainHead::where("automainheadid",'=',$automainheadid)
            ->get();
            // dd($array);        
            return view('heads.main_heads',compact('array','array1'));
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function update_main_head_db(Request $request)
    {
        $rules = [
            'head_category'  =>  'required|string',
            'head'  =>  'required|string',
            'main_head_code'  =>  'required|string',
            'main_head_name'  =>  'required|string',
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
                $data['mainhead_financialyear'] = '';
                $thisyearmonths = date('m',time());
                
                if($thisyearmonths >= 01 AND $thisyearmonths <= 06)
                {
                    $data['mainhead_financialyear'] = date('Y',strtotime('-1 year')).' - '.date('Y',time());
                }
                else
                {
                    $data['mainhead_financialyear'] = date('Y',time()).' - '.date('Y',strtotime('+1 year'));
                }
				$main_head = MainHead::where("automainheadid",'=',$data['automainheadid'])->update(
                    [
                        'headcategoryid' => $data['head_category'],
                        'headid' => $data['head'],
                        'main_head_code' => $data['main_head_code'],
                        'main_head_name' => $data['main_head_name'],
                        'updated_by' => Session::get('admin_id'),
                    ]
                );

				return redirect('/main_heads')->with('success',"Updated Successfully");
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function delete_main_head($automainheadid)
    {
        if(Session::get('admin_id'))
        {
            $main_head_delete = MainHead::where("automainheadid",'=',$automainheadid);
            $main_head_delete->delete();

            return redirect('/main_heads')->with('success',"Deleted Successfully");
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function delete_multiple_main_heads(request $request)
    {
		try{
			$id = $request->id;
			if(!empty($id))
			{
				foreach ($id as $deleteid) 
				{
					MainHead::where('automainheadid', $deleteid)->delete();
				}
				return redirect('/main_heads')->with('success',"Deleted successfully");	
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
