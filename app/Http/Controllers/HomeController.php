<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\MonthlySavings;
use Illuminate\Http\Request;
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
        /**
         * Obtenemos la cabecera de los meses
         */
        $title = Salary::where('month','=',date('m'))
            ->where('year','=',date('Y'))
        ->sum('money');
        if ($title == NULL) {
            $getDate = explode("-",getRestMonth(date('m')));
            $title = Salary::where('month','=',$getDate[0])
                ->where('year','=',$getDate[1])
            ->sum('money');
            if ($title != NULL) {
                $title = "Ahorro ".getMonth($getDate[0])." / ".$getDate[1];
            }
        } else {
            $title = "Ahorro ".getMonth(date('m'))." / ".date('Y');
        }
        $title = "-";
        /** 
         * Obtenemos el ahorro del mes actual
         * - Si hay registro del mes actual, usamos ese mes.
         * - En el caso de que no tenemos ahorra de dicho mes, restamos 1 mes y cogemos el anterior.
         * - En el caso de que no tengamos ahorro en ningún rango de meses, cogemos el ahorro por defecto.
         */
        $savings = MonthlySavings::select('*')
            ->where('month','=',date('m'))
            ->where('year','=',date('Y'))
        ->get();
        if (count($savings) == 0) {
            $getDate = explode("-",getRestMonth(date('m')));
            $savings = MonthlySavings::select('*')
                ->where('month','=',$getDate[0])
                ->where('year','=',$getDate[1])
            ->get();
            if (count($savings) == 0) {
                $savings = Config::select('*')
                    ->where('option','=','ahorro mensual')
                ->get();
            }
        }
        /**
         * Obtenemos la suma de ambos salarios.
         * - Si hay registro del mes actual, usamos ese mes.
         * - En el caso de que no tenemos ahorra de dicho mes, restamos 1 mes y cogemos el anterior.
         */
        $salaries = Salary::where('month','=',date('m'))
            ->where('year','=',date('Y'))
        ->sum('money');
        if ($salaries == NULL) {
            $getDate = explode("-",getRestMonth(date('m')));
            $salaries = Salary::where('month','=',$getDate[0])
                ->where('year','=',$getDate[1])
            ->sum('money');
        }

        return view('home.home',compact('savings','salaries','title'));
    }
}
