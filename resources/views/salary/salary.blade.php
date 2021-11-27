@extends('layouts.layout')

@section('title','Salarios mensuales')

@section('body')
<!-- panel para insertar salarios -->
<div class="card marginTop marginSide">
    <div class="card-header">
            <div class="floatLeft">Salario mensual</div>
            <div class="floatRight">
                <i class="fas fa-compress fa-compress-sal" style="display:none;"></i> 
                <i class="fas fa-compress-arrows-alt fa-compress-arrows-alt-sal"></i>
            </div>
    </div>
    <div class="card-body cardBodySalary">

        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Atención!</strong> No se podrán añadir más de 2 salarios al mes.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form id="formAddSalary" action="/addSalary" method="POST">
            @csrf
            <label for="inputName" class="sr-only">Nombre</label>
            <input type="text" id="inputName" class="form-control" name="name" placeholder="Nombre" value="{{ old('name') }}" autofocus @if ($errors->get('name')) style="border-color: #721c24;" @endif />
            @if ($errors->get('name')) 
                <small class="red">Error, debes rellenar el campo nombre</small>    
            @endif      
            <label for="inputSalary" class="sr-only">Salario</label>
            <input type="text" id="inputSalary" class="form-control" name="salary" placeholder="Salario" @if ($errors->get('salary')) style="border-color: #721c24;" @endif />
            @if ($errors->get('salary')) 
                <small class="red">Error, debes rellenar el campo salario o el formato decimal introducido no es correcto</small>    
            @endif
            <button class="btn btn-info btn-block btn-addSalary marginTop" type="submit" id="marginTop">Añadir salario</button>
        </form>
    </div>
</div>
<!-- panel para insertar salarios -->

<hr class="marginSide">

<!-- listado de salarios creados -->
<div class="marginSide">
    <table class="table">
        <thead>
          <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Sueldo</th>
            <th scope="col">Mes-Año</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
        @if (count($salaries) > 0)
            @foreach ($salaries as $salary)
            <tr>
                <td class="textLeft"><a href="" class="updateSal" data-name="name" data-type="text" data-pk="{{$salary->id}}" data-title="Edita el nombre">{{ $salary->name }}</a></td>
                <td class="textLeft"><a href="" class="updateSal" data-name="money" data-type="text" data-pk="{{$salary->id}}" data-title="Edita el sueldo">{{ $salary->money }}</a></td>
                <td><strong>{{ getMonth($salary->month) }} - {{ $salary->year }} / {{ getNextMonth($salary->month) }}</strong></td>
                <td>
                    <form class="delSalary" action="/deleteSalary" method="POST">
                        @csrf
                        <a href="" data-toggle="deleteSalary" data-question="¿Quieres eliminar este registro?" data-id="{{ $salary->id }}"><i class="fas fa-trash red"></i></a>
                    </form>
                </td>
            </tr>
            @endforeach
        @else
            <tr>
              <td class="textCenter" colspan="4">No hay salarios creados</td>
            </tr>
        @endif
        </tbody>
        @if (count($salaries) > 0)
        <tfoot>
          <tr><td colspan="4">{{ $salaries->links() }}</td></tr>
        </tfoot>
        @endif
    </table>
</div>
<!-- listado de salarios creados -->

<!-- Js --> 
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Actualizamos campos de categorías
    $('.updateSal').editable({
        url: '/updateSalary',
        type: 'text',
        pk: 1,
        name: 'name',
        title: 'Enter name'
    });

    // Confirmamos si queremos eliminar salario (borrado físico)
    $('[data-toggle="deleteSalary"]').jConfirm({
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
        var send = $('.delSalary').attr('action');
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