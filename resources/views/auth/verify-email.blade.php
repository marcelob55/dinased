@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Verifica tu correo</h1>
  <p>Te enviamos un enlace de verificación. Si no lo recibiste, puedes solicitar otro.</p>

  @if (session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
  @endif

  <form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button class="btn btn-primary">Reenviar enlace</button>
  </form>
</div>
@endsection
