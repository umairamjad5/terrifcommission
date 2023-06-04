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
use App\Models\EmployeeHBDeduction;
use App\Models\EmployeeMotorCarDeduction;
use App\Models\EmployeeMotorCycleDeduction;

class ReportsController extends Controller
{
    function ledger_report()
    {
        if(Session::get('admin_id'))
        {
            return view('reports.ledger_report');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function ledger_db(Request $request)
    {
        $rules = [
            'start_date'  =>  'required|string',
            'end_date'  =>  'required|string',
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
                $start_date = Carbon::parse($data['start_date'])
                                ->toDateTimeString();

                $end_date = Carbon::parse($data['end_date'])
                                ->toDateTimeString();

                $array = Ledger::whereBetween('amount_date',[$start_date,$end_date])
                ->orderby('autoledgerid','asc')
                ->get();
                // dd($array);
                return view('reports.ledger_report',compact('array'));
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function fund_position()
    {
        if(Session::get('admin_id'))
        {
            return view('reports.fund_position');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function fund_position_db(Request $request)
    {
        $rules = [
            'financial_year'  =>  'required|string',
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
                $financial_year = $data['financial_year'];
                return view('reports.fund_position',compact('financial_year'));
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function trial_balance()
    {
        if(Session::get('admin_id'))
        {
            return view('reports.trial_balance');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function trial_balance_db(Request $request)
    {
        $rules = [
            'financial_year'  =>  'required|string',
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
                $financial_year = $data['financial_year'];
                return view('reports.trial_balance',compact('financial_year'));
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function staff_salary_report()
    {
        if(Session::get('admin_id'))
        {
            return view('reports.staff_salary_report');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function staff_salary_report_db(Request $request)
    {
        $rules = [
            'month'  =>  'required|string',
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
                $month = $data['month'];
                return view('reports.staff_salary_report',compact('month'));
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function officer_salary_report()
    {
        if(Session::get('admin_id'))
        {
            return view('reports.officer_salary_report');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function officer_salary_report_db(Request $request)
    {
        $rules = [
            'month'  =>  'required|string',
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
                $month = $data['month'];
                return view('reports.officer_salary_report',compact('month'));
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function hbl_report()
    {
        if(Session::get('admin_id'))
        {
            return view('reports.hbl_report');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function hbl_report_db(Request $request)
    {
        $rules = [
            'month'  =>  'required|string',
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
                $month = $data['month'];
                return view('reports.hbl_report',compact('month'));
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function nbp_report()
    {
        if(Session::get('admin_id'))
        {
            return view('reports.nbp_report');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function nbp_report_db(Request $request)
    {
        $rules = [
            'month'  =>  'required|string',
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
                $month = $data['month'];
                return view('reports.nbp_report',compact('month'));
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function hb_loan_report()
    {
        if(Session::get('admin_id'))
        {
            return view('loan_reports.hb_loan_report');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function hb_loan_report_db(Request $request)
    {
        $rules = [
            'employee'  =>  'required|string',
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
                $array = EmployeeHBDeduction::where('employeeid','=',$data['employee'])
                ->orderby('autoemployeehbdeductionid','asc')
                ->get();

                return view('loan_reports.hb_loan_report',compact('array'));
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function motorcar_loan_report()
    {
        if(Session::get('admin_id'))
        {
            return view('loan_reports.motorcar_loan_report');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function motorcar_loan_report_db(Request $request)
    {
        $rules = [
            'employee'  =>  'required|string',
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
                $array = EmployeeMotorCarDeduction::where('employeeid','=',$data['employee'])
                ->orderby('autoemployeemotorcardeductionid','asc')
                ->get();

                return view('loan_reports.motorcar_loan_report',compact('array'));
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }

    function motorcycle_loan_report()
    {
        if(Session::get('admin_id'))
        {
            return view('loan_reports.motorcycle_loan_report');
        }
        else
        {
            return redirect('/')->with('error',"Please Login First");
        }
    }

    function motorcycle_loan_report_db(Request $request)
    {
        $rules = [
            'employee'  =>  'required|string',
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
                $array = EmployeeMotorCycleDeduction::where('employeeid','=',$data['employee'])
                ->orderby('autoemployeemotorcycledeductionid','asc')
                ->get();

                return view('loan_reports.motorcycle_loan_report',compact('array'));
			}
			catch(Exception $e){
				return back()->with('error',"Error Occured");
			}
		}
    }
}
