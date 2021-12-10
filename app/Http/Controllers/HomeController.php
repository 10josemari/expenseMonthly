<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\MonthlySavings;
use App\Models\FinancialActivity;
use Illuminate\Http\Request;
use App\Models\SalaryUsers;
use App\Models\Salary;
use App\Models\Config;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Una vez logueados, vamos a la vista home
     */
    public function index()
    {
        $salary = Salary::select('*')->join('salary_bank', 'salary.id', '=', 'salary_bank.salary_id')->where('passed','=',0)->get();
        $salaries = SalaryUsers::select('*')->where('salary_id','=',$salary[0]['id'])->get();
        $expenses = FinancialActivity::where('salary_id','=',$salary[0]['id'])->where('type','=','expense')->sum('value');
        $incomes = FinancialActivity::where('salary_id','=',$salary[0]['id'])->where('type','=','income')->sum('value');
        $financialActivity = FinancialActivity::select('*')->where('salary_id','=',$salary[0]['id'])->orderBy('created_at', 'asc')->get();

        return view('home.home',compact('salary','salaries','expenses','incomes','financialActivity'));
    }
}
