@extends('layouts.layout')

@section('title','Configuración')

@section('body')
<!-- Permite restar total -->
@if (count($salaries) > 0)
<div class="card marginTop">
    <div class="card-header">
        <div class="floatLeft primary">Retirada</div> 
        <div class="floatRight primary">
            <i class="fas fa-compress fa-compress-config" style="display:none;"></i>
            <i class="fas fa-compress-arrows-alt fa-compress-arrows-alt-config"></i>
        </div>
    </div>
    <div class="card-body carBodyConfig">
        <div>
            <table class="table">
                <thead>
                    @if (count($salaries) > 0)
                        @foreach ($salaries as $salary)
                            @if($salary->passed == 0)
                            <tr>
                                <th colspan="2"><strong><i>Pulsa el icono para añadir un registro</i></strong></th>
                                <th>
                                    <form class="addSubTotal" action="/addSubTotal" method="POST">
                                        @csrf
                                        <a href="" data-toggle="addSubTotal" data-question="¿Quieres añadir un registro?" data-id="{{ $salary->id }}"><i class="fas fa-plus primary"></i></a>
                                    </form>
                                </th>
                            </tr>
                            @endif
                        @endforeach
                    @endif
                    <tr>
                      <th scope="col" class="primary">Mes-Año</th>
                      <th scope="col" class="primary">Acción</th>
                      <th scope="col" class="primary">Cuantía</th>
                    </tr>
                </thead>
                <tbody>
                @if ( count($salarySubtractedTotal) > 0)
                    @foreach ($salarySubtractedTotal as $salTotal)
                    <tr>
                        <td><strong>{{ getMonth($salTotal->month) }} - {{ $salTotal->year }} / {{ getNextMonthTitle($salTotal->month) }} - {{ getNextYear($salTotal->month,$salTotal->year) }}</strong></td>
                        @if($salTotal->passed == 0)
                            <td><a href="" class="editSubTotal" data-name="nameSubTotal" data-type="text" data-pk="{{$salTotal->idSub}}" data-title="Editar nombre de acción"><i>{{$salTotal->name}}</i></a><br><small>{{getDateFormat($salTotal->createdAt)}}</small></td>
                            <td><a href="" class="editSubTotal" data-name="amountSubTotal" data-type="text" data-pk="{{$salTotal->idSub}}" data-title="Añade una cuantía"><i>{{$salTotal->amount}}</i></a></td>
                        @else 
                            <td><i>{{$salTotal->name}}</i><br><small>{{getDateFormat($salTotal->createdAt)}}</small></td>
                            <td><i>{{$salTotal->amount}}</i></td>
                        @endif
                    </tr>
                    @endforeach
                @else 
                <tr>
                    <td colspan="3"><strong><i>No hay registros añadidos</i></strong></td>
                </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
<!-- Permite restar total -->

<!-- Configuración por defecto -->
<div class="marginTop">
    <table class="table">
        <thead>
          <tr>
            <th scope="col" class="primary">#</th>
            <th scope="col" class="primary">Cuantía</th>
          </tr>
        </thead>
        <tbody>
        @if (count($configs) > 0)
            @foreach ($configs as $config)
            <tr>
                @switch(ucfirst($config->option))
                    @case('Importe inicial')
                        @if($config->value == "0.00")
                            <td><strong>{{ ucfirst($config->option) }}</strong></td>
                            <td><a href="" class="updateCon" data-name="{{ $config->option }}" data-type="text" data-pk="{{$config->id}}" data-title="Edita la cuantía">{{ $config->value }}</a></td>
                        @else
                            <td><strong>{{ ucfirst($config->option) }}</strong></td>
                            <td>{{ $config->value }}€</td>
                        @endif
                    @break
                    @case('Ahorro mensual')
                        <td>@if(count($salaries) > 0) <div class="iconsConfig floatLeft marginRight"><i class="fas fa-plus showSavings iconInherit" style="display:none;"></i> <i class="fas fa-minus hideSavings iconInherit"></i></div> @endif <div class="title floatLeft"><strong>{{ ucfirst($config->option) }} fijo</strong></div></td>
                        <td><a href="" class="updateCon" data-name="{{ $config->option }}" data-type="text" data-pk="{{$config->id}}" data-title="Edita la cuantía">{{ $config->value }}</a></td>
                    @break
                @endswitch
            </tr>
            @endforeach
        @endif
        @if ($saveTotal != 0)
        <!--<tr>
            <td><strong><i>Ahorro total</i></strong></td>
            <td><strong><i>{{ $saveTotal }}€</i></strong></td>
        </tr>-->
        @endif
        </tbody>
    </table>
</div>
<!-- Configuración por defecto -->

<!-- panel de ahorro mensual según el salario -->
@if (count($salaries) > 0)
<div class="card marginTop marginBottom">
    <div class="card-header">
        <div class="floatLeft primary">Ahorro mensual (permite modificar el mes actual)</div> 
    </div>
    <div class="card-body">
        <div>
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col" class="primary">Mes-Año</th>
                    <th scope="col" class="primary">Cuantía</th>
                    <th scope="col" class="primary"></th>
                  </tr>
                </thead>
                <tbody>
                @if (count($salaries) > 0)
                    @foreach ($salaries as $salary)
                        @if($salary->passed == 0)
                            <tr>
                                <td><strong>{{ getMonth($salary->month) }} - {{ $salary->year }} / {{ getNextMonthTitle($salary->month) }} - {{ getNextYear($salary->month,$salary->year) }}</strong></td>
                                <td><a href="" class="updateSav" data-name="{{ getMonth($salary->month) }} - {{ $salary->year }}" data-type="text" data-pk="{{$salary->id}}" data-month="{{ $salary->month }}" data-title="Edita la cuantía">{{ $salary->saveMonthly }}</a></td>
                            </tr>
                        @else
                            <tr>
                                <td><strong>{{ getMonth($salary->month) }} - {{ $salary->year }} / {{ getNextMonthTitle($salary->month) }} - {{ getNextYear($salary->month,$salary->year) }}</strong></td>
                                <td>{{ $salary->saveMonthly }}</td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td class="textCenter" colspan="3">No hay ahorro mensuales creados</td>
                    </tr>
                @endif
                </tbody>
                @if (count($salaries) > 0)
                <tfoot>
                  <tr><td colspan="3">{{ $salaries->links() }}</td></tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endif
<!-- panel de ahorro mensual según el salario -->

<!-- Js --> 
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Actualizamos campos ahorro mensual fijo
    $('.updateCon').editable({
        url: '/updateConfig',
        type: 'text',
        pk: 1,
        name: 'name',
        title: 'Enter name',
        success: function(response, newValue) {
            if(response['success'] == "done"){
                location.reload()
            }
        }
    });

    // Actualizamos campo de ahorro mensual por mes
    $('.updateSav').editable({
        url: '/updateSaving',
        type: 'text',
        pk: 1,
        name: 'name',
        title: 'Enter name',
    });

    // Creamos un registro para eliminar el total
    $('[data-toggle="addSubTotal"]').jConfirm({
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
        var send = $('.addSubTotal').attr('action');
        $.ajax({
            type:'POST',
            url:send,
            data:{id: id}
        }).done(function(data){
            console.log(data);
            if(data['success'] == "done"){
                location.reload()
            }
        }).fail(function(err){
            console.log(err);   
        });
    });

    // Añadimos si hay alguna actualización (siempre restando al total)
    $('.editSubTotal').editable({
        url: '/updateSubTotal',
        type: 'text',
        pk: 1,
        name: 'name',
        title: 'Enter name',
    });
});
</script>
<!-- Js --> 
@endsection