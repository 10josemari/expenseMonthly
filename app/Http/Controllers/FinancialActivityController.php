<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\FinancialActivity;
use App\Models\SalaryBank;
use App\Models\Category;
use App\Models\Salary;
use Carbon\Carbon;

class FinancialActivityController extends Controller
{
    /**
     * Mostramos la vista de transferencia/ingreso.
     * Pasamos las categorías ya que las tendremos en el formulario de añadir ingreso.
     * Tambien pasamos para poder pintar los ingresos a editar, si tenemo un salario del mes posterior.
     */
    public function indexIncome(){
        $categories = Category::select('*')->where('deleted_at','=',NULL)->get();
        $salary = Salary::select('*')->where('passed','=',0)->get();
        $incomes = FinancialActivity::where('type','=','income')->where('salary_id','=',$salary[0]['id'])->orderBy('id', 'desc')->paginate(6);

        return view('income.income',compact('categories','incomes'));
    }

    /**
     * Añadimos el ingreso. Cuando se añade el ingreso, se tiene que sumar el ingreso al total del campo bank_now_total de la tabla salary_bank.
     * Con request_validate evaluamos que lo pasado por formulario sea lo correcto.
     * Para saber a que salario hay que modificarle el total comprobamos si tenemos salario del mes actual o del anterior.
     * Si es mayor que 0 es porque hay registro del mes actual. Al entrar, sumamos el valor enviado por formulario al campo de salary_bank y hacemos update.
     * Si es 0 es porque no hay registro de mes actual y obtenemos el anterior. Se suma al igual que el anterior y se actualiza.
     * Una vez hecho todo, se crea el registro.
     */
    public function addIncome(){
        request()->validate([
            'name' => 'required|string',
            'category' => 'required',
            'income' => 'required|numeric',
        ]);
        $salary = Salary::select('*')->where('month','=',date('m'))->where('year','=',date('Y'))->orderBy('id', 'desc')->get();
        if(count($salary) > 0){
            $salaryBank = SalaryBank::select('*')->where('salary_id','=',$salary[0]['id'])->get();
            $newTotal = $salaryBank[0]['bank_now_total'] + request()->income;
            SalaryBank::find($salaryBank[0]['id'])->update(['bank_now_total' => $newTotal],['updated_at' => Carbon::now()]);
        } else {
            return "ahora vamos por aquí";
            $getDate = explode("-",getRestMonth(date('m')));
            $salary = ConfigSalary::select('*')->where('month','=',$getDate[0])->where('year','=',$getDate[1])->get();
            $salaryBank = SalaryBank::select('*')->where('salary_id','=',$salary[0]['id'])->get();
            $newTotal = $salaryBank[0]['bank_now_total'] + request()->income;
            SalaryBank::find($salaryBank[0]['id'])->update(['bank_now_total' => $newTotal],['updated_at' => Carbon::now()]);
        }

        FinancialActivity::create([
            'month' => date('m'),
            'year' => date('Y'),
            'nameAction' => request()->name,
            'type' => 'income',
            'value' => request()->income,
            'category_id' =>request()->category,
            'user_id' => auth()->user()->id,
            'salary_id' => $salaryBank[0]['salary_id'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        request()->session()->regenerateToken();
        return redirect()->route('income');
    }

    /**
     * Actualizamos el nombre y el valor del ingreso (a la hora de actualizar el value, tocar el total de salaryBank).
     * Obtenems a través del final FinancialActivity el $salary_id para modificar el total.
     */
    public function updateIncome(Request $request){
        switch ($request->name) {
            case 'nameAction':
                FinancialActivity::find($request->pk)->update([$request->name => $request->value],['updated_at' => Carbon::now()]);
            break;
            case 'value':
                $infoFinancialActivity = FinancialActivity::select('*')->where('id','=',$request->pk)->get();
                $infoSalaryBank = SalaryBank::select('*')->where('salary_id','=',$infoFinancialActivity[0]['salary_id'])->get();
                if($infoFinancialActivity[0]['value'] > $request->value){
                    $newValue = $infoFinancialActivity[0]['value'] - $request->value;
                    $newBankTotal = $infoSalaryBank[0]['bank_now_total'] - $newValue;
                } else {
                    $newValue = $request->value - $infoFinancialActivity[0]['value'];
                    $newBankTotal = $infoSalaryBank[0]['bank_now_total'] + $newValue;
                }
                SalaryBank::find($infoSalaryBank[0]['id'])->update(['bank_now_total' => $newBankTotal], ['updated_at' => Carbon::now()]);

                FinancialActivity::find($request->pk)->update([$request->name => $request->value], ['updated_at' => Carbon::now()]);
            break;
        }
        return response()->json(['success'=>'done']);
    }

    /**
     * Eliminamos un pago.
     * Restamos el value en el total del banco ahorrado. El value asignado se le resta a bank_now_total
     */
    public function deleteIncome(Request $request){
        $infoFinancialActivity = FinancialActivity::select('*')->where('id','=',$request->id)->get();
        $infoSalaryBank = SalaryBank::select('*')->where('salary_id','=',$infoFinancialActivity[0]['salary_id'])->get();
        $newBankTotal = $infoSalaryBank[0]['bank_now_total'] - $infoFinancialActivity[0]['value'];
        SalaryBank::find($infoSalaryBank[0]['id'])->update(['bank_now_total' => $newBankTotal], ['updated_at' => Carbon::now()]);
        FinancialActivity::find($request->id)->delete();

        return response()->json(['success'=>'done']);
    }
}
