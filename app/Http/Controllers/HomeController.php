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

    /**
     * Vista historial
     */
    public function indexHistory(){
        $salary = Salary::select('*')->join('salary_bank', 'salary.id', '=', 'salary_bank.salary_id')->where('passed','=',1)->orderBy('salary.id', 'desc')->paginate(3);
        return view('history.history',compact('salary'));
    }

    /** 
     * Mostramos información sobre un mes en concreto
     */
    public function showHistory(Request $request){
        $salary = Salary::select('*')->join('salary_bank', 'salary.id', '=', 'salary_bank.salary_id')->where('salary_id','=',$request->id)->get();
        $salaries = SalaryUsers::select('*')->where('salary_id','=',$request->id)->get();
        $expenses = FinancialActivity::where('salary_id','=',$request->id)->where('type','=','expense')->sum('value');
        $incomes = FinancialActivity::where('salary_id','=',$request->id)->where('type','=','income')->sum('value');
        $financialActivity = FinancialActivity::select('*')->where('salary_id','=',$request->id)->orderBy('created_at', 'asc')->get();

        // Pintamos la información de salida. Para ello a iremos almacenando en una variable
        $print = '<div class="card">';
        $print .= '<div class="card-header">';
        $print .= '<div class="floatLeft">Resumen de gastos <strong><i>'.getMonth($financialActivity[0]['month']).' - '.$financialActivity[0]['year'].' / '.getNextMonthTitle($financialActivity[0]['month']).'</i></strong></div>';
        $print .= '</div>';
        $print .= '</div>';

        $print .= '<div class="card-body">';

        // SALARIOS
        $print .= '<table class="table">';
        $print .= '<tbody>';
        foreach($salaries as $infoSalaries){
            $print .= '<tr>';
            $print .= '<td><i><strong>'.$infoSalaries->name.'</strong></i> (<i>'.$infoSalaries->amount.'€</i>)</td>';
            $print .= '<td><i><strong>'.getDateFormat($infoSalaries->created_at).'</strong></i></td>';
            $print .= '</tr>';
        }
        $print .= '</tbody>';
        $print .= '</table>';

        // VALORES FIJOS BANCO
        $print .= '<table class="table">';
        $print .= '<tbody>';
        foreach($salary as $infoSalary){
            $print .= '<tr><td colspan="2" style="color:#0c5460;"><i>Suma de salarios</i> <strong><i>'.$infoSalary->money.'€</i></strong></td></tr>';
            $print .= '<tr><td style="color:#856404;"><i>Ahorro mensual fijo</i> <strong><i>'.$infoSalary->saveMonthly.'€</i></strong></td><td style="color:#78a057;"><i>Ahorro mensual total</i> <strong><i>'.$infoSalary->saveMonthly + ($infoSalary->bank_now_total - $infoSalary->bank_adding_savings).'€</i></strong></td></tr>';
            $print .= '<tr><td style="color:#448d8b;"><i>Inicio de mes </i> <strong><i>'.$infoSalary->bank_previous_month.'€</i></strong></td><td style="color:#448d8b;"><i>Ahorro mes finalizado (sumando ahorro mensual total)</i> <strong><i>'.$infoSalary->bank_now_total.'€</i></strong></td></tr>';
            $print .= '<tr><td style="color:#448d8b;"><i>El banco no bajó de...</i> <strong><i>'.$infoSalary->bank_adding_savings.'€</i></strong></td><td style="color:#b38c2d;"><i>Este mes ha sobrado...</i> <strong><i>'.round(($infoSalary->bank_now_total - $infoSalary->bank_adding_savings),2).'€</i></strong></td></tr>';
        }
        $print .= '</tbody>';
        $print .= '</table>';

        // INGRESOS-GASTOS MENSUALES
        $print .= '<table class="table">';
        $print .= '<tbody>';
        $print .= '<tr><td style="color:#155724;"><i>Ingresos mensuales</i> <strong><i>'.$incomes.'€</i></strong></td><td style="color:#721c24;"><i>Gastos menuales</i> <strong><i>'.$expenses.'€</i></strong></td></tr>';
        $print .= '</tbody>';
        $print .= '</table>';

        $print .= '<hr>';

        // GASTOS-INGRESOS
        $print .= '<table class="table">';
        $print .= '<thead>';
        $print .= '<tr><th colspan="3"><strong>Gastos detallados</strong></th></tr>';
        $print .= '<tr><th><strong>#</strong></th><th><strong>tipo</strong></th><th><strong>Cuantía</strong></th></tr>';
        $print .= '</thead>';
        $print .= '<tbody>';
        foreach($financialActivity as $financial){
            switch ($financial->type) {
                case 'income':
                    $print .= '<tr style="color:#155724;">';
                    $print .= '<td><i><strong>'.$financial->nameAction.' <br> <small>'.getDateFormat($financial->created_at).'</small> </strong></i></td>';
                    $print .= '<td><i><strong>Ingreso</strong></i></td>';
                    $print .= '<td><i><strong>'.$financial->value.'€</strong></i></td>';
                    $print .= '</tr>';
                break;
                case 'expense':
                    $print .= '<tr style="color:#721c24;">';
                    $print .= '<td><i><strong>'.$financial->nameAction.' <br> <small>'.getDateFormat($financial->created_at).'</small></strong></i></td>';
                    $print .= '<td><i><strong>Gasto</strong></i></td>';
                    $print .= '<td><i><strong>'.$financial->value.'€</strong></i></td>';
                    $print .= '</tr>';
                break;
            }
        }
        $print .= '</tbody>';
        $print .= '</table>';

        $print .= '</div>';
        return $print;
    }
}
