<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\MonthlySavings;
use Illuminate\Http\Request;
use App\Models\ConfigSalary;
use App\Models\Salary;
use App\Models\Config;
use Carbon\Carbon;

class SalaryController extends Controller
{
    /**
     * Vamos a la vista de añadir salario
     */
    public function index(){
        $salaries = Salary::select('*')->paginate(6);
        return view('salary.salary', compact('salaries'));
    }

    /**
     * Añadimos una categoría y retornamos a la vista de categorías
     */
    public function addSalary(){
        request()->validate([
            'name' => 'required|string',
            'salary' => 'required|numeric',
        ]);

        /**
         * Realizamos las comprobaciones e insertamos los campos.
         * - Banco mes actual (sin ahorro) - bank_previous_month
         * - Banco (no debe de bajar) - bank_adding_savings
         * - Ahorro banco (mes actual) - bank_now_total
         */
        $countSalary = Salary::select('*')->where('month','=',date('m'))->where('year','=',date('Y'))->get();
        if(count($countSalary) < 2){
            Salary::create([
                'name' => request()->name,
                'money' => request()->salary,
                'month' => date('m'),
                'year' => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $salaryInitial = ConfigSalary::select('*')->where('month','=',date('m'))->where('year','=',date('Y'))->orderBy('id', 'desc')->get();
            if (count($salaryInitial) == 0) {
                $getDate = explode("-",getRestMonth(date('m')));
                $salaryInitial = ConfigSalary::select('*')->where('month','=',$getDate[0])->where('year','=',$getDate[1])->orderBy('id', 'desc')->get();
                if (count($salaryInitial) == 0) {
                    $salaryInitial = Config::select('*')->where('option','=','importe inicial')->get();
                    $salaryInitial = $salaryInitial[0]['value'];
                    $salaryNowTotal = $salaryInitial;
                } else {
                    $salaryInitial = $salaryInitial[0]['bank_previous_month'];
                    $salaryNowTotal = $salaryInitial;
                }
            } else {
                $salaryInitial = $salaryInitial[0]['bank_previous_month'];
                $salaryNowTotal = $salaryInitial;
            }
            $savings = MonthlySavings::select('*')->where('month','=',date('m'))->where('year','=',date('Y'))->get();
            if (count($savings) == 0) {
                $getDate = explode("-",getRestMonth(date('m')));
                $savings = MonthlySavings::select('*')->where('month','=',$getDate[0])->where('year','=',$getDate[1])->get();
                if (count($savings) == 0) {
                    $savings = Config::select('*')->where('option','=','ahorro mensual')->get();
                    $savings = $savings[0]['value'];
                } else {
                    $savings = $savings[0]['value'];
                }
            } else {
                $savings = $savings[0]['value'];
            }
            $salaries = Salary::where('month','=',date('m'))->where('year','=',date('Y'))->orderBy('id', 'desc')->get();
            if (count($salaries) == 0) {
                $getDate = explode("-",getRestMonth(date('m')));
                $salaries = Salary::where('month','=',$getDate[0])->where('year','=',$getDate[1])->get();
                if(count($salaries)){
                    $id = $salaries[0]['id'];
                    $salaries = Salary::where('month','=',$getDate[0])->where('year','=',$getDate[1])->sum('money');
                } else {
                    $salaries = "00.00";
                }
            } else {
                $id = $salaries[0]['id'];
                $salaries = Salary::where('month','=',date('m'))->where('year','=',date('Y'))->sum('money');
            }
            // Sumamos totales
            $bank_previous_month = $salaryInitial;
            $bank_adding_savings = $salaryInitial + $savings;
            $bank_now_total = $salaries + $salaryNowTotal;
            // Creamos el registro
            ConfigSalary::create([
                'bank_previous_month' => $bank_previous_month,
                'bank_adding_savings' => $bank_adding_savings,
                'bank_now_total' => $bank_now_total,
                'month' => date('m'),
                'year' => date('Y'),
                'salary_id' => $id,
                'config_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            // Retornamos a la vista
            request()->session()->regenerateToken();
            return redirect()->route('salary');
        } else {
            request()->session()->regenerateToken();
            return redirect()->route('salary'); 
        }
    }

    /**
    * Actualizamos el nombre de la categoría
    */
    public function updateSalary(Request $request){
        switch ($request->name) {
            case 'name':
                Salary::find($request->pk)->update([$request->name => $request->value],['updated_at' => Carbon::now()]);
            break;
            case 'money':
                Salary::find($request->pk)->update([$request->name => $request->value], ['updated_at' => Carbon::now()]);
            break;
        }
        return response()->json(['success'=>'done']);
    }

    /**
     * Eliminamos una categoría
     */
    public function deleteSalary(Request $request){
        Salary::find($request->id)->delete();
        return response()->json(['success'=>'done']);
    }
}
