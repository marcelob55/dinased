@extends('layouts.app')
@section('content')
<h1>Detalle del Caso: {{ $caso->numero_caso }}</h1>
<p><b>Label:</b> {{ $caso->label }} | <b>Fecha:</b> {{ $caso->fecha }}</p>
@if($caso->detalle)
  <p><b>Lugar:</b> {{ $caso->detalle->lugar_hecho }}</p>
  <p><b>Coordenadas:</b> {{ $caso->detalle->coordenadas }}</p>
  <p><b>Tipo arma:</b> {{ $caso->detalle->tipo_arma }}</p>
  <p><b>Indicios:</b> {{ $caso->detalle->indicios }}</p>
  <p><b>Motivación:</b> {{ $caso->detalle->motivacion }}</p>
  <p><b>Circunstancias:</b> {!! nl2br(e($caso->detalle->circunstancias)) !!}</p>
  <p><b>Entrevistas:</b> {!! nl2br(e($caso->detalle->entrevistas)) !!}</p>
  <p><b>Actividades:</b> {!! nl2br(e($caso->detalle->actividades)) !!}</p>
@else
  <p>Sin detalle. <a href="{{ route('detalle.edit',$caso) }}">Alimentar ahora</a></p>
@endif
@endsection
