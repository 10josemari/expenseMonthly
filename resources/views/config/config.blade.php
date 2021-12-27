@extends('layouts.layout')

@section('title','Configuración')

@section('body')
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
        <tr>
            <td class="primary"><strong>Ahorro total (suma de todos los meses)</strong></td>
            <td class="primary"><strong>{{ $saveTotal }}€</strong></td> 
        </tr>
        </tbody>
    </table>
</div>
<!-- Configuración por defecto -->

<!-- panel de ahorro mensual según el salario -->
@if (count($salaries) > 0)
<div class="card marginTop">
    <div class="card-header">
        <div class="floatLeft primary">Ahorro mensual (permite modificar el mes actual)</div> 
    </div>
    <div class="card-body cardBodyConfig">
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
                                <td><strong>{{ getMonth($salary->month) }} - {{ $salary->year }} / {{ getNextMonthTitle($salary->month) }}</strong></td>
                                <td><a href="" class="updateSav" data-name="{{ getMonth($salary->month) }} - {{ $salary->year }}" data-type="text" data-pk="{{$salary->id}}" data-month="{{ $salary->month }}" data-title="Edita la cuantía">{{ $salary->saveMonthly }}</a></td>
                            </tr>
                        @else
                            <tr>
                                <td><strong>{{ getMonth($salary->month) }} - {{ $salary->year }} / {{ getNextMonthTitle($salary->month) }}</strong></td>
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
});
</script>
<!-- Js --> 
@endsection