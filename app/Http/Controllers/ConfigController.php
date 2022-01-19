<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\SalarySubtractedTotal;
use App\Models\SalaryBank;
use App\Models\Salary;
use App\Models\Config;
use Carbon\Carbon;

class ConfigController extends Controller
{
    /**
     * Vamos a la vista config.
     * Comprobamos si ya tenemos por defecto asignados opciones de configuración. Tabla config.
     * - Obtenemos la variable $configs donde recogemos los valores por defecto de importe inicial y ahorro fijo mensual.
     * - Obtenemos la variable $salaries donde recogemos todos los salarios introducidos.
     */
    public function index(){
        $configs = Config::select('*')->get();
        $salaries = Salary::select('*')->orderBy('id', 'desc')->get();
        $saveTotal = Salary::sum('saveMonthly');
        $infoSalary = SalaryBank::select('*')->orderBy('id', 'desc')->get();
        foreach($infoSalary as $info){
            $total = $info->bank_now_total - $info->bank_adding_savings;
            $saveTotal = $saveTotal + $total;
        }
        $salarySubtractedTotal = Salary::select('salary_subtracted_total.id AS idSub','salary_subtracted_total.name AS name','salary_subtracted_total.amount AS amount','salary.id AS idSal','salary_bank.bank_now_total AS bank_now_total','salary.month AS month','salary.year AS year','salary.passed AS passed','salary_subtracted_total.created_at AS createdAt')->join('salary_subtracted_total','salary_subtracted_total.salary_id','=','salary.id')->join('salary_bank','salary_bank.salary_id','=','salary.id')->get();

        return view('config.config', compact('configs','salaries','saveTotal','salarySubtractedTotal'));
    }

    /**
     * Actualizamos los valores iniciales de config.
     * Ya que tenemos dos ids fijos para ambas entidades, hacemos un switch para actuar de distinta manera entre una u otra entidad.
     * - importe inicial (1). Este campo solo será rellenable 1 vez. No podrá ser modificado más.
     * - ahorro mensual (2). Este campo podrá ser modificado siempre, pero tiene el siguiente comportamiento.
     *  -> Si no hay salario de ese mes que se está insertando se modificada en la tabla config directamente.
    */
    public function updateConfig(Request $request){
        switch ($request->pk) {
            case 1:
                $valueConfig = Config::select('*')->where('id','=',$request->pk)->get();
                if($valueConfig[0]['value'] == "0.00"){
                    Config::find($request->pk)->update(['value' => $request->value], ['updated_at' => Carbon::now()]);
                }
            break;
            case 2:
                // Obtenemos el mes-año actual de la actualización y mes-año anterior (solo cambiaría o necesario para el mes de enero)
                $afterDates = explode("-",getRestMonth(date('m')));
                $salarySaveMonthly = Salary::select('*')->where('config_id','=',$request->pk)->where('month','=',$afterDates[0])->where('year','=',$afterDates[1])->get();
                // Si es 0, editamos el valor por defecto de la configuración.
                if(count($salarySaveMonthly) == 0){
                    Config::find($request->pk)->update(['value' => $request->value], ['updated_at' => Carbon::now()]);
                }
            break;
        }
        return response()->json(['success'=>'done']);
    }

    /**
     * Actualizamos el ahorro mensual referente al mes en cuestión. 
     * - Modificamos el campo saveMonthly de la tabla salary
     * - Modificamos el campo bank_adding_savings de la tabla salary_bank.
     * - Obtenemos el campo saveMonthly y bank_adding_savings para tratarlos de la siguiente forma:
     *  -> si el campo saveMonthly es mayor que valor modificado, restamos estos y la diferencia se la restamos a bank_adding_savings.
     *  -> si el campo saveMonthly es menos que valor modificado, restamos estos y la diferencia se la sumamos a bank_adding_savings.
     */
    public function updateSaving(Request $request){
        $salary = Salary::select('salary.id','salary.saveMonthly','salary_bank.bank_adding_savings')->join('salary_bank','salary_bank.salary_id','=','salary.id')->where('salary.id','=',$request->pk)->get();
        if($salary[0]['saveMonthly'] > $request->value){
            $bank_adding_savings = $salary[0]['saveMonthly'] - $request->value;
            $bank_adding_savings = $salary[0]['bank_adding_savings'] - $bank_adding_savings;
        } else {
            $bank_adding_savings = $request->value - $salary[0]['saveMonthly'];
            $bank_adding_savings = $salary[0]['bank_adding_savings'] + $bank_adding_savings;
        }
        
        Salary::find($request->pk)->update(['saveMonthly' => $request->value],['updated_at' => Carbon::now()]);
        SalaryBank::find($request->pk)->update(['bank_adding_savings' => $bank_adding_savings], ['updated_at' => Carbon::now()]);
        return response()->json(['success'=>'done']);
    }

    /**
     * Añadimos un registro para eliminar el total
     * - Se crea con los campos amount y name por defecto. Así luego se puede modificar al gusto
     */
    public function addSubTotal(Request $request){
        SalarySubtractedTotal::create([
            'name' => 'Introduce un nombre de acción',
            'amount' => '0.00',
            'salary_id' => $request->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return response()->json(['success' => 'done']);
    }

    /**
     * Permite restar cantidas al total ahorrado. Por ejemplo si se tiene que hacer pagos extra que tiren del ahorro.
     * - Modificamos el nombre de acción.
     * - Modificamos la cuantía del resto total, teniendo en cuenta que el campo bank_now_total se verá modificado.
     */
    public function updateSubTotal(Request $request){
        switch ($request->name) {
            case 'nameSubTotal':
                SalarySubtractedTotal::find($request->pk)->update(['name' => $request->value], ['updated_at' => Carbon::now()]);
            break;
            case 'amountSubTotal':
                $infoSalarySST = SalarySubtractedTotal::select('*')->where('id','=',$request->pk)->get();
                $infoSalarySB = SalaryBank::select('*')->where('salary_id','=',$infoSalarySST[0]['salary_id'])->get();
                if($request->value > $infoSalarySST[0]['amount']){
                    $total = $request->value - $infoSalarySST[0]['amount'];
                    $newTotal = $infoSalarySB[0]['bank_now_total'] - $total;
                } else {
                    $total = $infoSalarySST[0]['amount'] - $request->value;
                    $newTotal = $infoSalarySB[0]['bank_now_total'] + $total;
                }

                SalaryBank::find($infoSalarySB[0]['id'])->update(['bank_now_total' => $newTotal], ['updated_at' => Carbon::now()]);
                SalarySubtractedTotal::find($request->pk)->update(['amount' => $request->value], ['updated_at' => Carbon::now()]);
            break;
        }

        return response()->json(['success'=>'done']);
    }
}
