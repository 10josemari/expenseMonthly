@extends('layouts.layout')

@section('title','Categorías')

@section('body')
<!--{{auth()->user()}}-->
<!-- panel para insertar categorias -->
<div class="card marginTop marginSide">
    <div class="card-header">
            <div class="floatLeft">Insertar categoría</div> 
            <div class="floatRight">
                <i class="fas fa-compress fa-compress-cat"></i> 
                <i class="fas fa-compress-arrows-alt fa-compress-arrows-alt-cat" style="display:none;"></i>
            </div>
    </div>
    <div class="card-body cardBodyCategory" style="display:none;">
        <form id="formAddCategory" action="/addCategory" method="POST">
            @csrf
            <label for="inputName" class="sr-only">Nombre de categoría</label>
            <input type="text" id="inputName" class="form-control" name="name" placeholder="Nombre de categoría" value="{{ old('name') }}" autofocus @if ($errors->get('name')) style="border-color: #721c24;" @endif />
            @if ($errors->get('name')) 
                <small class="red">Error, debes rellenar el campo nombre de categoría</small>    
            @endif      
            <label for="inputDescription" class="sr-only">Descripción</label>
            <input type="text" id="inputDescription" class="form-control" name="description" placeholder="Descripción" @if ($errors->get('description')) style="border-color: #721c24;" @endif />
            @if ($errors->get('description')) 
                <small class="red">Error, debes rellenar el campo Descripción</small>    
            @endif
            <button class="btn btn-info btn-block btn-addCategory marginTop" type="submit" id="marginTop">Añadir categoría</button>
        </form>
    </div>
</div>
<!-- panel para insertar categorias -->

<hr class="marginSide">

<!-- listado que muestra las categorías creadas -->
<div class="marginSide">
    <table class="table">
        <thead>
          <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Descripción</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
        @if (count($categories) > 0)
            @foreach ($categories as $category)
            <tr>
                <td><a href="" class="updateCat" data-name="name" data-type="text" data-pk="{{$category->id}}" data-title="Edita el nombre">{{ $category->name }}</a></td>
                <td><a href="" class="updateCat" data-name="description" data-type="text" data-pk="{{$category->id}}" data-title="Edita la descripción">{{ $category->description }}</a></td>
                <td><i class="fas fa-trash red"></i></td>
            </tr>
            @endforeach
        @else
            <tr>
              <td class="textCenter" colspan="3">No hay categorías creadas</td>
            </tr>
        @endif
        </tbody>
        <tfoot>
          <tr><td colspan="3">{{ $categories->links() }}</td></tr>
        </tfoot>
    </table>
</div>
<!-- listado que muestra las categorías creadas -->

<!-- Js para editable --> 
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.updateCat').editable({
        url: '/updateCategory',
        type: 'text',
        pk: 1,
        name: 'name',
        title: 'Enter name'
    });
});
</script>
<!-- Js para editable --> 
@endsection