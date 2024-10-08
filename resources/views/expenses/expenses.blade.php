@extends('layouts.layout')

@section('title','Gastos')

@section('body')

<!-- Div donde se muestra el saldo actual pendiente -->
<div class="alert alert-secondary marginTop" role="alert">
    @foreach($salaryBank as $infoSalary) 
        <i>Actualmente tienes en el banco un total de... </i> <strong><i>{{round(($infoSalary->bank_now_total - $infoSalary->bank_adding_savings),2)}}€</i></strong>
    @endforeach
</div>
<!-- Div donde se muestra el saldo actual pendiente -->

<hr>

<!-- panel de gastos mensuales -->
<div class="card marginTop">
    <div class="card-header colorSecondary">
            <div class="floatLeft primary">Gastos</div> 
            <div class="floatRight primary">
                <i class="fas fa-compress fa-compress-expense" style="display:none;"></i> 
                <i class="fas fa-compress-arrows-alt fa-compress-arrows-alt-expense"></i>
            </div>
    </div>
    <div class="card-body cardBodyExpense">
        <form id="formAddExpense" action="/addExpense" method="POST">
            @csrf
            <label for="inputName" class="sr-only">Descripción</label>
            <input type="text" id="inputName" class="form-control" name="name" placeholder="Descripción gasto" value="{{ old('name') }}" autofocus @if ($errors->get('name')) style="border-color: #721c24;" @endif />
            @if ($errors->get('name')) 
                <small class="red">Error, debes rellenar el campo descripción</small>    
            @endif 
            <label for="inputExpense" class="sr-only">Categoría</label>
            <select class="form-control" id="inputExpense" class="form-control" name="category" placeholder="" @if ($errors->get('category')) style="border-color: #721c24;" @endif />
            <option value="">[ Selecciona una opción ]</option>
            @foreach($categories as $category) 
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
            </select>
            @if ($errors->get('category')) 
                <small class="red">Error, debes seleccionar una categoría</small>    
            @endif 
            <label for="inputExpense" class="sr-only">Salario</label>
            <input type="text" id="inputExpense" class="form-control" name="expense" placeholder="10.00" @if ($errors->get('expense')) style="border-color: #721c24;" @endif />
            @if ($errors->get('expense')) 
                <small class="red">Error, debes rellenar el campo con formato decimal.</small>    
            @endif
            <button class="btn btn-primary btn-block btn-addExpense marginTop" type="submit" id="marginTop">Añadir gasto <div class="spinner-addExpense floatRight none"><i class="fas fa-circle-notch fa-spin"></i></div></button>
        </form>
    </div>
</div>
<!-- panel de gastos mensuales -->

<hr>

<!-- Panel de editar o eliminar gastos mensuales -->
<div>
    <table class="table">
        <thead>
          <tr>
            <th scope="col" class="primary">Acción gasto</th>
            <th scope="col" class="primary">Valor</th>
            <th scope="col" class="primary"></th>
          </tr>
        </thead>
        <tbody>
        @if (count($expenses) > 0)
            @foreach ($expenses as $expense)
            <tr>
                <td><a href="" class="updateExp" data-name="nameAction" data-type="text" data-pk="{{$expense->id}}" data-title="Edita el nombre de la acción">{{ $expense->nameAction }}</a></td>
                <td><a href="" class="updateExp" data-name="value" data-type="text" data-pk="{{$expense->id}}" data-title="Edita la cantidad">{{ $expense->value }}</a></td>
                <td>
                    <form class="delExpense" action="/deleteExpense" method="POST">
                        @csrf
                        <a href="" data-toggle="deleteExpense" data-question="¿Quieres eliminar este registro?" data-id="{{ $expense->id }}"><i class="fas fa-trash red"></i></a>
                    </form>
                </td>
            </tr>
            @endforeach
        @else
            <tr>
              <td class="textCenter primary" colspan="3">No hay gastos añadidos</td>
            </tr>
        @endif
        </tbody>
        @if (count($expenses) > 0)
        <tfoot>
          <tr><td colspan="3">{{ $expenses->links() }}</td></tr>
        </tfoot>
        @endif
    </table>
</div>
<!-- Panel de editar o eliminar gastos mensuales -->

<!-- Js -->
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".btn-addExpense").on('click', function(e) {
        //$(".btn-addExpense").prop('disabled', true);
        $(".spinner-addExpense").css("display","block");
    });

    // Actualizamos campos de categorías
    $('.updateExp').editable({
        url: '/updateExpense',
        type: 'text',
        pk: 1,
        name: 'name',
        title: 'Enter name'
    });

    // Confirmamos si queremos eliminar categorías
    $('[data-toggle="deleteExpense"]').jConfirm({
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
        var send = $('.delExpense').attr('action');
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