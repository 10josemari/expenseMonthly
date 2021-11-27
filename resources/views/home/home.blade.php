@extends('layouts.layout')

@section('title','Página principal')

@section('body')
<!--{{auth()->user()}}-->
<!-- panel de información de ahorro -->
<div class="card marginTop marginSide">
    <div class="card-header">
        <div class="floatLeft">{{ $title }}</div> 
        <div class="floatRight">
            <i class="fas fa-compress fa-compress-home" style="display:none;"></i> 
            <i class="fas fa-compress-arrows-alt fa-compress-arrows-alt-home"></i>
        </div>
    </div>
    <div class="card-body carBodyHome">
        @foreach($savings as $saving)
            <div class="row">
                <div class="col-12"><strong>Ahorro mensual </strong> - <strong>{{ $saving->value }}€</strong></div>
            </div>
        @endforeach
        <hr>
        @isset($salaries)
            <div class="row">
                <div class="col-12"><strong>Suma de salarios </strong> - <strong>{{ $salaries }}€</strong></div>
            </div>
        @endisset
    </div>
</div>
<!-- panel de información de ahorro -->
@endsection