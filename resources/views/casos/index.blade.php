@extends('layouts.app')
@section('body-class','no-carousel')

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
      <nav style="margin-top:12px" role="navigation" aria-label="Pagination Navigation">
        {{ $casos->onEachSide(1)->links() }}
      </nav>
    @endif
  @else
    <p>No hay casos registrados.</p>
  @endif
</div>
@endsection

@push('styles')
<style>
  /* NO tocar los íconos de la paginación */
  nav[aria-label*="Pagination"] svg { display:inline-block; width:1em; height:1em; }

  /* Apaga sliders/carruseles comunes cuando el body tiene .no-carousel */
  body.no-carousel .carousel,
  body.no-carousel .carousel-inner,
  body.no-carousel .carousel-item,
  body.no-carousel .carousel-control,
  body.no-carousel .carousel-control-prev,
  body.no-carousel .carousel-control-next,
  body.no-carousel .carousel-control-prev-icon,
  body.no-carousel .carousel-control-next-icon,
  body.no-carousel .glyphicon-chevron-left,
  body.no-carousel .glyphicon-chevron-right,
  body.no-carousel .icon-prev,
  body.no-carousel .icon-next,
  body.no-carousel .splide, body.no-carousel .splide__arrows, body.no-carousel .splide__arrow,
  body.no-carousel .glide,  body.no-carousel .glide__arrows,  body.no-carousel .glide__arrow,
  body.no-carousel .flickity-button, body.no-carousel .flickity-prev-next-button,
  body.no-carousel .tns-outer, body.no-carousel .tns-controls [data-controls="prev"],
  body.no-carousel .tns-controls [data-controls="next"],
  body.no-carousel .swiper, body.no-carousel .swiper-button-prev, body.no-carousel .swiper-button-next,
  body.no-carousel .owl-carousel, body.no-carousel .owl-nav, body.no-carousel .owl-prev, body.no-carousel .owl-next,
  body.no-carousel .flexslider, body.no-carousel .flex-direction-nav {
    display:none !important; width:0 !important; height:0 !important;
    overflow:hidden !important; pointer-events:none !important;
  }

  /* Muchas librerías dibujan flechas con ::before/::after */
  body.no-carousel *::before,
  body.no-carousel *::after { content:none !important; }
</style>
@endpush

@push('scripts')
<script>
(function(){
  // NO tocar nada dentro de la paginación
  const PAG = document.querySelector('nav[aria-label*="Pagination"]');
  const SEL = [
    // bootstrap / genérico
    '.carousel', '.carousel-inner', '.carousel-item',
    '.carousel-control', '.carousel-control-prev', '.carousel-control-next',
    '.carousel-control-prev-icon', '.carousel-control-next-icon',
    '.glyphicon-chevron-left', '.glyphicon-chevron-right', '.icon-prev', '.icon-next',
    // libs
    '.splide', '.splide__arrows', '.splide__arrow',
    '.glide', '.glide__arrows', '.glide__arrow',
    '.flickity-button', '.flickity-prev-next-button',
    '.tns-outer', '.tns-controls [data-controls="prev"]', '.tns-controls [data-controls="next"]',
    '.swiper', '.swiper-button-prev', '.swiper-button-next',
    '.owl-carousel', '.owl-nav', '.owl-prev', '.owl-next',
    '.flexslider', '.flex-direction-nav',
    // genéricos
    '[class*="arrow"]', '[class*="prev"]', '[class*="next"]'
  ].join(',');

  function nuke(root){
    root.querySelectorAll(SEL).forEach(el => {
      if (!PAG || !PAG.contains(el)) el.remove();
    });
  }

  // 1) borra los actuales
  nuke(document);

  // 2) si se crean luego, bórralos también
  const mo = new MutationObserver(muts => {
    for (const m of muts) for (const n of m.addedNodes) if (n.nodeType===1) nuke(n);
  });
  mo.observe(document.documentElement, {childList:true, subtree:true});
})();
</script>
@endpush
