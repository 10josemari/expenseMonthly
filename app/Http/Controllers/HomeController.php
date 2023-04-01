<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SalarySubtractedTotal;
use App\Models\FinancialActivity;
use App\Models\MonthlySavings;
use Illuminate\Http\Request;
use App\Models\SalaryUsers;
use App\Models\Category;
use App\Models\Salary;
use App\Models\Config;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Una vez logueados, vamos a la vista home
     */
    public function index(){
        $salary = Salary::select('*')->join('salary_bank', 'salary.id', '=', 'salary_bank.salary_id')->where('passed','=',0)->get();
        $salaries = SalaryUsers::select('*')->where('salary_id','=',$salary[0]['id'])->get();
        $expenses = FinancialActivity::where('salary_id','=',$salary[0]['id'])->where('type','=','expense')->sum('value');
        $incomes = FinancialActivity::where('salary_id','=',$salary[0]['id'])->where('type','=','income')->sum('value');
        $financialActivity = FinancialActivity::select('financial_activity.type','financial_activity.nameAction','financial_activity.created_at','financial_activity.value','category.name')->join('category','financial_activity.category_id','=','category.id')->where('salary_id','=',$salary[0]['id'])->orderBy('financial_activity.created_at', 'desc')->get();
        $financialData = FinancialActivity::select("category.name","financial_activity.type",DB::raw("SUM(value) as total"))->where('salary_id','=',$salary[0]['id'])->join('category','financial_activity.category_id','=','category.id')->groupBy('category.name','financial_activity.type')->orderByRaw('total DESC')->get();
        $financialData = convertData($financialData);

        return view('home.home',compact('salary','salaries','expenses','incomes','financialActivity','financialData'));
    }

    /**
     * Vista historial
     */
    public function indexHistory(){
        $salary = Salary::select('*')->join('salary_bank', 'salary.id', '=', 'salary_bank.salary_id')->where('passed','=',1)->orderBy('salary.id', 'desc')->paginate(6);
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
        $financialActivity = FinancialActivity::select('financial_activity.type','financial_activity.nameAction','financial_activity.created_at','financial_activity.value','category.name','financial_activity.month','financial_activity.year')->join('category','financial_activity.category_id','=','category.id')->where('salary_id','=',$request->id)->orderBy('financial_activity.created_at', 'desc')->get();
        $percents = FinancialActivity::select("category.name", DB::raw("SUM(value) as total"))->where('salary_id','=',$request->id)->where('type','=','expense')->join('category','financial_activity.category_id','=','category.id')->groupBy('category.name')->orderByRaw('total DESC')->get();
        $salarySubtractedTotal = Salary::select('salary_subtracted_total.id AS idSub','salary_subtracted_total.name AS name','salary_subtracted_total.amount AS amount','salary.id AS idSal','salary_bank.bank_now_total AS bank_now_total','salary.month AS month','salary.year AS year')->join('salary_subtracted_total','salary_subtracted_total.salary_id','=','salary.id')->join('salary_bank','salary_bank.salary_id','=','salary.id')->where('salary_subtracted_total.salary_id','=',$request->id)->get();

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
            $print .= '<td><i class="primary"><strong>'.$infoSalaries->name.'</strong></i> (<i class="secondary">'.$infoSalaries->amount.'€</i>)</td>';
            $print .= '<td><i class="primary"><strong>'.getDateFormat($infoSalaries->created_at).'</strong></i></td>';
            $print .= '</tr>';
        }
        $print .= '</tbody>';
        $print .= '</table>';

        // VALORES FIJOS BANCO
        $print .= '<table class="table">';
        $print .= '<tbody>';
        foreach($salary as $infoSalary){
            $saveMonthly = $infoSalary->saveMonthly;
            $saveTotal = $infoSalary->saveMonthly + ($infoSalary->bank_now_total - $infoSalary->bank_adding_savings);
            $totalMonth = $infoSalary->money + $incomes;

            $text = '';
            if($incomes > 0){
                $totalIncomes = $infoSalary->money + $incomes;
                $text .= '( '.$totalIncomes.'€ )';
            }
            $print .= '<tr><td colspan="2" style="color:#0c5460;"><i>Suma de salarios</i> <strong><i>'.$infoSalary->money.'€</i></strong> <i>'.$text.'</i></td></tr>';
            $print .= '<tr><td  colspan="2" style="color:#3B0468;"><i>El ahorro inicial del mes empezó en...</i> <strong><i>'.$infoSalary->bank_previous_month.'€</i></strong></td></tr>';
            $print .= '<tr><td style="color:#03857F;"><i>Ahorro mensual fijo...</i> <strong><i>'.$infoSalary->saveMonthly.'€</i></strong></td></tr>';
            if(count($salarySubtractedTotal) == 0){
                $print .= '<tr><td style="color:#03857F;"><i>El banco no debía de bajar de...</i> <strong><i>'.$infoSalary->bank_adding_savings.'€</i></strong></td></td></tr>';
                $print .= '<tr><td style="color:#78a057;"><i>Se ahorró un total de...</i> <strong><i>'.$saveTotal.'€</i></strong> <i>('.round(($infoSalary->bank_now_total - $infoSalary->bank_adding_savings),2).'€ de ahorro extra)</i></td></tr>';    
            }
            $textF = '';
            if(count($salarySubtractedTotal) != 0){
                $text = "<br><small style='color:#000;'><strong><i>Las estadisticas y el total ahorrado cambiaron ya que se tuvo que tocar el ahorro total. <br><hr>Lista de los pagos realizados:</i></strong></small>";
                $lista = '<ul type="square">';
                foreach ($salarySubtractedTotal as $subTotal) {
                    $lista .= '<li style="color:#000;">'.$subTotal->name.' [ <i><strong>'.$subTotal->amount.'€</strong></i> ]</li>';
                }
                $lista .= '</ul>';

                $textF = $text.$lista;
            }
            $print .= '<tr><td style="color:#78a057;"><i>El mes terminó con un ahorro total de...</i> <strong><i>'.$infoSalary->bank_now_total.'€</i></strong>'.$textF.'</td></tr>';
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
                    $print .= '<td><i><strong>Ingreso</strong></i><br><small>'.$financial->name.'</small></td>';
                    $print .= '<td><i><strong>'.$financial->value.'€</strong></i></td>';
                    $print .= '</tr>';
                break;
                case 'expense':
                    $print .= '<tr style="color:#721c24;">';
                    $print .= '<td><i><strong>'.$financial->nameAction.' <br> <small>'.getDateFormat($financial->created_at).'</small></strong></i></td>';
                    $print .= '<td><i><strong>Gasto</strong></i><br><small>'.$financial->name.'</small></td>';
                    $print .= '<td><i><strong>'.$financial->value.'€</strong></i></td>';
                    $print .= '</tr>';
                break;
            }
        }
        $print .= '</tbody>';
        $print .= '</table>';

        $print .= '<hr>';

        // PORCENTAJES
        $print .= '<table class="table">';
        $print .= '<thead>';
        $print .= '<tr><th colspan="3"><strong>Porcentajes de gastos</strong></th></tr>';
        $print .= '<tr><th><strong>#</strong></th><th><strong>total</strong></th><th><strong>% individual</strong></th></tr>';
        $print .= '</thead>';
        $print .= '<tbody>';
        // Ahorro mensual. Siempre va fuera del foreach
        $print .= '<tr>';
        if(count($salarySubtractedTotal) == 0){
            $print .= '<td><i><strong>Ahorro mensual</strong></i></td>';
            $print .= '<td><i><strong>'.$saveTotal.'€</strong></i></td>';
            $print .= '<td><i><strong>'.getPercent($saveTotal,$totalMonth).'%</strong></i></td>';
        } else {
            $print .= '<td><i><strong>Ahorro mensual</strong></i></td>';
            $print .= '<td><i><strong>'.$saveMonthly.'€</strong></i></td>';
            $print .= '<td><i><strong>'.getPercent($saveMonthly,$totalMonth).'%</strong></i></td>';
        }
        $print .= '</tr>';
        foreach($percents as $percent){
            $print .= '<tr>';
            $print .= '<td><i><strong>'.$percent->name.'</strong></i></td>';
            $print .= '<td><i><strong>'.$percent->total.'€</strong></i></td>';
            $print .= '<td><i><strong>'.getPercent($percent->total,$totalMonth).'%</strong></i></td>';
            $print .= '</tr>';
        }
        $print .= '</tbody>';
        $print .= '</table>';

        $print .= '</div>';
        return $print;
    }

    /**
    * Buscador
    */
    public function indexSearch(){
        $categories = Category::select('*')->orderBy('name', 'asc')->get();
        return view('search.search',compact('categories'));
    }

    /**
    * Buscador con filtro
    */
    public function searchFilter(Request $request){
        // Tratamos el string obtenido para separarlo según el input metido
        $data = explode("&",$request->value);
        foreach($data as $key => $value){
            $dataValue = explode("=",$value);
            switch($dataValue[0]){
		        case 'value':
                    $valueSQL = empty($dataValue[1]) ? false : $dataValue[1];        
                break;
		        case 'year':
		        	$yearSQL = empty($dataValue[1]) ? false : $dataValue[1];
		        break;
		        case 'category':
		        	$categorySQL = empty($dataValue[1]) ? false : $dataValue[1];
		        break;
                case 'month':
		        	$monthSQL = empty($dataValue[1]) ? false : $dataValue[1];
                    if(strlen($monthSQL) == 1){
                        $monthSQL = str_pad($monthSQL, 2, "0", STR_PAD_LEFT);
                    }
		        break;
	        }
        }

        // Query que trae toda la info según los parámetros dados.
        if($valueSQL != "" OR $monthSQL != "" OR $yearSQL != "" OR $categorySQL != ""){
            $financialActivity = FinancialActivity::query();
            $financialActivity = $financialActivity->select('financial_activity.type','financial_activity.nameAction','financial_activity.created_at','financial_activity.value','category.name','financial_activity.month','financial_activity.year');
            $financialActivity = $financialActivity->join('category','financial_activity.category_id','=','category.id');
            if($valueSQL != ""){
                $financialActivity = $financialActivity->where('nameAction','like','%'.$valueSQL.'%');
            } 
            if($monthSQL != ""){
                $financialActivity = $financialActivity->where('month','=',$monthSQL);
            }
            if($yearSQL != ""){
                $financialActivity = $financialActivity->where('year','=',$yearSQL);
            }
            if($categorySQL != ""){
                $financialActivity = $financialActivity->where('category_id','=',$categorySQL);
            }
            $financialActivity = $financialActivity->orderBy('financial_activity.id', 'desc');
            //$financialActivity = $financialActivity->dd();
            $financialActivity = $financialActivity->get();
        } else {
            // Retornamos vacío si no hay datos
            return "";
        }

        // Obtenemos los totales de ingresos/gastos según los datos dados
        $totalIncome = 0;
        $totalExpense = 0;
        foreach($financialActivity as $financialActivityTotal){
            switch($financialActivityTotal->type){
                case 'income':
                    $totalIncome += $financialActivityTotal->value;
                break;
                case 'expense':
                    $totalExpense += $financialActivityTotal->value;
                break;
            }
        }

        $print = '<div class="row marginTop">';
        // Apartado de ingresos totales
        $print .= '<div class="col-6">';
        $print .= '<div class="card">';
        $print .= '<div class="card-header" style="background-color:#155724;color:#fff;">';
        $print .= '<div class="floatLeft"><strong>Total ingresos</strong></div>';
        $print .= '</div>';
        $print .= '</div>';
        $print .= '<div class="card-body bodyTotalIncome">';
        if($totalIncome > 0){
            $print .= '<p>Hay un total de <b>'.round($totalIncome,2).'€</b> en ingresos.</p>';
        } else {
            $print .= '<p>No hay <b>ingresos</b> con los parámetros dados.</p>';
        }
        $print .= '</div>';
        $print .= '</div>';

        // Apartado de gastos totales
        $print .= '<div class="col-6">';
        $print .= '<div class="card">';
        $print .= '<div class="card-header" style="background-color:#721c24;color:#fff;">';
        $print .= '<div class="floatLeft"><strong>Total gastos</strong></div>';
        $print .= '</div>';
        $print .= '</div>';
        $print .= '<div class="card-body">';
        if($totalExpense > 0){
            $print .= '<p>Hay un total de <b>'.round($totalExpense,2).'€</b> en gastos.</p>';
        } else {
            $print .= '<p>No hay <b>gastos</b> con los parámetros dados.</p>';
        }
        $print .= '</div>';
        $print .= '</div>';

        $print .= '</div>';

        // Apartado de gastos detallados
        $print .= '<div class="card marginTop">';
        $print .= '<div class="card-header">';
        $print .= '<div class="floatLeft"><strong>Gastos detallados</strong></div>';
        $print .= '</div>';
        $print .= '</div>';
        $print .= '<div class="card-body">';

        //GASTOS FILTRADOS
        $print .= '<table class="table">';
        $print .= '<thead>';
        $print .= '<tr><th><strong>#</strong></th><th><strong>tipo</strong></th><th><strong>Cuantía</strong></th></tr>';
        $print .= '</thead>';
        $print .= '<tbody>';
        foreach($financialActivity as $financial){
            switch ($financial->type) {
                case 'income':
                    $print .= '<tr style="color:#155724;">';
                    $print .= '<td><i><strong>'.$financial->nameAction.' <br> <small>'.getDateFormat($financial->created_at).'</small> </strong></i></td>';
                    $print .= '<td><i><strong>Ingreso</strong></i><br><small>'.$financial->name.'</small></td>';
                    $print .= '<td><i><strong>'.$financial->value.'€</strong></i></td>';
                    $print .= '</tr>';
                break;
                case 'expense':
                    $print .= '<tr style="color:#721c24;">';
                    $print .= '<td><i><strong>'.$financial->nameAction.' <br> <small>'.getDateFormat($financial->created_at).'</small></strong></i></td>';
                    $print .= '<td><i><strong>Gasto</strong></i><br><small>'.$financial->name.'</small></td>';
                    $print .= '<td><i><strong>'.$financial->value.'€</strong></i></td>';
                    $print .= '</tr>';
                break;
            }
        }

        $print .= '</div>';
        return $print;
    }
}
