@extends('layouts.layout')

@section('title','Configuración')

@section('body')
<!-- Configuración por defecto -->
<div class="marginSide marginTop">
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Cuantía</th>
          </tr>
        </thead>
        <tbody>
        @if (count($configs) > 0)
            @foreach ($configs as $config)
            <tr>
                @switch(ucfirst($config->option))
                    @case('Importe inicial')
                        <td><strong>{{ ucfirst($config->option) }}</strong></td>
                        <td><a href="" class="updateCon" data-name="{{ $config->option }}" data-type="text" data-pk="{{$config->id}}" data-title="Edita la cuantía">{{ $config->value }}</a></td>
                    @break
                    @case('Ahorro mensual')
                        <td>@if(count($salaries) > 0) <div class="iconsConfig floatLeft marginRight"><i class="fas fa-plus showSavings iconInherit"></i> <i class="fas fa-minus hideSavings iconInherit" style="display:none;"></i></div> @endif <div class="title floatLeft"><strong>{{ ucfirst($config->option) }} fijo</strong></div></td>
                        <td><a href="" class="updateCon" data-name="{{ $config->option }}" data-type="text" data-pk="{{$config->id}}" data-title="Edita la cuantía">{{ $config->value }}</a></td>
                    @break
                @endswitch
            </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<!-- Configuración por defecto -->

<!-- panel de ahorro mensual según el salario -->
@if (count($salaries) > 0)
<div class="card marginTop marginSide">
    <div class="card-header">
        <div class="floatLeft">Ahorro mensual (modificado)</div> 
    </div>
    <div class="card-body cardBodyConfig" style="display:none;">
        <div class="marginSide">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Mes-Año</th>
                    <th scope="col">Cuantía</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                @if (count($salaries) > 0)
                    @foreach ($salaries as $salary)
                    <tr>
                        <td><strong>{{ getMonth($salary->month) }} - {{ $salary->year }} / {{ getNextMonth($salary->month) }}</strong></td>
                        <td><a href="" class="updateSav" data-name="{{ getMonth($salary->month) }} - {{ $salary->year }}" data-type="text" data-pk="{{$salary->id}}" data-month="{{ $salary->month }}" data-title="Edita la cuantía">{{ $salary->saveMonthly }}</a></td>
                    </tr>
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
        title: 'Enter name'
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