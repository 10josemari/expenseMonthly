<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PiggyBank;
use Carbon\Carbon;

class PiggyBankController extends Controller
{
    /**
     * Vamos a la vista de hucha.
     * Pasamos todas las monedas con su cantidad para controlar su contabilidad
     */
    public function index(){
        $piggyBank = PiggyBank::get();
        return view('piggyBank.piggyBank',compact('piggyBank'));
    }

    /**
     * Actualizamos el número de monedas
     */
    public function updateAmountPiggyBank(Request $request){
        PiggyBank::find($request->pk)->update([$request->name => $request->value], ['updated_at' => Carbon::now()]);
        return response()->json(['success'=>'done']);
    }

    /**
     * Reseteamos a 0 todas las monedas
     */
    public function cleanPiggyBank(Request $request){
        for ($i=1; $i <= $request->id ; $i++) { 
            PiggyBank::find($i)->update(['amount'=> "0"], ['updated_at' => Carbon::now()]);
        }
        return response()->json(['success'=>'done']);
    }
}
