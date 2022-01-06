@extends('layouts.layout')

@section('title','Historial')

@section('body')
<div class="row marginTop">
    <div class="col-12 marginBottom">
        <div class="floatLeft primary"></i><strong><i>Historial de gastos/ingresos, ahorros por meses</i></strong></div>
        <div class="floatRight primary"><i class="fas fa-broom"></i></div>
    </div>
    <div class="col-12">
        <ul class="list-group">
        @foreach($salary as $infoSalary)
            <li class="list-group-item li-item" data-pk="{{$infoSalary->id}}"><strong><i>{{getMonth($infoSalary->month)}} - {{$infoSalary->year}} / {{ getNextMonthTitle($infoSalary->month) }} - {{ getNextYear($infoSalary->month,$infoSalary->year) }}</i></strong></li>
        @endforeach
        </ul>
        @if (count($salary) > 0)
            {{ $salary->links() }}
        @endif
    </div>
    <div class="col-12 col-history marginTop primary"><strong><i>Selecciona un mes del menú superior</i></strong></div>
</div>

<!-- Js -->
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".li-item").on('click', function(e) {
        var id =  $(this).data('pk');
        var send = "/showHistory";
        $.ajax({
            type:'GET',
            url:send,
            data:{id: id}
        }).done(function(data){
            $(".col-history").html(data);
        }).fail(function(err){
            console.log(err);
        });

    });

    $(".fa-broom").on('click', function(e) {
        $(".col-history").html("");
    });
});
</script>
<!-- Js -->
@endsection