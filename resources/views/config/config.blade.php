@extends('layouts.layout')

@section('title','Configuración')

@section('body')
    <!-- Configuración por defecto -->
    <div class="marginSide">
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
                            <td>@if(count($monthlySavings) > 0) <div class="iconsConfig floatLeft marginRight"><i class="fas fa-plus showSavings iconInherit"></i> <i class="fas fa-minus hideSavings iconInherit" style="display:none;"></i></div> @endif <div class="title floatLeft"><strong>{{ ucfirst($config->option) }}</strong></div></td>
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

    <!-- panel para ahorro mensual -->
    @if (count($monthlySavings) > 0)
    <div class="card marginTop marginSide">
        <div class="card-header">
            <div class="floatLeft">Ahorro mensual</div> 
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
                    @if (count($monthlySavings) > 0)
                        @foreach ($monthlySavings as $monthlySaving)
                        <tr>
                            <td><strong>{{ getMonth($monthlySaving->month) }} - {{ $monthlySaving->year }}</strong></td>
                            <td><a href="" class="updateSav" data-name="{{ getMonth($monthlySaving->month) }} - {{ $monthlySaving->year }}" data-type="text" data-pk="{{$monthlySaving->id}}" data-title="Edita la cuantía">{{ $monthlySaving->value }}</a></td>
                            <td>
                                <form class="delSaving" action="/deleteSaving" method="POST">
                                    @csrf
                                    <a href="" data-toggle="deleteSaving" data-question="¿Quieres eliminar este registro?" data-id="{{$monthlySaving->id}}"><i class="fas fa-trash red"></i></a>
                                </form>
                            </td>
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
    <!-- panel para ahorro mensual -->

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
            title: 'Enter name'
        });

        // Confirmamos si queremos eliminar categorías
        $('[data-toggle="deleteSaving"]').jConfirm({
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
            var send = $('.delSaving').attr('action');
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