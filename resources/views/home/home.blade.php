@extends('layouts.layout')

@section('title','Página principal')

@section('body')
<h1>Welcome back to Home</h1>
{{auth()->user()}}
@endsection