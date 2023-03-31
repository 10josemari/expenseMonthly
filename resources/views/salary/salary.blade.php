@extends('layouts.layout')

@section('title','Salarios mensuales')

@section('body')
<!-- panel para insertar salarios -->
<div class="card marginTop cardAddSalary">
    <div class="card-header cardAddSalary colorSecondary">
        <div class="floatLeft primary">Salario mensual</div>
        <div class="floatRight primary">
            <i class="fas fa-compress fa-compress-sal" style="display:none;"></i>
            <i class="fas fa-compress-arrows-alt fa-compress-arrows-alt-sal"></i>
        </div>
    </div>
    <div class="card-body cardBodySalary">
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
            <button class="btn btn-primary btn-block btn-addSalary marginTop" type="submit" id="marginTop">Añadir salario <div class="spinner-addSalary floatRight none"><i class="fas fa-circle-notch fa-spin"></i></div></button>
        </form>
    </div>
</div>
<!-- panel para insertar salarios -->

<!-- panel para insertar salarios de un mes concreto -->
<div class="card marginTop cardAddSalaryMonth" style="display:none;">
    <div class="card-header cardAddSalaryMonth colorPrimary">
        <div class="floatLeft white">Salario mensual - mes concreto</div>
        <div class="floatRight white">
            <i class="fas fa-compress fa-compress-salMonth" style="display:none;"></i> 
            <i class="fas fa-compress-arrows-alt fa-compress-arrows-alt-salMonth"></i>
        </div>
    </div>
    <div class="card-body cardBodySalaryMonth">
        <form id="formAddSalaryMonth" action="/addSalaryMonth" method="POST">
            @csrf
            <input type="hidden" id="inputMonth" class="form-control" name="month" placeholder="Mes" value="" />
            <input type="hidden" id="inputYear" class="form-control" name="year" placeholder="Año" value="" />
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
            <button class="btn btn-primary-secondary btn-block btn-addSalary marginTop" type="submit" id="marginTop">Añadir salario <div class="spinner-addSalary floatRight none"><i class="fas fa-circle-notch fa-spin"></i></div></button>
        </form>
    </div>
</div>
<!-- panel para insertar salarios de un mes concreto -->

<hr>

<!-- panel para la visualización de salarios -->
<div>
    <table class="table">
        <thead>
          <tr>
            <th scope="col" class="primary">#</th>
            <th scope="col" class="primary">Nombre</th>
            <th scope="col" class="primary">Sueldo</th>
            <th scope="col" class="primary">Mes-Año</th>
            <th scope="col" class="primary"></th>
          </tr>
        </thead>
        <tbody>
        @if (count($salaries) > 0)
            @foreach ($salaries as $salary)
            <tr>
                <td class="textLeft">
                    @if(comprobateId($id,$salary->salary_id,$countSalaries))
                        <i class="fas fa-plus addSalaryMonth" data-month="{{ $salary->month }}" data-year="{{ $salary->year }}"></i>
                        <i class="fas fa-minus closeSalaryMonth" style="display:none;"></i>
                    @else
                        <i class="fas fa-ban"></i>
                    @endif
                </td>
                @if($salary->passed == 0)
                    <td class="textLeft"><a href="" class="updateSal" data-name="name" data-type="text" data-pk="{{$salary->id}}" data-title="Edita el nombre">{{ $salary->name }}</a></td>
                    <td class="textLeft"><a href="" class="updateSal" data-name="amount" data-type="text" data-pk="{{$salary->id}}" data-title="Edita el sueldo">{{ $salary->amount }}</a></td>
                    <td><strong>{{ getMonth($salary->month) }} - {{ $salary->year }} / {{ getNextMonthTitle($salary->month) }} - {{ getNextYear($salary->month,$salary->year) }}</strong></td>
                    <td>
                        <form class="delSalary" action="/deleteSalary" method="POST">
                            @csrf
                            <a href="" data-toggle="deleteSalary" data-question="¿Quieres eliminar este registro?" data-id="{{ $salary->id }}"><i class="fas fa-trash red"></i></a>
                        </form>
                    </td>
                @else
                    <td class="textLeft">{{ $salary->name }}</td>
                    <td class="textLeft">{{ $salary->amount }}</td>
                    <td><strong>{{ getMonth($salary->month) }} - {{ $salary->year }} / {{ getNextMonthTitle($salary->month) }} - {{ getNextYear($salary->month,$salary->year) }}</strong></td>
                    <td></td>
                @endif
            </tr>
            @endforeach
        @else
            <tr>
              <td class="textCenter primary" colspan="4">No hay salarios creados</td>
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
<!-- panel para la visualización de salarios -->

<!-- Js --> 
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".btn-addSalary").on('click', function(e) {
        //$(".btn-addSalary").prop('disabled', true);
        $(".spinner-addSalary").css("display","block");
    });

    // Actualizamos campos de categorías
    $('.updateSal').editable({
        url: '/updateSalary',
        type: 'text',
        pk: 1,
        name: 'name',
        title: 'Enter name'
    });

    // Controlamos la visibilidad de botón y div para añadir salarios
    $(".addSalaryMonth").on('click', function(e) {
        $("#inputMonth").val($(this).data('month'));
        $("#inputYear").val($(this).data('year'));
        $(".cardAddSalaryMonth").css("padding-bottom","2em");
        $(".cardAddSalary").css("display","none");
        $(".addSalaryMonth").css("display","none");
        $(".closeSalaryMonth").css("display","block");
        $(".cardAddSalaryMonth").css("display","block");
    });
    $(".closeSalaryMonth").on('click', function(e) {
        $("#inputMonth").val("");
        $("#inputYear").val("");
        $(".cardAddSalary").css("padding-bottom","2em");
        $(".cardAddSalary").css("display","block");
        $(".addSalaryMonth").css("display","block");
        $(".closeSalaryMonth").css("display","none");
        $(".cardAddSalaryMonth").css("display","none");
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