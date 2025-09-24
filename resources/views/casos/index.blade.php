@extends('layouts.app')

@section('content')
<div class="casos-index">
  <h1>Listado de casos</h1>

  @if($casos->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Número de caso</th>
          <th>Nombre del caso</th>
          <th>Creado (fecha y hora)</th>
          <th>Usuario (generador)</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      @foreach($casos as $c)
        @php
          $creado = optional($c->created_at)?->timezone('America/Guayaquil');
        @endphp
        <tr>
          <td>{{ $c->id }}</td>
          <td>{{ $c->numero_caso }}</td>
          <td>{{ $c->label }}</td>
          <td>{{ $creado ? $creado->format('d/m/Y H:i') : '—' }}</td>
          <td>{{ $c->cedula }}</td>
          <td class="actions">
            <a href="{{ route('casos.show', $c) }}">Ver</a>
            <a href="{{ route('detalle.edit', $c) }}">Editar Caso Detalle</a>
            <a href="{{ route('segjudicial.create', $c->id) }}">Seg. judicial</a>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>

    @if($casos->hasPages())
      <nav style="margin-top:12px">
        {{ $casos->onEachSide(1)->links() }}
      </nav>
    @endif
  @else
    <p>No hay casos registrados.</p>
  @endif
</div>

<style>
  /* Paginación más compacta */
  .casos-index .pagination { font-size: .9rem; }
  .casos-index .pagination .page-link { padding: .25rem .5rem; line-height: 1.2; }
  .casos-index .pagination .page-link svg,
  .casos-index .pagination .page-link i { width: 14px; height: 14px; font-size: 14px; }

  /* Si en tu layout aún se cargan estilos de carrusel, minimiza flechas: */
  .casos-index .carousel-control { width: 28px; }
  .casos-index .carousel-control .glyphicon-chevron-left,
  .casos-index .carousel-control .glyphicon-chevron-right,
  .casos-index .carousel-control .icon-prev,
  .casos-index .carousel-control .icon-next {
    width: 16px; height: 16px; margin-top: -8px; font-size: 16px;
  }
  .casos-index .carousel-control-prev-icon,
  .casos-index .carousel-control-next-icon {
    width: 16px; height: 16px; background-size: 100% 100%;
  }
</style>
@endsection
