@extends('layouts.layout')

@section('title','Hucha')

@section('body')
<!--{{auth()->user()}}-->

<!-- BOTÓN PARA LIMPIAR TODO-->
<div class="btnClean marginTop">
    <form class="emptyPiggyBank" action="/cleanPiggyBank" method="POST">
        @csrf
        <a href="" data-toggle="cleanPiggyBank" data-question="¿Quieres reiniciar todos los valores introducidos?" data-id="8"><i class="fas fa-trash red"></i></a>
    </form>
</div>
<!-- BOTÓN PARA LIMPIAR TODO-->

<hr>

<!-- panel de hucha -->
<div class="card marginTop">
    <table class="table">
        <thead>
          <tr>
            <th scope="col" class="primary">Moneda</th>
            <th scope="col" class="primary">Cantidad</th>
            <th scope="col" class="primary">Total</th>
          </tr>
        </thead>
        <tbody>
        <div style="display:none;">{{$a=0}}</div>
        @foreach ($piggyBank as $piggy)
        <div style="display:none;">{{ $a = $a + getTotal($piggy->option,$piggy->amount) }}</div>
        <tr>
            <td>{{$piggy->option}}€</td>
            <td><a href="" class="updateAmount" data-name="amount" data-type="text" data-pk="{{$piggy->id}}" data-title="Edita el número de monedas">{{$piggy->amount}}</a></td>
            <td><strong>{{ getTotal($piggy->option,$piggy->amount) }}€</strong></td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr class="colorSecondary">
                <td class="primary"></td>
                <td class="primary"></td>
                <td class="primary"><strong>{{$a}}</strong>€</td>
            </tr>
        </tfoot>
    </table>
</div>
<!-- panel de hucha -->

<!-- js -->
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Actualizamos el número de monedas
    $('.updateAmount').editable({
        url: '/updateAmountPiggyBank',
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

    // Limpiar campos de hucha
    $('[data-toggle="cleanPiggyBank"]').jConfirm({
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
        var send = $('.emptyPiggyBank').attr('action');
        $.ajax({
            type:'POST',
            url:send,
            data:{id: id}
        }).done(function(data){
            if(data['success'] == "done"){
                location.reload()
            }
        }).fail(function(err){
            console.log(err);   
        });
    });
});
</script>
<!-- Js -->
@endsection