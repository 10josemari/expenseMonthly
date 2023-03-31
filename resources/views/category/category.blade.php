@extends('layouts.layout')

@section('title','Categorías')

@section('body')
<!--{{auth()->user()}}-->
<!-- panel para insertar categorias -->
<div class="card marginTop">
    <div class="card-header colorSecondary">
        <div class="floatLeft primary">Insertar categoría</div> 
        <div class="floatRight primary">
            <i class="fas fa-compress fa-compress-cat" style="display:none;"></i> 
            <i class="fas fa-compress-arrows-alt fa-compress-arrows-alt-cat"></i>
        </div>
    </div>
    <div class="card-body cardBodyCategory">
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
            <button class="btn btn-primary btn-block btn-addCategory marginTop" type="submit" id="marginTop">Añadir categoría <div class="spinner-addCategory floatRight none"><i class="fas fa-circle-notch fa-spin"></i></div></button>
        </form>
    </div>
</div>
<!-- panel para insertar categorias -->

<hr>

<!-- listado que muestra las categorías creadas -->
<div class="listCategoriesActives">
    <table class="table">
        <thead>
          <tr>
            <th class="primary" scope="col">Nombre</th>
            <th class="primary" scope="col">Descripción</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
        @if (count($categories) > 0)
            @foreach ($categories as $category)
            <tr>
                <td><a href="" class="updateCat" data-name="name" data-type="text" data-pk="{{$category->id}}" data-title="Edita el nombre">{{ $category->name }}</a></td>
                <td><a href="" class="updateCat" data-name="description" data-type="text" data-pk="{{$category->id}}" data-title="Edita la descripción">{{ $category->description }}</a></td>
                <td>
                    <form class="delCategory" action="/deleteCategory" method="POST">
                        @csrf
                        <a href="" data-toggle="deleteCategory" data-question="¿Quieres desactivar este registro?" data-id="{{ $category->id }}"><i class="fas fa-trash red"></i></a>
                    </form>
                </td>
            </tr>
            @endforeach
        @else
            <tr>
              <td class="textCenter primary" colspan="3">No hay categorías creadas</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
<!-- listado que muestra las categorías creadas -->

<!-- listado que muestra las categorías desactivadas -->
<div class="listCategoriesInactives">
    <table class="table">
        <thead>
          <tr>
            <th class="primary" scope="col">Nombre</th>
            <th class="primary" scope="col">Descripción</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
        @if (count($categoriesNull) > 0)
            @foreach ($categoriesNull as $categoryNull)
            <tr>
                <td><a href="" class="updateCat" data-name="name" data-type="text" data-pk="{{$categoryNull->id}}" data-title="Edita el nombre">{{ $categoryNull->name }}</a></td>
                <td><a href="" class="updateCat" data-name="description" data-type="text" data-pk="{{$categoryNull->id}}" data-title="Edita la descripción">{{ $categoryNull->description }}</a></td>
                <td>
                    <form class="actCategory" action="/reactivateCategory" method="POST">
                        @csrf
                        <a href="" data-toggle="reactivateCategory" data-question="¿Quieres activar este registro?" data-id="{{ $categoryNull->id }}"><i class="fas fa-check green"></i></a>
                    </form>
                </td>
            </tr>
            @endforeach
        @else
            <tr>
              <td class="textCenter primary" colspan="3">No hay categorías desactivadas</td>
            </tr>
        @endif
        </tbody>
        @if (count($categories) > 0)
        <tfoot>
          <tr><td colspan="3">{{ $categories->links() }}</td></tr>
        </tfoot>
        @endif
    </table>
</div>
<!-- listado que muestra las categorías desactivadas -->

<!-- Js --> 
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".btn-addCategory").on('click', function(e) {
        //$(".btn-addCategory").prop('disabled', true);
        $(".spinner-addCategory").css("display","block");
    });

    // Actualizamos campos de categorías
    $('.updateCat').editable({
        url: '/updateCategory',
        type: 'text',
        pk: 1,
        name: 'name',
        title: 'Enter name'
    });

    // Confirmamos si queremos eliminar categorías
    $('[data-toggle="deleteCategory"]').jConfirm({
        //string: confirm button text
        confirm_text: 'Si',
        //string: deny button text
        deny_text: 'No',
        //string ('auto','top','bottom','left','right'): prefer#78261f location of the tooltip (defaults to auto if no space)
        position: 'left',
        //string: class(es) to add to the tooltip
        class: '',
        //boolean: if true, the deny button will be shown
        show_deny_btn: true,
        //string ('black', 'white', 'bootstrap-4', 'bootstrap-4-white')
        theme: 'bootstrap-4-white',
        //string ('tiny', 'small', 'medium', 'large')
        size: 'small'
    }).on('confirm', function(e){
        var id =  $(this).data('id');
        var send = $('.delCategory').attr('action');
        $.ajax({
            type:'POST',
            url:send,
            data:{id: id}
        }).done(function(data){
            if(data['success'] == "done"){
                location.reload()
            }
        }).fail(function(err){
            console.log(err);   
        });
    });

    // Confirmamos si queremos activar categorías
    $('[data-toggle="reactivateCategory"]').jConfirm({
        //string: confirm button text
        confirm_text: 'Si',
        //string: deny button text
        deny_text: 'No',
        //string ('auto','top','bottom','left','right'): prefer#78261f location of the tooltip (defaults to auto if no space)
        position: 'left',
        //string: class(es) to add to the tooltip
        class: '',
        //boolean: if true, the deny button will be shown
        show_deny_btn: true,
        //string ('black', 'white', 'bootstrap-4', 'bootstrap-4-white')
        theme: 'bootstrap-4-white',
        //string ('tiny', 'small', 'medium', 'large')
        size: 'small'
    }).on('confirm', function(e){
        var id =  $(this).data('id');
        var send = $('.actCategory').attr('action');
        $.ajax({
            type:'POST',
            url:send,
            data:{id: id}
        }).done(function(data){
            if(data['success'] == "done"){
                location.reload()
            }
        }).fail(function(err){
            console.log(err);   
        });
    });
});
</script>
<!-- Js -->
@endsection