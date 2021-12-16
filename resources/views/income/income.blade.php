@extends('layouts.layout')

@section('title','Transferencias/Ingresos')

@section('body')
<!-- panel de transferencias/ingresos -->
<div class="card marginTop">
    <div class="card-header colorSecondary">
            <div class="floatLeft primary">Transferencias/Ingresos</div> 
            <div class="floatRight primary">
                <i class="fas fa-compress fa-compress-income" style="display:none;"></i> 
                <i class="fas fa-compress-arrows-alt fa-compress-arrows-alt-income"></i>
            </div>
    </div>
    <div class="card-body cardBodyIncome">
        <form id="formAddIncome" action="/addIncome" method="POST">
            @csrf
            <label for="inputName" class="sr-only">Descripción</label>
            <input type="text" id="inputName" class="form-control" name="name" placeholder="Descripción ingreso" value="{{ old('name') }}" autofocus @if ($errors->get('name')) style="border-color: #721c24;" @endif />
            @if ($errors->get('name')) 
                <small class="red">Error, debes rellenar el campo descripción</small>    
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
            <input type="text" id="inputIncome" class="form-control" name="income" placeholder="10.00" @if ($errors->get('income')) style="border-color: #721c24;" @endif />
            @if ($errors->get('income')) 
                <small class="red">Error, debes rellenar el campo con formato decimal.</small>    
            @endif
            <button class="btn btn-primary btn-block btn-addIncome marginTop" type="submit" id="marginTop">Añadir transferencia/ingreso <div class="spinner-addIncome floatRight none"><i class="fas fa-circle-notch fa-spin"></i></div></button>
        </form>
    </div>
</div>
<!-- panel de transferencias/ingresos -->

<hr>

<!-- Panel de editar o eliminar transferencias/ingresos -->
<div>
    <table class="table">
        <thead>
          <tr>
            <th scope="col" class="primary">Acción ingreso</th>
            <th scope="col" class="primary">Valor</th>
            <th scope="col" class="primary"></th>
          </tr>
        </thead>
        <tbody>
        @if (count($incomes) > 0)
            @foreach ($incomes as $income)
            <tr>
                <td><a href="" class="updateInc" data-name="nameAction" data-type="text" data-pk="{{$income->id}}" data-title="Edita el nombre de la acción">{{ $income->nameAction }}</a></td>
                <td><a href="" class="updateInc" data-name="value" data-type="text" data-pk="{{$income->id}}" data-title="Edita la cantidad">{{ $income->value }}</a></td>
                <td>
                    <form class="delIncome" action="/deleteIncome" method="POST">
                        @csrf
                        <a href="" data-toggle="deleteIncome" data-question="¿Quieres eliminar este registro?" data-id="{{ $income->id }}"><i class="fas fa-trash red"></i></a>
                    </form>
                </td>
            </tr>
            @endforeach
        @else
            <tr>
              <td class="textCenter primary" colspan="3">No hay ingresos añadidos</td>
            </tr>
        @endif
        </tbody>
        @if (count($incomes) > 0)
        <tfoot>
          <tr><td colspan="3">{{ $incomes->links() }}</td></tr>
        </tfoot>
        @endif
    </table>
</div>
<!-- Panel de editar o eliminar transferencias/ingresos -->

<!-- Js -->
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".btn-addIncome").on('click', function(e) {
        //$(".btn-addIncome").prop('disabled', true);
        $(".spinner-addIncome").css("display","block");
    });

    // Actualizamos campos de categorías
    $('.updateInc').editable({
        url: '/updateIncome',
        type: 'text',
        pk: 1,
        name: 'name',
        title: 'Enter name'
    });

    // Confirmamos si queremos eliminar categorías
    $('[data-toggle="deleteIncome"]').jConfirm({
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
        var send = $('.delIncome').attr('action');
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