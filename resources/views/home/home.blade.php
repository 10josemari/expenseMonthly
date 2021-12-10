@extends('layouts.layout')

@section('title','Página principal')

@section('body')
<!-- panel de información de ahorro -->
<div class="card marginTop marginSide">
    <div class="card-header">
            <div class="floatLeft">Resumen de gastos</div> 
            <div class="floatRight">
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
                <td><i><strong>{{$infoSalaries->name}}</strong></i> (<i>{{$infoSalaries->amount}}€</i>)</td>
                <td><i><strong>{{getDateFormat($infoSalaries->created_at)}}</strong></i></td>
            </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Valores fijos del banco -->
        <table class="table">
            <tbody>
            @foreach($salary as $infoSalary)
            <tr>
                <td colspan="2" style="color:#0c5460;"><i>Suma de salarios</i> <strong><i>{{$infoSalary->money}}€</i></strong></td>
            </tr>
            <tr>
                <td style="color:#856404;"><i>Ahorro mensual fijo</i> <strong><i>{{$infoSalary->saveMonthly}}€</i></strong></td>
                <td style="color:#78a057;"><i>Ahorro mensual total</i> <strong><i>{{$infoSalary->saveMonthly + ($infoSalary->bank_now_total - $infoSalary->bank_adding_savings)}}€</i></strong></td>
            </tr>
            <tr>
                <td style="color:#448d8b;"><i>Banco mes actual (sin ahorro)</i> <strong><i>{{$infoSalary->bank_previous_month}}€</i></strong></td>
                <td style="color:#448d8b;"><i>Banco mes actual (sumando ahorro mensual total)</i> <strong><i>{{$infoSalary->bank_now_total}}€</i></strong></td>
            </tr>
            <tr>
                <td style="color:#448d8b;"><i>El banco no debe de bajar de...</i> <strong><i>{{$infoSalary->bank_adding_savings}}€</i></strong></td>
                <td style="color:#b38c2d;"><i>Este mes puedes gastar...</i> <strong><i>{{round(($infoSalary->bank_now_total - $infoSalary->bank_adding_savings),2)}}€</i></strong></td>
            </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Ingresos/gastos mensuales -->
        <table class="table">
            <tbody>
                <tr>
                    <td style="color:#155724;"><i>Ingresos mensuales</i> <strong><i>{{$incomes}}€</i></strong></td>
                    <td style="color:#721c24;"><i>Gastos menuales</i> <strong><i>{{$expenses}}€</i></strong></td>
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
            <th colspan="3"><strong>Gastos detallados</strong></th>
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
                    <td><i><strong>Ingreso</strong></i></td>
                    <td><i><strong>{{$financial->value}}€</strong></i></td>
                </tr>
                @break
                @case('expense')
                <tr style="color:#721c24;">
                    <td><i><strong>{{$financial->nameAction}} <br> <small>{{getDateFormat($financial->created_at)}}</small></strong></i></td>
                    <td><i><strong>Gasto</strong></i></td>
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
@endsection