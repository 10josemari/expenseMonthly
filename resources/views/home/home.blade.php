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
                <td colspan="2" style="color:#3B0468;"><i>El ahorro total se quedó el mes anterior en..</i> <strong><i>{{$infoSalary->bank_previous_month}}€</i></strong></td>
            </tr>
            <tr>
                <td colspan="2" style="color:#03857F;"><i>Ahorro mensual fijo...</i> <strong><i>{{$infoSalary->saveMonthly}}€</i></strong></td>
            </tr>
            @if(count($salarySubtractedTotal) == 0)
                <tr>
                    <td colspan="2" style="color:#03857F;"><i>El banco no debe de bajar de...</i> <strong><i>{{$infoSalary->bank_adding_savings}}€</i></strong></td>
                </tr>
                <tr>
                    <td colspan="2" style="color:#78a057;"><i>Ahorro mensual total...</i> <strong><i>{{$infoSalary->saveMonthly + ($infoSalary->bank_now_total - $infoSalary->bank_adding_savings)}}€</i></strong></td>
                </tr>
            @endif
            <tr>
                <td colspan="2" style="color:#78a057;">
                    <i>Ahorro total (actual)...</i> <strong><i>{{$infoSalary->bank_now_total}}€</i></strong><br>
                    @if(count($salarySubtractedTotal) != 0)
                        <small style='color:#000;'><strong><i>Las estadisticas y el total ahorrado han cambiado ya que se ha tenido que tocar el ahorro total. Pulsa <a href="{{ route('config') }}">aquí</a> para configurar los pagos realizados.</i></strong><br><hr>Lista de los pagos realizados:</small>
                        <ul type="square">
                        @foreach($salarySubtractedTotal as $subTotal)
                            <li style="color:#000;">{{$subTotal->name}} [ <i><strong>{{$subTotal->amount}}€</strong></i> ]</li>
                        @endforeach
                        </ul>
                    @endif
                </td>
            </tr>
            @if(count($salarySubtractedTotal) == 0)
                <tr>
                    <td colspan="2" style="color:#b38c2d;"><i>Este mes puedes gastar...</i> <strong><i>{{round(($infoSalary->bank_now_total - $infoSalary->bank_adding_savings),2)}}€</i></strong></td>
                </tr>
            @endif
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

<!-- porcentajes individuales -->
<table class="table">
    <thead>
        <tr>
            <th colspan="3"><strong>Porcentajes de gastos</strong></th>
        </tr>
        <tr>
            <th><strong>#</strong></th>
            <th><strong>total</strong></th>
            <th><strong>% individual</strong></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><i><strong>Ahorro mensual</strong></i></td>
            @if(count($salarySubtractedTotal) == 0)
                <td><i><strong>{{$saveMonth}}€</strong></i></td>
                <td><i><strong>{{getPercent($saveMonth,$totalMonth)}}%</strong></i></td>
            @else 
                <td><i><strong>{{$saveMonthly}}€</strong></i></td>
                <td><i><strong>{{getPercent($saveMonthly,$totalMonth)}}%</strong></i></td>
            @endif
        </tr>
        @foreach($percents as $percent)
            <tr>
            <td><i><strong>{{$percent->name}}</strong></i></td>
            <td><i><strong>{{$percent->total}}€</strong></i></td>
            <td><i><strong>{{getPercent($percent->total,$totalMonth)}}%</strong></i></td>
            </tr>
        @endforeach
    </tbody>
</table>
<!-- porcentajes individuales -->
@endsection