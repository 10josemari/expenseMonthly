@extends('layouts.layout')

@section('title','Historial')

@section('body')
<div class="row marginTop marginSide">
    <div class="col-12 marginBottom"><strong><i>Historial de gastos por meses</i></strong></div>
    <div class="col-4">
        <ul class="list-group">
        @foreach($salary as $infoSalary)
            <li class="list-group-item li-item" data-pk="{{$infoSalary->id}}"><strong><i>{{getMonth($infoSalary->month)}} - {{$infoSalary->year}} / {{getNextMonthTitle($infoSalary->month)}}</i></strong></li>
        @endforeach
        </ul>
    </div>
    <div class="col-8 col-history"><strong><i>Selecciona un mes del menú de la izquierda</i></strong></div>
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
});
</script>
<!-- Js -->
@endsection