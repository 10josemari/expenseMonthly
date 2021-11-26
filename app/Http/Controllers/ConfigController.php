<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\MonthlySavings;
use Illuminate\Http\Request;
use App\Models\Config;
use Carbon\Carbon;

class ConfigController extends Controller
{
    /**
     * Vamos a la vista config
     */
    public function index(){
        /**
         * Comprobamos si ya tenemos por defecto asignados opciones de configuración
         * Comprobamos ademas si tiene registro en monthlySavings
         */
        $configs = Config::select('*')->get();
        $monthlySavings = MonthlySavings::select('*')->get();
        return view('config.config', compact('configs','monthlySavings'));
    }

    /**
     * Actualizamos los valores iniciales de config
     */
    public function updateConfig(Request $request){
        /**
         * Comprobamos si la opción modificada ya tiene registro distinto a 0. Al hablar de cuantía económica comprobamos que sea mayor que 0.
         * Si el valor es mayor que 0, lo insertamos en la tabla monthly_savings para que haga referencia a un ahorro distinto de otro mes.
         */
        $valueConfig = Config::select('*')->where('id','=',$request->pk)->get();
        if($valueConfig[0]['value'] != "0.00"){
            // Obtenemos el mes
            $month = date("m");
            // Obtenemos el año
            $year = date("Y");
            // Obtenemos el id
            $id = auth()->user()->id;

            // Comprobar si tenemo ya registro creado para ese mes
            $monthlySavings = MonthlySavings::select('*')
                ->where('month','=',$month)
                ->where('year','=',$year)
            ->get();
            if(count($monthlySavings) == 0){
                MonthlySavings::create([
                    'month' => $month,
                    'year' => $year,
                    'value' => $request->value,
                    'user_id' => $id,
                    'config_id' => $request->pk,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        } else {
            Config::find($request->pk)->update(['value' => $request->value], ['updated_at' => Carbon::now()]);
        }
        return response()->json(['success'=>'done']);
    }

    /**
     * Actualizamos los valores iniciales de config
     */
    public function updateSaving(Request $request){
        MonthlySavings::find($request->pk)->update(['value' => $request->value], ['updated_at' => Carbon::now()]);
        return response()->json(['success'=>'done']);
    }

    /**
     * Eliminamos un ahorro mensual
     */
    public function deleteSaving(Request $request){
        MonthlySavings::find($request->id)->delete();
        return response()->json(['success'=>'done']);
    }    
}
