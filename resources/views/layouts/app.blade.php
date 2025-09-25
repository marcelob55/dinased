<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Sistema de Casos DINASED')</title>

  <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
  @stack('styles')
    
	
<style>
  /* ===== Carruseles comunes ===== */
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

  /* Bootstrap / genéricos */
  body.no-carousel [class*="carousel"],
  body.no-carousel [aria-label="Previous"],
  body.no-carousel [aria-label="Next"],

  /* Swiper */
  body.no-carousel .swiper,
  body.no-carousel .swiper-button-prev,
  body.no-carousel .swiper-button-next,

  /* Splide */
  body.no-carousel .splide,
  body.no-carousel .splide__arrows,
  body.no-carousel .splide__arrow,
  body.no-carousel .splide__arrow--prev,
  body.no-carousel .splide__arrow--next,

  /* Glide.js */
  body.no-carousel .glide,
  body.no-carousel .glide__arrows,
  body.no-carousel .glide__arrow,

  /* Flickity */
  body.no-carousel .flickity-button,
  body.no-carousel .flickity-prev-next-button,

  /* Tiny-slider */
  body.no-carousel .tns-outer,
  body.no-carousel .tns-controls [data-controls="prev"],
  body.no-carousel .tns-controls [data-controls="next"],

  /* OwlCarousel */
  body.no-carousel .owl-carousel,
  body.no-carousel .owl-nav,
  body.no-carousel .owl-prev,
  body.no-carousel .owl-next,

  /* Flexslider */
  body.no-carousel .flexslider,
  body.no-carousel .flex-direction-nav {
    display: none !important;
    width: 0 !important;
    height: 0 !important;
    overflow: hidden !important;
    pointer-events: none !important;
  }

  /* Muchas librerías dibujan las flechas con ::before/::after */
  body.no-carousel *::before,
  body.no-carousel *::after {
    content: none !important;
  }
</style>

<style>
  body.no-carousel *::before,
  body.no-carousel *::after { content: none !important; }
</style>

	
	
</head>
<body class="@yield('body-class')">

  {{-- ===== Header ===== --}}
  <header class="site-header">
    <div class="brand">
      <img src="{{ asset('assets/img/escudo-policia.jpg') }}" alt="Escudo Policía" class="logo">
      <img src="{{ asset('assets/img/dinased.jpg') }}" alt="DINASED" class="logo logo--right">
      <div class="brand__text">
        <span class="brand__title">
          DIRECCIÓN NACIONAL DE INVESTIGACIÓN DE MUERTES VIOLENTAS Y DESAPARECIDOS
        </span>
      </div>
    </div>

    {{-- Menú principal --}}
    <nav class="header-actions">
      @auth
        <a href="{{ route('casos.index') }}"
           class="btn btn--link {{ request()->routeIs('casos.index') ? 'is-active' : '' }}">Casos</a>

        <a href="{{ route('casos.create') }}"
           class="btn btn--link {{ request()->routeIs('casos.create') ? 'is-active' : '' }}">Nuevo caso</a>
 
		<a href="{{ route('segjudicial.dashboard') }}"
			class="btn btn--link {{ request()->routeIs('segjudicial.dashboard') ? 'is-active' : '' }}">
			Dashboard judicial
		</a>


        <form action="{{ route('logout') }}" method="POST" style="display:inline">
          @csrf
          <button type="submit" class="btn btn--pill btn--outline">Salir</button>
        </form>
      @endauth

      @guest
        <a class="btn btn--pill" href="{{ route('casos.index') }}">
          <span class="i i-home"></span> Inicio
        </a>
      @endguest
    </nav>
  </header>

  {{-- ===== Contenedor principal ===== --}}
  <main class="container">

    {{-- Mensajes flash / errores globales (opcionales, pero muy útiles) --}}
    @if (session('ok'))
      <div class="alert alert--success">{{ session('ok') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert--danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
      <div class="alert alert--danger">
        <strong>Revisa:</strong>
        <ul style="margin: .5rem 0 0 1rem;">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </main>

  {{-- ===== Footer ===== --}}
  <footer class="site-footer">
    <small>© {{ date('Y') }} DINASED — Sistema de Casos</small>
  </footer>
  

  {{-- al final, donde cargas el JS --}}
@unless (request()->is('casos*'))
  <script src="{{ asset('assets/js/app.js') }}"></script>
@endunless
@stack('scripts')


</body>
</html>
