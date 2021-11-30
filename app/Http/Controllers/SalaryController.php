<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\SalaryUsers;
use App\Models\SalaryBank;
use App\Models\Salary;
use App\Models\Config;
use Carbon\Carbon;

class SalaryController extends Controller
{
    /**
     * Vamos a la vista de añadir salario.
     * - Con la variable $salaries pasamos todos los salarios que han sido introducidos y almacenados en la BBDD.
     * - Con $countSalaries nos permite contar cuantos registros tenemos en el mes actual para así en la vista controlar la visibilidad de añadir más de 2 salarios en un mes.
     */
    public function index(){
        $salaries = Salary::select('salary.*','salary_bank.*','salary_users.*')->join('salary_users','salary_users.salary_id','=','salary.id')->join('salary_bank','salary_bank.salary_id','=','salary.id')->orderBy('salary.id', 'desc')->paginate(6);
        $countSalaries = Salary::select('salary.*','salary_bank.*','salary_users.*')->join('salary_users','salary_users.salary_id','=','salary.id')->join('salary_bank','salary_bank.salary_id','=','salary.id')->where('month','=',date('m'))->where('year','=',date('Y'))->get();
        return view('salary.salary', compact('salaries','countSalaries'));
    }

    /**
     * Añadimos el salario y hacemos las operaciones necesarias de manera interna.
     * Con request()->validate validamos que los campos pasados esten correctos.
     * Al insertar un registro de salario, contamos con 2 formas.
     * 1 forma. No tiene ningún tipo de registro. En esta caso, creamos el registro de salario cogiendo los valores por defecto de config y lo introducido por el formulario (else).
     *  -> en esta forma, evitamos que se dupliquen por mes las inserciones de salario, ya que se van a tratar por otro método
     * 2 forma. Si tenemos un registro de mes anterior. Se coge el total del mes anterior y se le suma el ahorro y el salario para trabajar con ese mes (if(count($salary) > 0){).
     */
    public function addSalary(){
        // Validamos que los datos introducidos sean correctos.
        request()->validate([
            'name' => 'required|string',
            'salary' => 'required|numeric',
        ]);
        //Obtenemos el ahorro mensual por defecto
        $saveMonthly = Config::select('*')->where('option','=','ahorro mensual')->get();
        //Obtenemos el mes-año anterior (se resta 1 mes para ver si tenemos registro). Si es mayor que 0 es porque hay registro
        $getDate = explode("-",getRestMonth(date('m')));
        $salary = Salary::select('salary.id','salary_bank.bank_now_total')->join('salary_users','salary_users.salary_id','=','salary.id')->join('salary_bank','salary_bank.salary_id','=','salary.id')->where('salary.month','=',$getDate[0])->where('salary.year','=',$getDate[1])->get();
        if(count($salary) > 0){
            $salaryMonth = Salary::select('*')->where('month','=',date('m'))->where('year','=',date('Y'))->get();
            if(count($salaryMonth) == 0){
                // Obtenemos los valores del mes anterior y los actualizamos para el mes actual.
                $bank_previous_month = $salary[0]['bank_now_total'];
                $bank_adding_savings = $salary[0]['bank_now_total'] + $saveMonthly[0]['value'];
                $bank_now_total = $salary[0]['bank_now_total'] + request()->salary;
                // Creamos la inserción del salario
                Salary::create([
                    'money' => request()->salary,
                    'month' => date('m'),
                    'year' => date('Y'),
                    'saveMonthly' => $saveMonthly[0]['value'],
                    'config_id' => $saveMonthly[0]['id'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                // Obtenemos el id creado
                $salary = Salary::select('*')->orderBy('id','desc')->get();
                // Creamos el registro.
                SalaryBank::create([
                    'bank_previous_month' => $bank_previous_month,
                    'bank_adding_savings' => $bank_adding_savings,
                    'bank_now_total' => $bank_now_total,
                    'salary_id' => $salary[0]['id'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                // Almacenamos el salario
                SalaryUsers::create([
                    'name' => request()->name,
                    'amount' => request()->salary,
                    'salary_id' => $salary[0]['id'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        } else {
            // Solo si no tenemos registro del mes actual, lo añadimos.
            $salary = Salary::select('*')->where('month','=',date('m'))->where('year','=',date('Y'))->get();
            if(count($salary) == 0){
                // Creamos el registro de salario
                Salary::create([
                    'money' => request()->salary,
                    'month' => date('m'),
                    'year' => date('Y'),
                    'saveMonthly' => $saveMonthly[0]['value'],
                    'config_id' => $saveMonthly[0]['id'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                // Obtenemos el id creado
                $salary = Salary::select('*')->orderBy('id','desc')->get();
                // Tratamos los importes. 
                // Capital inicial en $initial
                $initial = Config::select('*')->where('option','=','importe inicial')->get();
                // El total de ese mes en $bank_now_total
                $bank_now_total = $initial[0]['value'] + request()->salary;
                // Ahorros
                $bank_adding_savings = $initial[0]['value'] + $saveMonthly[0]['value'];
                // Creamos el registro.
                SalaryBank::create([
                    'bank_previous_month' => $initial[0]['value'],
                    'bank_adding_savings' => $bank_adding_savings,
                    'bank_now_total' => $bank_now_total,
                    'salary_id' => $salary[0]['id'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                // Almacenamos los salarios
                SalaryUsers::create([
                    'name' => request()->name,
                    'amount' => request()->salary,
                    'salary_id' => $salary[0]['id'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
        request()->session()->regenerateToken();
        return redirect()->route('salary'); 
    }

    /**
     * Añadimos un salario por un mes concreto seleccionado. Esta opción solo estará disponible si no disponemos ya de 2 salarios introducidos para el mismo mes.
     * - Contamos si tenemos ya introducido un salario anterior para entrar por el if o el else aunque el funcionamiento es muy parecido.
     *  -> $newMoney = sumamos el valor nuevo introducido con el ya almacenado.
     *  -> $bank_now_total = sumamos y obtenemos el valor nuevo del total del banco.
     * -> SalaryUsers::create, creamos el registro de salario.
     */
    public function addSalaryMonth(){
        $salary = Salary::select('salary.id as salary_id','salary.money as money','salary_bank.bank_now_total as bank_new_total','salary_bank.id as salaryBank_id','salary_users.amount as amount')->join('salary_users','salary_users.salary_id','=','salary.id')->join('salary_bank','salary_bank.salary_id','=','salary.id')->where('salary.month','=',request()->month)->where('salary.year','=',request()->year)->get();
        if(count($salary) > 0){
            $newMoney = $salary[0]['money'] + request()->salary;
            $bank_now_total = $salary[0]['bank_new_total'] + request()->salary;
            Salary::find($salary[0]['salary_id'])->update(['money' => $newMoney], ['updated_at' => Carbon::now()]);
            SalaryBank::find($salary[0]['salary_id'])->update(['bank_now_total' => $bank_now_total], ['updated_at' => Carbon::now()]);
        } else {
            $newMoney = $salary[0]['money'] + request()->salary;
            $bank_now_total = $salary[0]['bank_new_total'] + request()->salary;
            Salary::find($salary[0]['salary_id'])->update(['money' => $newMoney], ['updated_at' => Carbon::now()]);
            SalaryBank::find($salary[0]['salary_id'])->update(['bank_now_total' => $newMoney], ['updated_at' => Carbon::now()]);
        }
        SalaryUsers::create([
            'name' => request()->name,
            'amount' => request()->salary,
            'salary_id' => $salary[0]['salary_id'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        request()->session()->regenerateToken();
        return redirect()->route('salary'); 
    }

    /**
     * Actualizamos el nombre y cantidad de salario.
     * case name es para el nombre del usuario
     * case amount es para el valor del salario. Según si la cantidad introducida es a restar o a sumar entrar por un lado u otro.
     * Una vez todos los datos obtenidos, pasamos a actualizar todos los registros y recargar página.
     */
    public function updateSalary(Request $request){
        switch ($request->name) {
            case 'name':
                SalaryUsers::find($request->pk)->update([$request->name => $request->value],['updated_at' => Carbon::now()]);
            break;
            case 'amount':
                $salary = Salary::select('salary.*','salary_bank.*','salary_users.*')->join('salary_users','salary_users.salary_id','=','salary.id')->join('salary_bank','salary_bank.salary_id','=','salary.id')->where('salary_users.id','=',$request->pk)->get();
                if($salary[0]['amount'] > $request->value){
                    $newAmount = $salary[0]['amount'] - $request->value;
                    $newMoney = $salary[0]['money'] - $newAmount;
                    $newBankTotal = $salary[0]['bank_now_total'] - $newAmount;
                } else {
                    $newAmount = $request->value - $salary[0]['amount'];
                    $newMoney = $salary[0]['money'] + $newAmount;
                    $newBankTotal = $salary[0]['bank_now_total'] + $newAmount;
                }
                SalaryUsers::find($request->pk)->update([$request->name => $request->value], ['updated_at' => Carbon::now()]);
                SalaryBank::find($salary[0]['salary_id'])->update(['bank_now_total' => $newBankTotal], ['updated_at' => Carbon::now()]);
                Salary::find($salary[0]['salary_id'])->update(['money' => $newMoney], ['updated_at' => Carbon::now()]);
            break;
        }
        return response()->json(['success'=>'done']);
    }

    /**
     * Eliminamos un salario de una persona.
     * Controlamos si es el último pago o no referentes al mes actual para entrar por un sitio u otro.
     * Si no es el último, modificamos todos los valores del banco y salario antes de eliminar.
     * Si es el último, no hace falta modificar nada y eliminamos todo del tirón.
     */
    public function deleteSalary(Request $request){
        $salaryUsers = Salary::select('salary.id as salary_id','salary_bank.bank_now_total as bank_new_total','salary_bank.id as salaryBank_id','salary_users.amount as amount')->join('salary_users','salary_users.salary_id','=','salary.id')->join('salary_bank','salary_bank.salary_id','=','salary.id')->where('salary_users.id','=',$request->id)->get();
        $salary = Salary::select('salary.*','salary_bank.*','salary_users.*')->join('salary_users','salary_users.salary_id','=','salary.id')->join('salary_bank','salary_bank.salary_id','=','salary.id')->where('salary_users.salary_id','=',$salaryUsers[0]['salary_id'])->get();
        if(count($salary) == 1){
            // Eliminamos el salario del usuario
            SalaryUsers::find($request->id)->delete();
            // Eliminamos el registro desalarBanrk
            SalaryBank::find($salaryUsers[0]['salaryBank_id'])->delete();
            // Eliminamos el salario
            Salary::find($salaryUsers[0]['salary_id'])->delete();
        } else {
            $newMoney = $salary[0]['money'] - $salaryUsers[0]['amount'];
            $bank_now_total = $salaryUsers[0]['bank_new_total'] - $salaryUsers[0]['amount'];

            SalaryBank::find($salaryUsers[0]['salaryBank_id'])->update(['bank_now_total' => $bank_now_total], ['updated_at' => Carbon::now()]);
            Salary::find($salaryUsers[0]['salary_id'])->update(['money' => $newMoney], ['updated_at' => Carbon::now()]);
            SalaryUsers::find($request->id)->delete();
        }
        return response()->json(['success'=>'done']);
    }
}
