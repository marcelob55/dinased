@extends('layouts.app')

@section('content')
<h1>Listado de casos</h1>

@if($casos->count())
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Número de caso</th>
        <th>Nombre del caso</th>
        <th>Fecha</th>
        <th>Usuario(generador)</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
    @foreach($casos as $c)
      <tr>
        <td>{{ $c->id }}</td>
        <td>{{ $c->numero_caso }}</td>
        <td>{{ $c->label }}</td>
        <td>{{ $c->fecha }}</td>
        <td>{{ $c->cedula }}</td>
        <td class="actions">
          <a href="{{ route('casos.show', $c) }}">Ver</a>
          <a href="{{ route('detalle.edit', $c) }}">Editar</a>
		  <a href="{{ route('detalle.edit', $c) }}">Asignar</a>
		  <a href="{{ route('detalle.edit', $c) }}">Eliminar</a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <div style="margin-top:12px;">
    {{ $casos->links() }}
  </div>
@else
  <p>No hay casos registrados.</p>
@endif
@endsection
