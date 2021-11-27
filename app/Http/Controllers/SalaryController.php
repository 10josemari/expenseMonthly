<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Salary;
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
         * Comprobamos si el mes-año ya se encuentra almacenado
         */
        $countSalary = Salary::select('*')
            ->where('month','=',date('m'))
            ->where('year','=',date('Y'))
        ->get();

        if(count($countSalary) < 2){
            Salary::create([
                'name' => request()->name,
                'money' => request()->salary,
                'month' => date('m'),
                'year' => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
    
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
