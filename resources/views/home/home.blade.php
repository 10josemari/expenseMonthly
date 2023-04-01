@extends('layouts.layout')

@section('title','Página principal')

@section('body')
<!-- panel de información de ahorro -->
<div class="card marginTop">
    <div class="card-header colorSecondary">
        <div class="floatLeft primary">Resumen</div> 
        <div class="floatRight primary">
            <i class="fas fa-compress fa-compress-home" style="display:none;"></i>
            <i class="fas fa-compress-arrows-alt fa-compress-arrows-alt-home"></i>
        </div>
    </div>
    <div class="card-body carBodyHome">
        <!-- Salarios -->
        <table class="table">
            <tbody>
            @foreach($salaries as $infoSalaries)
            <tr>
                <td><i class="primary"><strong>{{$infoSalaries->name}}</strong></i> (<i class="secondary">{{$infoSalaries->amount}}€</i>)</td>
                <td><i class="primary"><strong>{{getDateFormat($infoSalaries->created_at)}}</strong></i></td>
            </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Valores fijos del banco -->
        <table class="table">
            <tbody>
            @foreach($salary as $infoSalary)
            <tr>
                <td colspan="2" style="color:#0c5460;">
                    <i>Suma de salarios</i> <strong><i>{{$infoSalary->money}}€</i></strong> <i>@if($incomes > 0) ( {{$infoSalary->money + $incomes}}€ ) @endif</i> 
                </td>
            </tr>
            <tr>
                <td colspan="2" style="color:#78a057;">
                    <i>Actualmente hay...</i> <strong><i>{{$infoSalary->bank_now_total}}€</i></strong><br>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Ingresos/gastos mensuales -->
        <table class="table">
            <tbody>
                <tr>
                    <td style="color:#155724;"><i>Ingresos mensuales</i> <strong><i>{{$incomes}}€</i></strong></td>
                    <td style="color:#721c24;"><i>Gastos mensuales</i> <strong><i>{{$expenses}}€</i></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- panel de información de ahorro -->

<hr>

<!-- Gastos/Ingresos detallados -->
<table class="table">
    <thead>
        <tr>
            <th colspan="3">
                <div class="floatLeft">Gastos detallados</div>
                <div class="floatRight primary">
                <a class="nav-link colorTextPrimary" href="{{ route('expense') }}"><i class="fas fa-coins"></i> Añade un registro</a>
                </div>
            </th>
        </tr>
        <tr>
            <th><strong>#</strong></th>
            <th><strong>tipo</strong></th>
            <th><strong>Cuantía</strong></th>
        </tr>
    </thead>
    <tbody>
    @if(count($financialActivity) > 0)
        @foreach($financialActivity as $financial)
            @switch($financial->type)
                @case('income')
                <tr style="color:#155724;">
                    <td><i><strong>{{$financial->nameAction}} <br> <small>{{getDateFormat($financial->created_at)}}</small> </strong></i></td>
                    <td><i><strong>Ingreso</strong></i><br><small>{{$financial->name}}</small></td>
                    <td><i><strong>{{$financial->value}}€</strong></i></td>
                </tr>
                @break
                @case('expense')
                <tr style="color:#721c24;">
                    <td><i><strong>{{$financial->nameAction}} <br> <small>{{getDateFormat($financial->created_at)}}</small></strong></i></td>
                    <td><i><strong>Gasto</strong></i><br><small>{{$financial->name}}</small></td>
                    <td><i><strong>{{$financial->value}}€</strong></i></td>
                </tr>
                @break
            @endswitch
        @endforeach
    @else 
    <tr>
        <td colspan="3"><i><strong>No hay registros</strong></i></td>
    </tr>
    @endif
    </tbody>
</table>
<!-- Gastos/Ingresos detallados -->

<!-- gastos/ingresos por categoría -->
<table class="table">
    <thead>
        <tr>
            <th colspan="5"><strong>Ingresos/Gastos por categoría</strong></th>
        </tr>
        <tr>
            <th><strong>#</strong></th>
            <th><strong>Gastos</strong></th>
            <th><strong>Ingresos</strong></th>
            <th><strong>Importe</strong></th>
        </tr>
    </thead>
    @if(count($financialData) > 0)
        @foreach($financialData as $per)
        <tr>
            <td><strong>{{ $per['type'] }}</strong></td>
            <td style="color:#721c24;">
                <strong>{{$per['expense']}}€ (<i style="color:#000;">{{ getPercentByType('expense',$expenses,$incomes,$per['expense'],$per['income']) }}</i>)</strong>
            </td>
            <td style="color:#155724;">
            <strong>{{$per['income']}}€ (<i style="color:#000;">{{ getPercentByType('income',$expenses,$incomes,$per['expense'],$per['income']) }}</i>)</strong>
            </td>
            @if( $per['expense'] > $per['income'] )
                <td style="color:#721c24;"><strong>{{ $per['expense'] - $per['income'] }}€</strong></td>
            @elseif ($per['expense'] < $per['income'])
                <td style="color:#155724;"><strong>{{ $per['income'] - $per['expense'] }}€</strong></td>
            @else 
                <td><strong>0€</strong></td>
            @endif
        </tr>
        @endforeach
    @endif
    <tbody>
    </tbody>
</table>
<!-- gastos/ingresos por categoría -->
@endsection