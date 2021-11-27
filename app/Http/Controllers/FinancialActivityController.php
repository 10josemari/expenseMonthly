<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\FinancialActivity;
use App\Models\ConfigSalary;
use App\Models\Category;
use App\Models\Salary;
use Carbon\Carbon;

class FinancialActivityController extends Controller
{
    /**
     * 
     */
    public function indexIncome(){
        /**
         * Obtenemos las categorías
         */
        $categories = Category::select('*')->where('deleted_at','=',NULL)->get();
        /**
         * Obtenemos los ingresos en el intervalo de mes
         */
        $salary = Salary::where('month','=',date('m'))->where('year','=',date('Y'))->sum('money');
        if ($salary == NULL) {
            $getDate = explode("-",getRestMonth(date('m')));
            $salary = Salary::where('month','=',$getDate[0])->where('year','=',$getDate[1])->sum('money');
        } else {
            return "Ahorro ".getMonth(date('m'))." / ".date('Y');
        }

        return view('income.income',compact('categories'));
    }

    /**
     * 
     */
    public function addIncome(){
        request()->validate([
            'name' => 'required|string',
            'category' => 'required',
            'income' => 'required|numeric',
        ]);

        /**
         * Actualizamos el total
         */
        $salary = ConfigSalary::select('*')->where('month','=',date('m'))->where('year','=',date('Y'))->orderBy('id', 'desc')->get();
        if (count($salary) == 0) {
            $getDate = explode("-",getRestMonth(date('m')));
            $salary = ConfigSalary::select('*')->where('month','=',$getDate[0])->where('year','=',$getDate[1])->orderBy('id', 'desc')->get();
            $salaryNowTotal = $salary[0]['bank_now_total'];
        } else {
            $salaryNowTotal = $salary[0]['bank_now_total'];
        }
        $income = $salaryNowTotal + request()->income;
        ConfigSalary::find($salary[0]['id'])->update(['bank_now_total' => $income],['updated_at' => Carbon::now()]);

        FinancialActivity::create([
            'month' => date('m'),
            'year' => date('Y'),
            'name' => request()->name,
            'type' => 'income',
            'value' => request()->income,
            'user_id' => auth()->user()->id,
            'category_id' =>request()->category,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        request()->session()->regenerateToken();
        return redirect()->route('income');
    }
}
