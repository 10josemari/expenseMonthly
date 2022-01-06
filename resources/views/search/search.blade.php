@extends('layouts.layout')

@section('title','Buscador')

@section('body')
<div class="row marginTop">
    <div class="col-12"><input type="text" class="form-control" id="input-search" placeholder="Introduce una palabra a buscar" aria-label="Introduce una palabra a buscar"></div>
</div>

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

    $("#input-search").keyup(function () {
        var value = $(this).val();
        if(value!=""){
            var send = "/searchFilter";
            $.ajax({
                type:'GET',
                url:send,
                data:{value: value}
            }).done(function(data){
                $(".col-search").html(data);
            }).fail(function(err){
                console.log(err);
            });
        } else {
            $(".col-search").html("");
        }
    }).keyup();

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