<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;

class CategoryController extends Controller
{
    /**
     * Función index que redirecciona a la vista de category
     * Con compact pasamos las variables. Si hay varias sería compact('categories','agent')
     */
    public function index(){
        /** Recogemos si ya tenemos categorías insertadas */
        $categories = Category::select('*')->paginate(10);
        return view('category.category', compact('categories'));
    }

    /**
     * Añadimos una categoría y retornamos a la vista de categorías
     */
    public function addCategory(){
        request()->validate([
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        //return request()->description;
        Category::create([
            'name' => request()->name,
            'description' => request()->description,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        request()->session()->regenerateToken();
        return redirect()->route('category');
    }

    /**
    * Actualizamos el nombre de la categoría
    */
    public function updateCategory(Request $request){
        switch ($request->name) {
            case 'name':
                Category::find($request->pk)->update([$request->name => $request->value],['updated_at' => Carbon::now()]);
            break;
            case 'description':
                Category::find($request->pk)->update([$request->name => $request->value], ['updated_at' => Carbon::now()]);
            break;
        }
        return response()->json(['success'=>'done']);
    }

    /**
     * Eliminamos una categoría
     */
    public function deleteCategory(Request $request){
        Category::find($request->id)->delete();
        return response()->json(['success'=>'done']);
    }
}
