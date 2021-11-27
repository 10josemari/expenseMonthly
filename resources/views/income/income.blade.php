@extends('layouts.layout')

@section('title','Transferencias/Ingresos')

@section('body')
<!--{{auth()->user()}}-->
<!-- panel de transferencias/ingresos -->
<div class="card marginTop marginSide">
    <div class="card-header">
            <div class="floatLeft">Transferencias/Ingresos</div> 
            <div class="floatRight">
                <i class="fas fa-compress fa-compress-income" style="display:none;"></i> 
                <i class="fas fa-compress-arrows-alt fa-compress-arrows-alt-income"></i>
            </div>
    </div>
    <div class="card-body cardBodyIncome">
        <form id="formAddIncome" action="/addIncome" method="POST">
            @csrf
            <label for="inputName" class="sr-only">Nombre</label>
            <input type="text" id="inputName" class="form-control" name="name" placeholder="Descripción ingreso" value="{{ old('name') }}" autofocus @if ($errors->get('name')) style="border-color: #721c24;" @endif />
            @if ($errors->get('name')) 
                <small class="red">Error, debes rellenar el campo nombre</small>    
            @endif 
            <label for="inputIncome" class="sr-only">Categoría</label>
            <select class="form-control" id="inputIncome" class="form-control" name="category" placeholder="" @if ($errors->get('category')) style="border-color: #721c24;" @endif />
            <option value="">[ Selecciona una opción ]</option>
            @foreach($categories as $category) 
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
            </select>
            @if ($errors->get('category')) 
                <small class="red">Error, debes seleccionar una categoría</small>    
            @endif 
            <label for="inputIncome" class="sr-only">Salario</label>
            <input type="text" id="inputIncome" class="form-control" name="income" placeholder="Salario" @if ($errors->get('income')) style="border-color: #721c24;" @endif />
            @if ($errors->get('income')) 
                <small class="red">Error, debes rellenar el campo salario o el formato decimal introducido no es correcto</small>    
            @endif
            <button class="btn btn-info btn-block btn-addIncome marginTop" type="submit" id="marginTop">Añadir transferencia/ingreso</button>
        </form>
    </div>
</div>
<!-- panel de transferencias/ingresos -->

<hr class="marginSide">
@endsection