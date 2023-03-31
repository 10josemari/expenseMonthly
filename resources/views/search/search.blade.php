@extends('layouts.layout')

@section('title','Buscador')

@section('body')
<!-- input de busqueda -->
<div class="row marginTop">
    <div class="col-12"><input type="text" class="form-control" id="input-search" placeholder="Introduce una palabra a buscar" aria-label="Introduce una palabra a buscar"></div>
</div>

<div class="row marginTop">
    <div class="col-4">
        <select class="form-control" name="monthSelect" id="monthSelect">
            <option value="">[ Mes ]</option>
                @for ($i = 1; $i <= 12; $i++)
                    @switch($i)
                        @case(1)<option value="{{$i}}">Enero</option>@break
                        @case(2)<option value="{{$i}}">Febrero</option>@break
                        @case(3)<option value="{{$i}}">Marzo</option>@break
                        @case(4)<option value="{{$i}}">Abril</option>@break
                        @case(5)<option value="{{$i}}">Mayo</option>@break
                        @case(6)<option value="{{$i}}">Junio</option>@break
                        @case(7)<option value="{{$i}}">Julio</option>@break
                        @case(8)<option value="{{$i}}">Agosto</option>@break
                        @case(9)<option value="{{$i}}">Septiembre</option>@break
                        @case(10)<option value="{{$i}}">Octubre</option>@break
                        @case(11)<option value="{{$i}}">Noviembre</option>@break
                        @case(12)<option value="{{$i}}">Diciembre</option>@break
                    @endswitch                
                @endfor
        </select>
    </div>
    <div class="col-4">
        <select class="form-control" name="yearSelect" id="yearSelect">
            <option value="">[ Año ]</option>
                @for ($i = 2021; $i <= 2050; $i++)
                    <option value="{{$i}}">{{$i}}</option>
                @endfor
        </select>
    </div>
    <div class="col-4">
        <select class="form-control" class="form-control" id="categorySelect" name="categorySelect">
            <option value="">[ Categoría ]</option>
            @foreach($categories as $category) 
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<hr>

<div class="row marginTop">
    <div class="col-12 col-search"></div>
</div>

<!-- Js -->
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var parameters = '';
    // Siempre que se añada algo en el input
    $("#input-search").keyup(function () {
        var category = $("#categorySelect").val();
        var year = $("#yearSelect").val();
        var month = $("#monthSelect").val();
        var value = $(this).val();
        if(value!=""){
            parameters = "value="+value+"&year="+year+"&category="+category+"&month="+month;
            sendParametersSearch(parameters)
        } else {
            parameters = "value="+value+"&year="+year+"&category="+category+"&month="+month;
            sendParametersSearch(parameters)
        }
    }).keyup();

    // Si el select de mes cambia.
    $(document).on('change', '#monthSelect', function(event) {
        var category = $("#categorySelect").val();
        var value = $("#input-search").val();
        var year = $("#yearSelect").val();
        var month = $(this).val();
        if(year!=""){
            parameters = "value="+value+"&year="+year+"&category="+category+"&month="+month;
            sendParametersSearch(parameters)
        } else {
            parameters = "value="+value+"&year="+year+"&category="+category+"&month="+month;
            sendParametersSearch(parameters)          
        }
    });

    // Si el select de año cambia.
    $(document).on('change', '#yearSelect', function(event) {
        var category = $("#categorySelect").val();
        var value = $("#input-search").val();
        var month = $("#monthSelect").val();
        var year = $(this).val();
        if(year!=""){
            parameters = "value="+value+"&year="+year+"&category="+category+"&month="+month;
            sendParametersSearch(parameters)
        } else {
            parameters = "value="+value+"&year="+year+"&category="+category+"&month="+month;
            sendParametersSearch(parameters)          
        }
    });

    // Si el select de categoría cambia.
    $(document).on('change', '#categorySelect', function(event) {
        var value = $("#input-search").val();
        var year = $("#yearSelect").val();
        var month = $("#monthSelect").val();
        var category = $(this).val();
        if(category!=""){
            parameters = "value="+value+"&year="+year+"&category="+category+"&month="+month;
            sendParametersSearch(parameters)
        } else {
            parameters = "value="+value+"&year="+year+"&category="+category+"&month="+month;
            sendParametersSearch(parameters)      
        }
    });
   
    // Historial
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

function sendParametersSearch(parameters){
    var send = "/searchFilter";
    $.ajax({
        type:'GET',
        url:send,
        data:{value:parameters}
    }).done(function(data){
        $(".col-search").html(data);
    }).fail(function(err){
        console.log(err);
    });
}
</script>
<!-- Js -->
@endsection