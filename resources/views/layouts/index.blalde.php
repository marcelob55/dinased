@extends('layouts.app')
@section('content')
<h1>Casos</h1>
<a href="{{ route('casos.create') }}">+ Nuevo caso</a>
<table border="1" cellpadding="6">
  <tr>
    <th>#</th><th>Número</th><th>Label</th><th>Fecha</th><th>Generador</th><th>Acciones</th>
  </tr>
  @foreach($casos as $c)
  <tr>
    <td>{{ $c->id }}</td>
    <td>{{ $c->numero_caso }}</td>
    <td>{{ $c->label }}</td>
    <td>{{ $c->fecha }}</td>
    <td>{{ $c->cedula }}</td>
    <td>
      <a href="{{ route('casos.show',$c) }}">Ver</a> |
      <a href="{{ route('detalle.edit',$c) }}">Alimentar</a>
    </td>
  </tr>
  @endforeach
</table>
{{ $casos->links() }}
@endsection
